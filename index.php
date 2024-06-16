<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmacia</title>
</head>

<body>
    <?php
    // require_once "autoload.php";
    require __DIR__ .'/../vendor/Controllers';
    require_once "./config/config.php";
    require_once "views/layout/header.php";

    use Controllers\FrontController;

    FrontController::main();

    require_once "views/layout/footer.php";
    ?>
</body>

</html>