<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmacia</title>
    <style>
        td {
            border: 1px solid black;
            padding: 35px;
        }

        body {
            margin: 10px;
            padding: 0px;
            background-color: wheat;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        a {
            text-decoration: none;
        }


        .boton {
            /* Para tener forma de boton */
            display: inline-block;
            padding: 10px 20px;
            background-color: black;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .image-heading img {
            float: left;
        }

        .image-heading h1 {
            float: left;
            text-decoration: underline;
            margin-left: 0px;
        }
    </style>
</head>

<body>
    <div class="image-heading">
        <img src="../imagenes/aldeire_logo.jpg" width="90px" height="100px">
        <h1>Farmacia de Aldeire</h1>
        <div style="clear: both;"></div> <!-- Para evitar que otros elementos floten alrededor -->
    </div>
    
    <body>