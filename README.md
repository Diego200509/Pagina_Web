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
3. [Construido con 🛠️](#construido-con-)
4. [Instalación 🔧](#instalación-)
5. [Funcionamiento de la pagina ⚙️](#funcionamiento)
6. [Despliegue 📦](#despliegue-)
7. [Autores ✒️](#autores-)
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

### Construido con 🛠️

_Herramientas utilizadas para desarrollar el proyecto:_

* [PHP](https://www.php.net/) - Lenguaje de programación principal.
* [MySQL](https://www.mysql.com/) - Base de datos.
* [Apache](https://httpd.apache.org/) - Servidor web.
* [Bootstrap](https://getbootstrap.com/) - Framework CSS para el diseño.

### Instalación 🔧

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


### Funcionamiento de la pagina ⚙️
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




## Despliegue 📦

_Para desplegar este sistema en un servidor en producción:_

1. Sube los archivos del proyecto al servidor mediante FTP o herramientas de implementación.
2. Configura la base de datos en el entorno de producción.
3. Ajusta las rutas y configuraciones en los archivos PHP según el entorno.


## Autores ✒️

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
