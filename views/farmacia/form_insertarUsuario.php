<?php
session_start();
if (!isset($_SESSION['rolUsuario']) || $_SESSION['rolUsuario'] === null) {
  // Redireccionar al usuario a la página de login si no está logueado
  header('Location: login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Farmacia de Aldeire</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    body {
      background-color: white;
      padding-top: 100px;
      /* Espacio para el menú de navegación */
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .boton {
      display: inline-block;
      padding: 10px 20px;
      background-color: black;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      margin: 5px;
    }

    .form-container {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
    }

    .navbar-brand img {
      max-height: 50px;
      margin-right: 10px;
    }

    .navbar-nav .nav-item .nav-link {
      color: white !important;
    }

    /* Color más oscuro al pasar el ratón */
    .navbar-nav .nav-item .nav-link:hover {
      color: #bfbfbf !important;
    }

    .table-container {
      margin-top: 20px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      margin-top: 20px;
    }

    th,
    td {
      border: 1px solid black;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }

    .sold-out-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 200px;
    }

    .sold-out-container img {
      max-width: 100%;
      max-height: 100%;
    }
  </style>
</head>

<body>
  <!-- Menú de navegación -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="inicio">
      <img src="../imagenes/aldeire_logo.jpg" width="40" height="40" alt="Logo">
      Farmacia de Aldeire
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="inicio">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="productos">Productos</a>
        </li>
        <?php if ($_SESSION['rolUsuario'] == 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="categorias">Categorías</a>
          </li>
        <?php endif; ?>
        <?php if ($_SESSION['rolUsuario'] == 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="usuarios">Control de usuarios</a>
          </li>
        <?php endif; ?>
        <?php if ($_SESSION['rolUsuario'] == 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="form_insertarUsuario">Añadir Usuario</a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="carrito">
            <i class="fas fa-shopping-cart"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container">

    <h2>Insertar nuevo Usuario</h2>
    <form action="insertarUsuario" method="post">
      <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
        <?php if (isset($errores['error_nombre'])): ?>
          <small class="form-text text-danger"><?= $errores['error_nombre'] ?></small>
        <?php endif; ?>
      </div>
      <div class="form-group">
        <label for="apellidos">Apellidos:</label>
        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
        <?php if (isset($errores['error_apellidos'])): ?>
          <small class="form-text text-danger"><?= $errores['error_apellidos'] ?></small>
        <?php endif; ?>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
        <?php if (isset($errores['error_email'])): ?>
          <small class="form-text text-danger"><?= $errores['error_email'] ?></small>
        <?php endif; ?>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" class="form-control" id="password" name="password" required>
        <?php if (isset($errores['error_password'])): ?>
          <small class="form-text text-danger"><?= $errores['error_password'] ?></small>
        <?php endif; ?>
      </div>
      <button type="submit" class="btn btn-primary">Insertar nuevo Usuario</button>
    </form>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>