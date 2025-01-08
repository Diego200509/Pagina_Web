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

![alt text]({C0FA1F3C-525D-4A67-92DC-BC4788CDDA9A}.png)

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
