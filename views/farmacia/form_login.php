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
                    <a class="nav-link" href="productos_logout">Productos</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5 d-flex flex-column align-items-center">
        <div class="form-container">
            <form action="login" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" class="form-control" id="email" name="email">
                    <?php if ($errores): ?>
                        <small class="text-danger"><?= $errores['error_email'] ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password">
                    <?php if ($errores): ?>
                        <small class="text-danger"><?= $errores['error_password'] ?></small>
                    <?php endif; ?>
                </div>

                <?php if ($errores): ?>
                    <small class="text-danger"><?= $errores['error_login'] ?></small>
                <?php endif; ?>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-custom" name="login">Login</button>
                    <a class="btn btn-custom" href="registro">Regístrate</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
