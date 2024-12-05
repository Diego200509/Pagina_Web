<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Elegante</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHq6Wxz4I8j7KcbM5w5ho2HDhqU2Oq6IjIXfz8V8KmHUz5IJ4/h8j5jwD2hTA4j+7WipQABg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    /* Estilo del cuerpo */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #4A90E2, #9013FE);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: white;
    }

    /* Contenedor principal */
    .login-container {
      background: white;
      border-radius: 20px;
      width: 350px;
      padding: 30px;
      box-shadow: 0px 20px 50px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    /* Encabezado */
    .login-container h2 {
      font-size: 28px;
      margin-bottom: 20px;
      color: #4A90E2;
      font-weight: 600;
    }

    /* Avatares */
    .avatars {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 20px;
    }

    .avatars img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      border: 3px solid #4A90E2;
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
      object-fit: cover;
      transition: transform 0.3s ease, border-color 0.3s ease;
    }

    .avatars img:hover {
      transform: scale(1.1);
      border-color: #9013FE;
    }

    /* Inputs */
    .input-group {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      background: #F4F4F4;
      border-radius: 30px;
      padding: 10px;
      box-shadow: inset 0px 2px 5px rgba(0, 0, 0, 0.1);
      transition: box-shadow 0.3s ease, transform 0.3s ease;
    }

    .input-group i {
      color: #4A90E2;
      margin-right: 10px;
      font-size: 20px;
    }

    .input-group input {
      border: none;
      outline: none;
      width: 100%;
      background: none;
      font-size: 16px;
      color: #333;
      padding: 10px;
      border-radius: 15px;
    }

    .input-group input::placeholder {
      color: #bbb;
    }

    .input-group:hover {
      box-shadow: inset 0px 2px 10px rgba(0, 0, 0, 0.15);
      transform: translateY(-2px);
    }

    .input-group input:focus {
      border: 2px solid #9013FE;
    }

    /* Botón de inicio */
    .login-button {
      width: 100%;
      background: #4A90E2;
      color: white;
      border: none;
      border-radius: 25px;
      padding: 12px 0;
      font-size: 16px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s ease, transform 0.3s ease;
    }

    .login-button:hover {
      background: #9013FE;
      transform: translateY(-3px);
    }

    /* Enlace de registro */
    .register-link {
      display: block;
      margin-top: 20px;
      font-size: 14px;
      color: #4A90E2;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .register-link:hover {
      color: #9013FE;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Bienvenido</h2>
    <!-- Avatares -->
    <div class="avatars">
      <img src="https://cdn.icon-icons.com/icons2/2643/PNG/512/female_woman_avatar_people_person_white_tone_icon_159370.png" alt="Avatar Mujer">
      <img src="https://img.freepik.com/vector-premium/perfil-avatar-hombre-icono-redondo_24640-14044.jpg" alt="Avatar Hombre">
    </div>

    <!-- Inputs -->
    <form>
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" placeholder="Usuario" required>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" placeholder="Contraseña" required>
      </div>

      <!-- Botón -->
      <button type="submit" class="login-button">Iniciar sesión</button>
    </form>
  </div>
</body>
</html>