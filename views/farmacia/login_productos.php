<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmacia de Aldeire</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
        }

        .image-heading img {
            margin-right: 15px;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid black;
            /* Borde negro para el formulario */
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

        /* Estilo para botones */
        .btn-custom {
            background-color: #add8e6;
            /* Color azul claro */
            color: black;
            border: none;
        }

        .btn-custom:hover {
            background-color: #87ceeb;
            /* Color azul claro más oscuro al pasar el mouse */
        }
    </style>
</head>

<body>
    <!-- NavBar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="logout">
            <img src="../imagenes/aldeire_logo.jpg" alt="Logo">
            Farmacia de Aldeire
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5 d-flex flex-column align-items-center">
        <form action="mostrarProductosCategorias" method="post" class="mt-4">
            <?php foreach ($categorias as $categoria): ?>
                <?php if (!empty($categoria["id"]) && !empty($categoria["nombre"])): ?>
                    <button type="submit" name="id_categoria" value="<?= $categoria["id"]; ?>"
                        class="btn btn-custom boton-custom"><?= $categoria["nombre"]; ?></button>
                <?php endif ?>
            <?php endforeach; ?>
        </form>

        <div class="table-container">
            <?php if (!empty($productos)): ?>
                <table class="table table-bordered table-custom">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $fila): ?>
                            <tr>
                                <td><?= $fila["id"]; ?></td>
                                <td><?= $fila["nombre"]; ?></td>
                                <td><?= $fila["descripcion"]; ?></td>
                                <td><?= $fila["precio"]; ?></td>
                                <td><?= $fila["cantidad"]; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <span class="badge badge-danger">SOLD OUT</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
