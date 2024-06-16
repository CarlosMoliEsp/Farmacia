<?php

namespace Controllers;

class FrontController {

    public static function main() {

        function show_error() {
            $error = new errorController();  //usamos este controlador para mostrar los errores
            $error->index();
        }

        if (isset($_GET['controller'])) {
            $nombre_controlador = 'Controllers\\'.$_GET['controller'].'Controller';

        } elseif(!isset($_GET['controller']) && !isset($_GET['action'])) {
            $nombre_controlador = controller_default; // se establece un archivo de parámetros

        } else {
            show_error();
            exit();
        }


        // si todo va bein creamos una instacia del controlador y llamanos a la acción
        if(class_exists($nombre_controlador)) {
            $controlador = new $nombre_controlador();

            if(isset($_GET['action']) && method_exists($controlador, $_GET['action'])) {
                $action = $_GET['action'];
                $controlador->$action();
            } elseif(!isset($_GET['controller']) && !isset($_GET['action'])) {
                $action_default = action_default;
                $controlador->$action_default();
            } else {
                show_error();
            }



        } else {
            show_error();
        }

    }

}






?>