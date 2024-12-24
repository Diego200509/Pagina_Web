<?php
// Incluir archivo de configuración para la conexión a la base de datos
include('../config/config.php');
// En sugerencias_queries.php
function obtenerSugerenciaPorId($id_sugerencia) {
    global $connection;  // Asegúrate de que la conexión esté disponible

    // Consulta SQL corregida
    $sql = "SELECT 
                s.ID_SUG AS id_sugerencia,
                u.NOM_USU AS nombre_usuario,
                u.EMAIL_USU AS correo_usuario,
                c.NOM_CAN AS nombre_candidato,
                s.SUGERENCIAS_SUG AS sugerencia,
                s.PROPUESTA_SUG AS propuesta,
                s.COMENTARIOS_SUG AS comentarios,
                s.ESTADO_SUG AS estado,
                s.CREATED_AT AS created_at
            FROM SUGERENCIAS s
            JOIN USUARIOS u ON s.ID_USU_PER = u.ID_USU
            JOIN PARTIDOS_POLITICOS p ON s.ID_PAR_SUG = p.ID_PAR
            JOIN CANDIDATOS c ON c.ID_PAR_CAN = p.ID_PAR
            WHERE s.ID_SUG = ?
            ORDER BY s.ID_SUG DESC";

    // Preparar la consulta
    if ($stmt = $connection->prepare($sql)) {
        // Bind el parámetro
        $stmt->bind_param("i", $id_sugerencia);  // El tipo es "i" para un entero

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el resultado
        $result = $stmt->get_result();

        // Si hay resultados, devolver la fila asociada
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();  // fetch_assoc() para obtener el resultado como un array asociativo
        } else {
            return null; // No se encuentra la sugerencia
        }
    } else {
        return null; // Error en la preparación de la consulta
    }
}



function obtenerTodasSugerencias()
{
    global $connection;

    // Consulta SQL corregida para obtener sugerencias junto con la información del usuario y del partido
    $sql = "SELECT 
    s.ID_SUG AS id_sugerencia,
    u.NOM_USU AS nombre_usuario,
    u.EMAIL_USU AS correo_usuario,
    c.NOM_CAN AS nombre_candidato,
    s.SUGERENCIAS_SUG AS sugerencia,
    s.PROPUESTA_SUG AS propuesta,
    s.ESTADO_SUG AS estado,
    s.CREATED_AT AS created_at
FROM SUGERENCIAS s
JOIN USUARIOS u ON s.ID_USU_PER = u.ID_USU
JOIN PARTIDOS_POLITICOS p ON s.ID_PAR_SUG = p.ID_PAR
JOIN CANDIDATOS c ON c.ID_PAR_CAN = p.ID_PAR
ORDER BY s.ID_SUG DESC";


    $result = $connection->query($sql);

    $sugerencias = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sugerencias[] = $row;
        }
    }

    return $sugerencias;
}

// Función para obtener el nombre del partido político según su ID_PAR
function obtenerNombrePartido($idPartido)
{
    global $connection;  // Hacer disponible la variable de conexión en la función

    // Consulta SQL para obtener el nombre del partido político
    $sql = "SELECT NOM_PAR FROM PARTIDOS_POLITICOS WHERE ID_PAR = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $idPartido);  // Vincular el ID del partido como parámetro
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se obtuvo algún resultado antes de acceder a él
    if ($result && $result->num_rows > 0) {
        $nombrePartido = $result->fetch_assoc();
        return $nombrePartido['NOM_PAR'];
    } else {
        return "Partido no encontrado";  // Mensaje alternativo si no se encuentra el partido
    }
}

// Función para obtener la cantidad de votos por cada partido político
function obtenerVotosPorPartido()
{
    global $connection;

    // Consulta SQL para contar los votos por partido
    $sql = "SELECT ID_PAR_VOT, COUNT(*) AS cantidad_votos 
            FROM VOTOS 
            GROUP BY ID_PAR_VOT";
    $result = $connection->query($sql);

    $votosPorPartido = [];
    while ($row = $result->fetch_assoc()) {
        $votosPorPartido[$row['ID_PAR_VOT']] = $row['cantidad_votos'];
    }

    return $votosPorPartido;
}

// Inicializar mensaje de error
$mensajeError = "";


// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];

    // Validar si se ha seleccionado un candidato
    if (isset($_POST['candidato']) && !empty($_POST['candidato'])) {
        $candidato = $_POST['candidato'];
    } else {
        $mensajeError = "Por favor selecciona un Partido politico.";

    }


    // Validar que todos los campos estén llenos
    if (!empty($nombre) && !empty($correo) && !empty($candidato)) {
        // Verificar si el usuario ya existe en la tabla USUARIOS
        $stmt_check = $connection->prepare("SELECT ID_USU FROM USUARIOS WHERE NOM_USU = ? OR EMAIL_USU = ?");
        $stmt_check->bind_param("ss", $nombre, $correo);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // El usuario ya existe, obtener su ID
            $usuario = $result_check->fetch_assoc();
            $id_usuario = $usuario['ID_USU'];

            // Verificar si el usuario ya ha votado
            $stmt_vot_check = $connection->prepare("SELECT * FROM REGISTROS_VOTOS WHERE ID_USU_RES = ?");
            $stmt_vot_check->bind_param("i", $id_usuario);
            $stmt_vot_check->execute();
            $result_vot_check = $stmt_vot_check->get_result();

            if ($result_vot_check->num_rows > 0) {
                // El usuario ya ha votado
                echo "
                <div style='
                    background-color: #ffe0b2; 
                    color: #e65100; 
                    border: 2px solid #ef6c00; 
                    padding: 20px; 
                    border-radius: 10px; 
                    font-family: Arial, sans-serif; 
                    max-width: 600px; 
                    margin: 290px auto 20px auto; 
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                    
                    <div style='display: flex; align-items: center;'>
                        <img src='https://img.icons8.com/fluency/48/000000/error.png' alt='Error de voto' style='margin-right: 15px;'/>
                        <h2 style='margin: 0; font-size: 24px;'>¡Ya has emitido un voto!</h2>
                    </div>
                    
                    <p style='font-size: 18px; margin-top: 10px;'>
                        <strong>Lo sentimos</strong>, pero según nuestros registros, este usuario o correo electrónico (<em>$correo</em>) ya ha emitido su voto en las elecciones.
                    </p>
                    
                    <p style='font-size: 16px;'>
                        Si crees que esto es un error, por favor contáctanos para más asistencia. Te agradecemos por participar.
                    </p>
                    
                    <div style='text-align: center; margin-top: 20px;'>
                                    <a href='../Sugerencias/index.php' style='
                            display: inline-block; 
                            padding: 10px 20px; 
                            background-color: #f57c00; 
                            color: white; 
                            text-decoration: none; 
                            border-radius: 5px; 
                            font-size: 16px;'>
                            Volver al inicio
                        </a>
                    </div>
                </div>";
            } else {
                // Permitir votar ya que no ha votado antes
                $stmt_voto = $connection->prepare("INSERT INTO VOTOS (ID_PAR_VOT) VALUES (?)");
                $stmt_voto->bind_param("i", $candidato);

                if ($stmt_voto->execute()) {
                    // Obtener ID del voto
                    $id_voto = $stmt_voto->insert_id;

                    // Registrar la relación en la tabla REGISTROS_VOTOS
                    $stmt_registro = $connection->prepare("INSERT INTO REGISTROS_VOTOS (ID_USU_RES, ID_VOT_RES) VALUES (?, ?)");
                    $stmt_registro->bind_param("ii", $id_usuario, $id_voto);
                    if ($stmt_registro->execute()) {
                        // Enviar correo al usuario
                        $url = "http://localhost:3001/send-email";  // Cambia a la ruta correcta de tu servidor Node.js
                        $data = array(
                            'to' => $correo,
                            'subject' => 'Confirmación de Voto',
                            'message' => 'Gracias por votar en las elecciones.'
                        );

                        // Usar cURL para hacer la solicitud HTTP POST a Node.js
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                        $response = curl_exec($ch);
                        curl_close($ch);
                        echo "
                        <div style='
                            background-color: #e0f7fa; 
                            color: #00695c; 
                            border: 2px solid #004d40; 
                            padding: 20px; 
                            border-radius: 10px; 
                            font-family: Arial, sans-serif; 
                            max-width: 600px; 
                            margin: 400px auto 20px auto; 
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                            
                            <div style='display: flex; align-items: center;'>
                                <img src='https://img.icons8.com/fluency/48/000000/ballot.png' alt='Voto registrado' style='margin-right: 15px;'/>
                                <h2 style='margin: 0; font-size: 24px;'>¡Tu voto ha sido registrado con éxito!</h2>
                            </div>
                            
                            <p style='font-size: 18px; margin-top: 10px;'>
                                <strong>¡Gracias por participar en las elecciones!</strong> Hemos registrado tu voto y se ha enviado una confirmación a tu correo electrónico (<em>$correo</em>).
                            </p>
                            
                            <p style='font-size: 16px;'>
                                Revisa tu bandeja de entrada (o la carpeta de spam) para confirmar la recepción. Si tienes alguna pregunta o inquietud, no dudes en contactarnos. ¡Tu participación es importante para el futuro!
                            </p>
                            
                            <div style='text-align: center; margin-top: 20px;'>
                                    <a href='../Sugerencias/index.php' style='
                                    display: inline-block; 
                                    padding: 10px 20px; 
                                    background-color: #00796b; 
                                    color: white; 
                                    text-decoration: none; 
                                    border-radius: 5px; 
                                    font-size: 16px;'>
                                    Volver al inicio
                                </a>
                            </div>
                        </div>";

                    } else {
                        echo "
                                        <div style='
                                            background-color: #ffcdd2; 
                                            color: #b71c1c; 
                                            border: 2px solid #d32f2f; 
                                            padding: 20px; 
                                            border-radius: 10px; 
                                            font-family: Arial, sans-serif; 
                                            max-width: 600px; 
                                            margin: 250px auto 20px auto;
                                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                                            
                                            <div style='display: flex; align-items: center;'>
                                                <img src='https://img.icons8.com/fluency/48/000000/error-cloud.png' alt='Error técnico' style='margin-right: 15px;'/>
                                                <h2 style='margin: 0; font-size: 24px;'>¡Error al registrar el voto!</h2>
                                            </div>
                                            
                                            <p style='font-size: 18px; margin-top: 10px;'>
                                                <strong>Lo sentimos</strong>, pero hemos encontrado un error mientras intentábamos registrar tu voto.
                                            </p>
                                            
                                            <p style='font-size: 16px;'>
                                                El problema técnico es el siguiente: <em>" . $stmt_voto->error . "</em>.
                                                Intenta de nuevo más tarde o contacta a soporte si el problema persiste.
                                            </p>
                                            
                                            <div style='text-align: center; margin-top: 20px;'>
                                    <a href='../Sugerencias/index.php' style='
                                                    display: inline-block; 
                                                    padding: 10px 20px; 
                                                    background-color: #d32f2f; 
                                                    color: white; 
                                                    text-decoration: none; 
                                                    border-radius: 5px; 
                                                    font-size: 16px;'>
                                                    Volver al inicio
                                                </a>
                                            </div>
                                        </div>";
                    }
                } else {
                    echo "
                    <div style='
                        background-color: #ffcdd2; 
                        color: #b71c1c; 
                        border: 2px solid #d32f2f; 
                        padding: 20px; 
                        border-radius: 10px; 
                        font-family: Arial, sans-serif; 
                        max-width: 600px; 
                        margin: 250px auto 20px auto; 
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                        
                        <div style='display: flex; align-items: center;'>
                            <img src='https://img.icons8.com/fluency/48/000000/error-cloud.png' alt='Error técnico' style='margin-right: 15px;'/>
                            <h2 style='margin: 0; font-size: 24px;'>¡Error al registrar el voto!</h2>
                        </div>
                        
                        <p style='font-size: 18px; margin-top: 10px;'>
                            <strong>Lo sentimos</strong>, pero hemos encontrado un error mientras intentábamos registrar tu voto.
                        </p>
                        
                        <p style='font-size: 16px;'>
                            El problema técnico es el siguiente: <em>" . $stmt_voto->error . "</em>.
                            Intenta de nuevo más tarde o contacta a soporte si el problema persiste.
                        </p>
                        
                        <div style='text-align: center; margin-top: 20px;'>
                <a href='../Sugerencias/index.php' style='
                                display: inline-block; 
                                padding: 10px 20px; 
                                background-color: #d32f2f; 
                                color: white; 
                                text-decoration: none; 
                                border-radius: 5px; 
                                font-size: 16px;'>
                                Volver al inicio
                            </a>
                        </div>
                    </div>";
                }
            }
            $stmt_vot_check->close();
        } else {
            // Insertar nuevo usuario si no existe
            $stmt = $connection->prepare("INSERT INTO USUARIOS (NOM_USU, EMAIL_USU, PASSWORD_USU, ROL_USU)VALUES (?, ?, NULL, 'USUARIO')");
            $stmt->bind_param("ss", $nombre, $correo);

            if ($stmt->execute()) {
                // Obtener ID del usuario recién insertado
                $id_usuario = $stmt->insert_id;

                // Preparar consulta para insertar voto
                $stmt_voto = $connection->prepare("INSERT INTO VOTOS (ID_PAR_VOT) VALUES (?)");
                $stmt_voto->bind_param("i", $candidato);

                if ($stmt_voto->execute()) {
                    // Obtener ID del voto
                    $id_voto = $stmt_voto->insert_id;

                    // Registrar la relación en la tabla REGISTROS_VOTOS
                    $stmt_registro = $connection->prepare("INSERT INTO REGISTROS_VOTOS (ID_USU_RES, ID_VOT_RES) VALUES (?, ?)");
                    $stmt_registro->bind_param("ii", $id_usuario, $id_voto);
                    if ($stmt_registro->execute()) {
                        // Enviar correo al usuario
                        $url = "http://localhost:3001/send-email";  // Cambia a la ruta correcta de tu servidor Node.js
                        $data = array(
                            'to' => $correo,
                            'subject' => 'Confirmación de Voto',
                            'message' => 'Gracias por votar en las elecciones.'
                        );

                        // Usar cURL para hacer la solicitud HTTP POST a Node.js
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

                        $response = curl_exec($ch);
                        curl_close($ch);

                        echo "
<div style='
    background-color: #e0f7fa; 
    color: #00695c; 
    border: 2px solid #004d40; 
    padding: 20px; 
    border-radius: 10px; 
    font-family: Arial, sans-serif; 
    max-width: 700px; 
    margin: 400px auto 20px auto; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
    
    <div style='display: flex; align-items: center;'>
        <img src='https://img.icons8.com/fluency/48/000000/ballot.png' alt='Voto registrado' style='margin-right: 15px;'/>
        <h2 style='margin: 0; font-size: 24px;'>¡Tu voto ha sido registrado con éxito!</h2>
    </div>
    
    <p style='font-size: 18px; margin-top: 50px;'>
        <strong>¡Gracias por participar en las elecciones!</strong> Hemos registrado tu voto y se ha enviado una confirmación a tu correo electrónico (<em>$correo</em>).
    </p>
    
    <p style='font-size: 16px;'>
        Revisa tu bandeja de entrada (o la carpeta de spam) para confirmar la recepción. Si tienes alguna pregunta o inquietud, no dudes en contactarnos. ¡Tu participación es importante para el futuro!
    </p>
    
    <div style='text-align: center; margin-top: 20px;'>
        <a href='../Sugerencias/index.php' style='
            display: inline-block; 
            padding: 10px 20px; 
            background-color: #00796b; 
            color: white; 
            text-decoration: none; 
            border-radius: 5px; 
            font-size: 16px;'>
            Volver al inicio
        </a>
    </div>
</div>";

                    } else {
                        echo "Error al registrar el voto: " . $stmt_registro->error;
                    }
                } else {
                    echo "Error al registrar el voto: " . $stmt_voto->error;
                }
            } else {
                echo "Error al registrar el usuario: " . $stmt->error;
            }

            // Cerrar declaraciones
            $stmt->close();
            $stmt_voto->close();
            $stmt_registro->close();
        }
        $stmt_check->close();
    } else {
        // Mostrar mensaje de error si no se completan todos los campos
        if (!empty($mensajeError)) {
            echo $mensajeError;  // Mostrar el mensaje de error
        } else {

            echo "
            <div style='
                background-color: #ffe0b2; 
                color: #e65100; 
                border: 2px solid #ef6c00; 
                padding: 20px; 
                border-radius: 10px; 
                font-family: Arial, sans-serif; 
                max-width: 600px; 
                margin: 150px auto 20px auto; /* Ajuste para que aparezca más abajo */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>
                
                <div style='display: flex; align-items: center;'>
                    <img src='https://img.icons8.com/fluency/48/000000/warning.png' alt='Advertencia' style='margin-right: 15px;'/>
                    <h2 style='margin: 0; font-size: 24px;'>¡Atención!</h2>
                </div>
                
                <p style='font-size: 18px; margin-top: 10px;'>
                    <strong>Por favor, asegúrate de completar todos los campos</strong> del formulario antes de enviar tu voto.
                </p>
                
                <p style='font-size: 16px;'>
                    Cada información es importante para que tu voto sea contado correctamente. 
                    Tómate un momento para revisar antes de continuar.
                </p>
                
                <div style='text-align: center; margin-top: 20px;'>
                    <a href='javascript:history.back()' style='
                        display: inline-block; 
                        padding: 10px 20px; 
                        background-color: #ef6c00; 
                        color: white; 
                        text-decoration: none; 
                        border-radius: 5px; 
                        font-size: 16px;'>
                        Volver atrás
                    </a>
                </div>
            </div>";

        }
    }
}
$connection->close();
?>