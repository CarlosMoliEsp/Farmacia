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
      padding-top: 70px;
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

    .card {
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

    .carousel-container {
      margin-top: 2px;
      /* Ajusta el margen superior del carrusel para alinear con el nav */
    }

    .carousel {
      max-width: 100%;
      /* Limita el ancho máximo del carrusel */
      overflow: hidden;
      /* Oculta cualquier contenido que se desborde del carrusel */
    }

    .carousel-item img {
      max-width: 100%;
      /* Establece un tamaño máximo para el ancho de la imagen */
      max-height: 300px;
      /* Limita la altura máxima de la imagen */
      width: 100%;
      /* Ajusta el ancho de la imagen al 100% del contenedor */
      height: auto;
      /* Ajusta la altura de la imagen automáticamente para mantener la proporción */
    }

    /* Espacio entre el carrusel y los productos */
    .productos-container {
      margin-top: 30px; /* Ajusta según sea necesario */
    }

    @media (max-width: 767px) {
      #carouselExampleIndicators {
        display: block;
        /* Oculta el carrusel en dispositivos móviles */
      }
    }

    /* Mostrar el carrusel en dispositivos más grandes */
    @media (min-width: 768px) {
      #carouselExampleIndicators {
        display: block;
        /* Muestra el carrusel en pantallas más grandes */
      }
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
        <li class="nav-item">
          <a class="nav-link" href="categorias">Categorías</a>
        </li>
        <?php if ($_SESSION['rolUsuario'] == 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="usuarios">Control de usuarios</a>
          </li>
        <?php endif; ?>
        <?php if ($_SESSION['rolUsuario'] == 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="form_insertarProducto">Añadir Producto</a>
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

  <div class="container carousel-container">
    <!-- Tabla del Listado de Productos -->
    <div class="container productos-container">
      <div class="row">
        <?php foreach ($productos as $producto): ?>
          <div class="col-md-3 mb-4">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($producto['nombre']); ?></h5>
                <p class="card-text"><?= htmlspecialchars($producto['descripcion']); ?></p>
                <p class="card-text"><strong><?= htmlspecialchars($producto['precio']); ?>€</strong></p>
                <p class="card-text">Categoría: <?= htmlspecialchars($producto['nombre_categoria']); ?></p>
                <p class="card-text">Cantidad: <?= htmlspecialchars($producto['cantidad']); ?></p>
                <?php if (!empty($producto["cantidad"])): ?>
                  <form action="comprar" method="post">
                    <input type="hidden" name="id_producto" value="<?= $producto['id']; ?>">
                    <button type="submit" class="btn btn-success btn-block">Añadir al carrito</button>
                  </form>
                <?php else: ?>
                  <div class="mt-3 text-center">
                    <span class="badge badge-danger" style="font-size: 14px; font-weight: bold;">SOLD OUT</span>
                  </div>
                <?php endif; ?>
                <!-- Botones de editar y borrar -->
                <div class="mt-3">
                  <?php if ($_SESSION['rolUsuario'] == 'admin'): ?>
                    <form action="mostrarEditarProducto" method="post" class="mb-2">
                      <button type="submit" class="btn btn-warning btn-sm btn-block" name="id_producto"
                        value="<?= $producto["id"]; ?>">Editar</button>
                    </form>
                  <?php endif; ?>
                  <?php if ($_SESSION['rolUsuario'] == 'admin'): ?>
                    <form action="borrarProducto" method="post" class="mb-2">
                      <button type="submit" class="btn btn-danger btn-sm btn-block" name="id_producto"
                        value="<?= $producto["id"]; ?>">Borrar</button>
                    </form>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <!-- Paginación -->
    <nav>
      <ul class="pagination">
        <?php for ($i = 1; $i <= $total_productos; $i++): ?>
          <li class="page-item">
            <form action="pagina2" method="post" class="d-inline"><button type="submit" class="page-link" name="page"
                value="<?= $i ?>"><?= $i ?></button></form>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>