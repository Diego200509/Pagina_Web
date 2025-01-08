# Sistema de Elecciones

_Este proyecto es un sistema de gestión de elecciones desarrollado para un solo partido político. Permite a los usuarios realizar sugerencias, votar por un candidato y ver los resultados, además de proporcionar herramientas administrativas para gestionar sugerencias y resultados._

## Funcionalidades

### Para los usuarios
- Ver la lista de candidatos junto con su información personal y académica.
- Conocer las propuestas planteadas por la lista de candidatos.
- Acceder a eventos y noticias relacionadas con el proceso electoral.
- Votar por un candidato de su elección.
- Enviar sugerencias sobre el proceso electoral.

### Para los administradores
- Crear y gestionar candidatos.
- Publicar y administrar propuestas electorales.
- Gestionar eventos y noticias.
- Administrar las sugerencias enviadas por los usuarios.
- Supervisar los resultados de las elecciones en tiempo real.

## Tabla de Contenidos 📑
1. [Comenzando 🚀](#comenzando-)
2. [Pre-requisitos 📋](#pre-requisitos-)
3. [Construido con 🛠](#construido-con-)
4. [Instalación 🔧](#instalación-)
5. [Funcionamiento de la página ⚙️](#funcionamiento-de-la-página-️)
6. [Despliegue 📦](#despliegue-)
7. [Autores 👥](#autores-)
8. [Expresiones de Gratitud 🎁](#expresiones-de-gratitud-)

## Comenzando 🚀

_Estas instrucciones te permitirán obtener una copia del proyecto en funcionamiento en tu máquina local para propósitos de desarrollo y pruebas._

Mira **Despliegue** para conocer cómo desplegar el proyecto.

### Pre-requisitos 📋

_Que cosas necesitas para instalar el software y cómo instalarlas_

- Un servidor web compatible con PHP (por ejemplo, [XAMPP](https://www.apachefriends.org/), [WAMP](https://www.wampserver.com/), o similar).
- MySQL o cualquier base de datos compatible.
- Navegador web para pruebas.
- Editor de texto o IDE (por ejemplo, Visual Studio Code).

## Construido con 🛠

_Herramientas utilizadas para desarrollar el proyecto:_

* [PHP](https://www.php.net/) - Lenguaje de programación principal.
* [MySQL](https://www.mysql.com/) - Base de datos.
* [Apache](https://httpd.apache.org/) - Servidor web.
* [Bootstrap](https://getbootstrap.com/) - Framework CSS para el diseño.

## Instalación 🔧

_Sigue estos pasos para tener el proyecto funcionando en tu entorno local:_

1. **Clona el repositorio** en tu máquina local: git clone https://github.com/Diego200509/Pagina_Web.git


2. **Configura la base de datos**:
- Ajusta las credenciales de conexión en los archivos PHP donde sea necesario, como `config.php`:
  ```php
  $servername = "localhost";
  $username = "root";
  $password = "tu_contraseña";
  $dbname = "elecciones2024";
  ```

3. **Configura el entorno de desarrollo**:
- Asegúrate de que tu servidor web esté apuntando a la carpeta del proyecto.
- Verifica que PHP y MySQL estén activos.

4. **Ejecuta el proyecto**:
- Abre tu navegador y accede a `http://localhost/Pagina_Web/Pagina_Web/Home/inicio.php` para acceder como usuario.
- Abre tu navegador y accede a `http://localhost/Pagina_Web/Pagina_Web/Login/Login.php` para acceder como administrador. 

5. **Realiza pruebas iniciales**:
- Accede como usuario y administrador para validar el funcionamiento.

- Crear una sugerencia como usuario.
- Revisar y aprobar la sugerencia como administrador.
- Verificar que la sugerencia se muestre en la sección correspondiente.


## Funcionamiento de la página ⚙️
### **Inicio - Vista del Usuario**
Al ingresar a la página web, los usuarios son recibidos con una interfaz visual atractiva y organizada. En la imagen proporcionada, se observa la página principal de la plataforma de elecciones, con las siguientes características:

1. **Encabezado y Menú de Navegación**  
   - En la parte superior, se encuentra una barra azul con el logotipo de la lista "MARY CRUZ".
   - También se muestran iconos y enlaces de acceso rápido a las principales secciones del sistema, incluyendo:  
     - **Candidatos:** Información detallada sobre cada candidato, incluyendo su perfil académico y personal.  
     - **Eventos y Noticias:** Publicaciones sobre actividades y novedades relacionadas con la elección.  
     - **Propuestas:** Iniciativas presentadas por la lista electoral para mejorar la institución universitaria.  
     - **Sugerencias:** Sección donde los usuarios pueden enviar sus sugerencias.  
     - **Votos:** Espacio donde los usuarios pueden emitir su voto de manera digital por su candidato favorito.
2. **Sección de Presentación de la Lista Electoral**  
   - Justo debajo del encabezado, se muestra una imagen destacada con los principales miembros de la lista electoral.  
   - En los laterales de la imagen hay flechas de navegación, lo que indica que es un carrusel que puede mostrar más imágenes con información relevante.

![alt text](img/{C0FA1F3C-525D-4A67-92DC-BC4788CDDA9A}.png)

### **Vista Previa de los Candidatos**
En la sección "Conoce a nuestros Candidatos", los usuarios pueden ver una vista previa de los candidatos que forman parte de la lista electoral. Esta sección destaca lo siguiente:

1. **Presentación Visual de los Candidatos**  
   - Se muestran tarjetas con la fotografía de cada candidato, su nombre y el cargo al que aspiran.  

2. **Información Relevante**  
   - Cada candidato tiene su propio recuadro, donde se especifica su rol dentro de la estructura electoral.  
   - Esta información permite a los usuarios conocer de manera rápida a los postulantes y su perfil.

![alt text](img/image.png)

### **Vista Previa de las Propuestas**
En la sección "Propuestas", los usuarios pueden conocer las iniciativas que plantea la lista electoral para la universidad. Esta sección se estructura de la siguiente manera:

1. **Tarjetas con Propuestas Destacadas**  
   - Se presentan tarjetas con el título, descripción y categoría de cada propuesta.
   - Las categorías pueden estar orientadas a distintos ámbitos como "Investigación" o "Vinculación con la Sociedad", etc.

2. **Detalles de cada Propuesta**  
   - Cada tarjeta contiene un breve resumen que explica el objetivo de la propuesta.

3. **Acceso a más Información**  
   - Si el usuario desea conocer más detalles sobre cada propuesta, puede presionar en "ver mas" para que le aparezca la información completa.

   ![alt text](img/Propuestas.png)
   ![alt text](img/ModalPropuestas.png)

### **Vista Previa de Eventos y Noticias**
Dentro de la página de inicio, los usuarios pueden interactuar con la sección "Eventos y Noticias" para visualizar información relevante de manera dinámica.

1. **Interacción con Botones**  
   - La sección cuenta con dos botones principales: "Mostrar Eventos" y "Mostrar Noticias".  
   - Al hacer clic en "Mostrar Eventos", se despliega una lista con los eventos más recientes relacionados con la campaña.  
   - Al hacer clic en "Mostrar Noticias", se muestran las noticias más recientes sobre el proceso electoral.

2. **Visualización de Eventos y Noticias Recientes**  
   - Se presentan tarjetas con información detallada de los cuatro eventos y noticias más recientes.
   - Cada tarjeta incluye:
     - Título del evento o noticia.
     - Breve descripción.
     - Fecha y ubicación (en caso de eventos).

3. **Diseño Interactivo**  
   - La interfaz permite a los usuarios alternar entre eventos y noticias fácilmente, mejorando la experiencia de navegación.

   ![alt text](img/Eventos.png)

   ![alt text](img/Noticias.png)

### **Sección de Candidatos - Vista del Usuario**
En la sección "Candidatos", los usuarios pueden ver información detallada sobre cada postulante que forma parte de la lista electoral.

1. **Información Individual del Candidato**  
   - Se muestra una tarjeta con la información de cada candidato de manera organizada.
   - Incluye:
     - **Fotografía** del candidato.
     - **Nombre completo**, resaltado en negritas.
     - **Edad** del candidato.
     - **Cargo** al que aspira.
     - **Información académica y profesional**, como títulos y especialidades.
     - **Experiencia relevante**, en este caso, el cargo que ha ocupado anteriormente.

2. **Interfaz de Navegación**  
   - A los lados de la tarjeta hay flechas de navegación que permiten al usuario desplazarse entre diferentes candidatos.
   - Esto permite una visualización intuitiva y organizada de la información.

   ![alt text](img/CandidatosUsuario.png)

### **Sección de Eventos y Noticias - Vista del Usuario**
En la sección "Eventos y Noticias", los usuarios pueden mantenerse informados sobre las actividades más relevantes de la comunidad electoral.

#### **Eventos**
1. **Tarjetas Informativas**  
   - Se presentan tarjetas con detalles sobre los eventos organizados por la lista electoral.
   - Cada tarjeta incluye:
     - **Título del evento**.
     - **Imagen representativa** del evento.
     - **Breve descripción** del evento.
     - **Fecha y ubicación** del evento.
     - Un botón "Ver más" para acceder a la descripción completa.

2. **Objetivo de la Sección**  
   - Permite a los usuarios conocer los eventos más recientes y relevantes de la campaña electoral.
   - Facilita la organización de los asistentes y el seguimiento de actividades importantes.

   ![alt text](img/EventosUsuario.png)

#### **Noticias**
1. **Tarjetas con Información Relevante**  
   - Se muestran noticias destacadas sobre la lista electoral y sus iniciativas.
   - Cada tarjeta contiene:
     - **Título de la noticia**.
     - **Imagen destacada**.
     - **Descripción breve**.
     - **Fecha de publicación**.
     - Un botón "Ver más" para acceder a la descripción completa.

   ![alt text](img/NoticiasUsuario.png)

2. **Propósito de la Sección**  
   - Brinda a los usuarios acceso a las noticias más importantes sobre la lista electoral.
   - Mantiene informada a la comunidad sobre propuestas, avances y actividades de la campaña.

### **Sección de Propuestas - Vista del Usuario**
En la sección "Propuestas", los usuarios pueden conocer y explorar las iniciativas planteadas por la lista electoral.

1. **Interfaz de Filtrado**  
   - Se incluye un menú desplegable que permite a los usuarios filtrar las propuestas por facultad o interés.
   - Esto facilita la búsqueda de propuestas relevantes según las necesidades individuales.

2. **Tarjetas de Propuestas**  
   - Se presentan en un formato de tarjetas que muestran:
     - **Título de la propuesta**.
     - **Categoría** a la que pertenece (Investigación, Vinculación con la Sociedad, etc.).
     - **Breve descripción** del contenido y objetivo de la propuesta.
     - Un botón "Ver más" para acceder a la información completa de cada propuesta.
     
   ![alt text](img/PropuestasUsuario.png)

### **Sección de Sugerencias - Vista del Usuario**
En la sección "Sugerencias", los usuarios pueden enviar sus recomendaciones sobre la gestión de la lista electoral.

1. **Formulario de Sugerencias**  
   - Los usuarios pueden ingresar su nombre, correo electrónico (opcional) y su sugerencia en un campo de texto.
   - Se incluye un botón de "Enviar Sugerencia" para registrar la opinión.

2. **Confirmación de Envío**  
   - Una vez enviada la sugerencia, aparece una ventana emergente confirmando el envío exitoso.
   - Se muestra un mensaje de agradecimiento por la colaboración.
   
   ![alt text](img/{F48C2D8F-0E2A-46DE-BE03-631602324C8B}.png)

   ![alt text](img/{BF5A69D9-0C64-406B-AB42-DFFF560FC609}.png)

### **Sección de Votaciones - Vista del Usuario**
En la sección "Votos", los usuarios pueden participar activamente en la elección seleccionando a su candidato preferido.

1. **Interfaz de Selección de Candidato**  
   - Se presentan los candidatos disponibles con sus imágenes, nombres y lemas de campaña.
   - Los usuarios pueden marcar su candidato preferido seleccionando una opción de radio.
   - Se incluye un botón "Votar" para confirmar la elección.

2. **Proceso de Votación**  
   - Una vez que el usuario selecciona su candidato y presiona "Votar", el sistema registra el voto en la base de datos.

3. **Visualización de Resultados en Tiempo Real**  
   - Después de emitir el voto, los usuarios pueden ver los resultados actualizados de la elección.
   - Se presentan las estadísticas con el porcentaje de votos obtenidos por cada candidato.
   - Se muestra el número total de votos registrados hasta el momento.
   - Los resultados se actualizan dinámicamente conforme más usuarios emiten su voto.
   
   ![alt text](img/{7770085C-CECF-44B7-9E78-1F9380000BA5}.png)

   ![alt text](img/{6FD5CB1E-ECA9-4946-B015-028E33A41077}.png)

### **Login - Vista de Administración**
Para acceder a la vista de administración, el sistema cuenta con un proceso de autenticación.

1. **Acceso Restringido**  
   - Solo los administradores y el superadmin pueden acceder al panel de administración.
   - El sistema ya tiene un superadmin predefinido en la base de datos, quien es el primer usuario en iniciar sesión.

2. **Interfaz de Inicio de Sesión**  
   - Se muestra un formulario donde los administradores deben ingresar su correo electrónico y contraseña.
   - Un botón "Iniciar sesión" permite acceder al sistema tras la validación de credenciales.

3. **Validación de Credenciales**  
   - El sistema verifica las credenciales ingresadas con las almacenadas en la base de datos.
   - Si las credenciales son correctas, el usuario es redirigido al panel de administración.
   - En caso de error, se muestra un mensaje indicando que los datos son incorrectos.

   ![alt text](img/{7E6E6370-27DA-4AD5-B09F-E8A72710F595}.png)

### **Sección de Administración - Vista del Administración**
La sección de administración está diseñada para que el superadmin tenga el control total sobre la gestión del sistema.

#### **Creación de Administradores**
1. **Acceso Exclusivo del Superadmin**  
   - Solo el superadmin tiene la capacidad de crear nuevos administradores dentro del sistema.
   - Los administradores creados pueden gestionar diversas funciones, pero no tienen acceso a la creación de otros administradores.

2. **Formulario de Creación de Administrador**  
   - Se requiere ingresar los siguientes datos:
     - **Nombre** del administrador.
     - **Correo electrónico** único para el acceso.
     - **Contraseña**, la cual debe tener un mínimo de 6 caracteres.
   - Se incluye un botón "Crear Admin" que registra el nuevo usuario en la base de datos.

3. **Validación de Datos**  
   - La contraseña ingresada debe cumplir con el requisito de al menos 6 caracteres para garantizar la seguridad.
   - Se verifica que el correo electrónico no esté duplicado en el sistema.

   ![alt text](img/{566F0590-6141-4A2B-AACE-2A144DD76088}.png)

   ![alt text](img/{FADD8B5F-AB21-465A-9E59-BC565816A5FB}.png)

### **Personalización del Sistema - Cambio de Colores - Vista del Administración**
El sistema permite personalizar la apariencia tanto de la vista de usuario como de la vista de administración mediante una herramienta de configuración de colores.

1. **Opciones de Configuración**  
   - **Colores Generales:** Modifican los colores del navbar, el footer y algunos elementos de las páginas en la vista de usuario. También se aplican a la interfaz de administración.
   - **Secciones:** Modifican el color de fondo de estas secciones únicamente en la página de inicio de la vista de usuario.
     - **Sección Candidatos**
     - **Sección Propuestas**
     - **Sección Eventos y Noticias**
   - **Páginas:** Cambian el color de fondo de las páginas completas, tanto en la vista de usuario como en la administración.
     - **Página Candidatos**
     - **Página Eventos y Noticias**
     - **Página Propuestas**
     - **Página Sugerencias**
   - **Página de Login:** Permite modificar el degradado de fondo en la pantalla de inicio de sesión.

2. **Aplicación de los Cambios**  
   - Cada sección cuenta con un selector de color para elegir la tonalidad deseada.
   - Se incluyen botones de **Aceptar** para guardar los cambios y **Restablecer** para volver a los valores predeterminados.
   - Una vez que se selecciona un color y se presiona "Aceptar", la configuración se guarda y se aplica automáticamente en la interfaz.
   - Si el usuario desea revertir los cambios, puede usar el botón "Restablecer" para volver a los colores originales.

   ![alt text](img/{5B3A40AA-26C7-4A80-A896-F13CFAD82B13}.png)

   ![alt text](img/{8AD04B6F-9983-4ED3-AD90-A35459955564}.png)

   ![alt text](img/{BBB1816A-5735-4CA3-BD5A-7026B7577659}.png)

### **Personalización del Sistema - Cambio de Imágenes - Vista del Administración**
El sistema permite modificar imágenes clave dentro de la plataforma, tanto en la vista de usuario como en la administración.

1. **Cambio del Logo en el Navbar**  
   - Se puede actualizar el logo que aparece en la barra de navegación (**navbar**) de la vista de usuario y la vista de administración.
   - Para ello, se debe seleccionar un archivo de imagen y presionar el botón **Aceptar** para aplicarlo.
   - Existe un botón **Restablecer** que permite volver a la imagen predeterminada.

2. **Cambio de Imágenes en el Slider del Inicio**  
   - El sistema permite modificar las imágenes del **carrusel** (slider) que se muestra en la página de inicio de los usuarios.
   - Cada imagen del slider puede actualizarse individualmente seleccionando un archivo y presionando **Aceptar**.
   - También existe la opción de **Restablecer** cada imagen a su versión original en caso de ser necesario.

3. **Aplicación de los Cambios**  
   - Una vez seleccionadas y confirmadas las imágenes, los cambios se reflejan de inmediato en la interfaz al actualizar la pagina.

   ![alt text](img/{AF2528B4-521B-4672-847D-130D4ADD4535}.png)

   ![alt text](img/{17986878-FF6F-4CDE-B3BB-B7CEB37318EE}.png)

   ![alt text](img/{21AB48A9-C478-4C37-A462-BD8BA53A5618}.png)

### **Gestión de Candidatos - Vista de Administración**
Esta sección permite a los administradores gestionar los candidatos que forman parte del proceso electoral.

1. **Listado de Candidatos**  
   - Se muestra una tabla con información clave de cada candidato, incluyendo:
     - **ID:** Número de identificación en la base de datos.
     - **Nombre y Apellido:** Información personal del candidato.
     - **Fecha de Nacimiento:** Datos de identificación.
     - **Cargo:** Posición que ocupa en la elección.
     - **Educación:** Información académica del candidato.
     - **Experiencia:** Trayectoria profesional y experiencia relevante.
     - **Estado:** Indica si el candidato está **activo** o **inactivo** en la elección.
     - **Imagen:** Fotografía representativa del candidato.
     - **Acciones:** Opciones para administrar la información del candidato.

2. **Acciones Disponibles**  
   - **Editar:** Permite modificar los datos del candidato, como nombre, cargo, educación y experiencia.
   - **Eliminar:** Opción para remover un candidato del sistema.
   - **Activar/Inactivar:** Permite cambiar el estado del candidato, haciendo que aparezca o desaparezca de la lista pública en la vista de usuario.

3. **Agregar un Nuevo Candidato**  
   - Se incluye un botón **"Agregar Candidato"** que permite registrar nuevos participantes en la elección.
   - El formulario de creación solicita información relevante como:
     - Nombre completo
     - Cargo al que postula
     - Formación académica
     - Experiencia laboral
     - Imagen representativa

   ![alt text](img/{F3AD1984-5C63-4899-B05B-7C4C7A48B087}.png)

   ![alt text](img/{8F56D9F1-B2F9-4476-ADEB-979FFF6D5D06}.png)

### **Gestión de Eventos y Noticias - Vista de Administración**
Esta sección permite a los administradores gestionar los eventos y noticias publicadas en el sistema.

1. **Listado de Eventos y Noticias**  
   - Se muestra una tabla con información clave de cada evento o noticia, incluyendo:
     - **ID:** Número de identificación en la base de datos.
     - **Título:** Nombre del evento o noticia.
     - **Descripción:** Resumen del contenido.
     - **Fecha:** Día en que se llevará a cabo o se publicó.
     - **Tipo:** Define si es un **evento** o una **noticia**.
     - **Ubicación:** Lugar donde se realizará el evento (solo aplicable a eventos, no a noticias).
     - **Partido:** Asociación con la lista electoral.
     - **Estado:** Indica si está **activo** o en estado **oculto**.
     - **Imagen:** Representación visual del evento o noticia.
     - **Acciones:** Opciones de gestión.

2. **Acciones Disponibles**  
   - **Editar:** Permite modificar la información del evento o noticia.
   - **Eliminar:** Opción para remover un evento o noticia del sistema.
   - **Ocultar/Activar:** Cambia el estado de visibilidad del evento o noticia en la vista de usuario.

3. **Restricciones y Validaciones**  
   - **Fechas Controladas:** No se permite ingresar una noticia con una fecha posterior a la actual. Las noticias deben corresponder a hechos ya ocurridos.
   - **Eventos Posteriores:** Solo se pueden registrar eventos con fechas futuras, asegurando que no se creen eventos con fechas pasadas.
   - **Ubicación para Eventos:** Es obligatorio que los eventos tengan una ubicación definida, mientras que las noticias no requieren este campo.

4. **Agregar un Nuevo Evento o Noticia**  
   - Se incluye un botón **"Agregar Evento/Noticia"** que permite registrar nuevas publicaciones.
   - El formulario de creación solicita información relevante como:
     - Título del evento o noticia.
     - Tipo (Evento o Noticia).
     - Fecha del evento o publicación.
     - Ubicación (para eventos, obligatorio).
     - Descripción detallada.
     - Imagen representativa.
     - Estado (activo u oculto).
   
   ![alt text](img/{4288F022-32F8-4C6C-8BF0-5A456FD6A0AC}.png)

   ![alt text](img/{22467AF9-AEB1-4486-BE15-11D676F262F9}.png)

### **Gestión de Propuestas - Vista de Administración**
Esta sección permite a los administradores gestionar las propuestas presentadas por la lista electoral.

1. **Listado de Propuestas**  
   - Se muestra una tabla con información clave de cada propuesta, incluyendo:
     - **ID:** Número de identificación en la base de datos.
     - **Partido Político:** Nombre de la lista electoral asociada a la propuesta.
     - **Título:** Nombre de la propuesta.
     - **Descripción:** Resumen del contenido de la propuesta.
     - **Categorías:** Clasificación de la propuesta (por ejemplo, investigación, vinculación con la sociedad, etc.).
     - **Estado:** Indica si está **visible** u **oculta** en la vista de usuario.
     - **Imagen:** Representación visual de la propuesta.
     - **Favorito:** Opción para marcar propuestas destacadas con un icono de estrella.
     - **Acciones:** Opciones de gestión.

2. **Acciones Disponibles**  
   - **Editar:** Permite modificar la información de la propuesta.
   - **Eliminar:** Opción para remover una propuesta del sistema.
   - **Ocultar/Activar:** Permite cambiar la visibilidad de la propuesta en la vista de usuario.
   - **Marcar como Favorita:** Posibilidad de destacar una propuesta importante mediante un icono de estrella.

3. **Agregar una Nueva Propuesta**  
   - Se incluye un botón **"Agregar Propuesta"** que permite registrar nuevas iniciativas.
   - El formulario de creación solicita información relevante como:
     - Título de la propuesta.
     - Descripción detallada.
     - Categoría de la propuesta.
     - Imagen representativa.
     - Estado (visible u oculto).

   ![alt text](img/PropuestasAdmin.png)

   ![alt text](img/{82F7174C-D60F-43F0-9FDF-3281EF72282E}.png)

### **Gestión de Sugerencias - Vista de Administración**
Esta sección permite a los administradores gestionar las sugerencias enviadas por los usuarios.

1. **Listado de Sugerencias**  
   - Se muestra una tabla con información clave de cada sugerencia, incluyendo:
     - **Usuario:** Nombre de quien envió la sugerencia.
     - **Correo:** Dirección de correo electrónico del usuario (opcional).
     - **Candidato:** Si la sugerencia está dirigida a un candidato específico.
     - **Sugerencia:** Contenido de la recomendación.
     - **Estado:** Puede ser **Pendiente** o **Revisado**.
     - **Fecha:** Momento en que se envió la sugerencia.
     - **Acciones:** Opciones de gestión.

2. **Estados de las Sugerencias**  
   - **Pendiente:** Estado inicial de una sugerencia cuando es enviada por un usuario.
   - **Revisado:** Una vez que el administrador la revisa, cambia a este estado. **No es posible volver al estado Pendiente una vez revisada**.

3. **Acciones Disponibles**  
   - **Marcar como Revisado:** Permite cambiar el estado de la sugerencia de **Pendiente** a **Revisado**.
   - **Eliminar:** Remueve la sugerencia de la vista de administración, pero se mantiene en la base de datos para auditoría y seguimiento histórico.
   - **Actualizar Imagen:** Opción que permite modificar la imagen de la sugerencia en la vista de usuario, asegurando que la presentación sea adecuada y actualizada.

   ![alt text](img/{0DCD4F60-BE03-4F42-B402-964691E140DD}.png)

   ![alt text](img/{4710F9FF-9023-4E0A-A55A-6E2EBB3CAAC4}.png)

   ![alt text](img/{B7FE745E-D99A-4A4C-91B9-4011F7702DB5}.png)


### **Gestión de Votos - Panel de Administración**
Esta sección permite a los administradores visualizar y gestionar los resultados de las elecciones en tiempo real.

1. **Visualización de los Votos**  
   - Se presentan los resultados de la votación en dos formatos:
     - **Vista Individual:** Se muestra el porcentaje y número de votos obtenidos por cada candidato con su respectiva imagen.
     - **Gráfico de Distribución:** Representación visual en forma de gráfico de pastel para analizar la proporción de votos entre los candidatos.

2. **Persistencia de los Votos**  
   - El sistema de votación utiliza **cookies del navegador** para restringir el voto único por usuario.  
   - Sin embargo, si un usuario elimina las cookies de su navegador, podrá votar nuevamente.
   - A pesar de ello, los votos **siempre quedan registrados en la base de datos**, garantizando la integridad de los resultados finales.

   ![alt text](img/{791CEF6D-6E58-454B-938A-34069D0640E1}.png)

### **Administración de Imágenes en Votos**
Esta función permite a los administradores modificar las imágenes que se muestran en la sección de votaciones y resultados.

1. **Actualización de Imágenes**
   - Los administradores pueden cambiar las imágenes de los candidatos en la vista de votación y resultados.
   - Se permite la modificación del fondo de la sección de votación para una mejor personalización.

2. **Interfaz de Administración**
   - La sección muestra las imágenes actuales de los candidatos y el fondo.
   - Los administradores pueden seleccionar un nuevo archivo de imagen y elegir la posición a reemplazar (**Candidato 1, Candidato 2 o Fondo , etc.**).
   - Se proporciona un botón de actualización para aplicar los cambios.

   ![alt text](img/{2E8018BB-8B80-414C-BED4-B8D96ABAEFA1}.png)

   ![alt text](img/{E46ACF9E-BD58-4AD5-B90C-DA9A841AA0A6}.png)

## Despliegue 📦

_Para desplegar este sistema en un servidor en producción:_

1. Sube los archivos del proyecto al servidor mediante FTP o herramientas de implementación.
2. Configura la base de datos en el entorno de producción.
3. Ajusta las rutas y configuraciones en los archivos PHP según el entorno.


## Autores 👥

* **Sebastián Ortiz** - *Desarrollo* - [SebastianOrtiz2004](https://github.com/SebastianOrtiz2004/SebastianOrtiz)
* **Diego Jijón** - *Desarrollo y Documentación* - [Diego200509](https://github.com/Diego200509)
* **Elkin López** - *Desarrollo* - [Elkinnn](https://github.com/Elkinnn)
* **Leonel Barros** - *Desarrollo* - [Leo538](https://github.com/Leo538)
* **T1Angel4220** - *Desarrollo* - [T1Angel4220](https://github.com/T1Angel4220)

## Expresiones de Gratitud 🎁

* Comenta a otros sobre este proyecto 📢.
* Invita una cerveza 🍺 o un café ☕ al equipo.
* Da las gracias públicamente 🤓.
* etc.

---
