<?php
namespace Controllers;

use Models\Usuario;
use Lib\Pages;
use Lib\Validador;
use Lib\Email;
use Models\Producto;
use Models\Categoria;

class UsuarioController
{

  private Pages $pages;
  private Usuario $usuario;
  private Producto $producto;
  private Categoria $categoria;

  function __construct()
  {
    $this->usuario = new Usuario();
    $this->producto = new Producto();
    $this->pages = new Pages();
    $this->categoria = new Categoria();
  }

  // Metodo que saldra por defecto
  public function inicio()
  {
    $errores = [];
    $productos = $this->producto->getAll();
    $categorias = $this->categoria->getAll();
    $this->pages->render("farmacia/form_login", ["errores" => $errores, "productos" => $productos, 'categorias' => $categorias]);
  }

  public function login()
  {
    $errores = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // print_r($_POST);

      // Valida los siguientes datos
      $email = filter_var($_POST["email"], FILTER_SANITIZE_STRING);
      $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

      // Deben estar estos campos completos
      if (!empty($email) && !empty($password)) {

        $resultado = $this->usuario->login($email, $password);

        if ($resultado) {
          session_start();
          $_SESSION['idUsuario'] = $resultado['id'];
          $_SESSION['rolUsuario'] = $resultado['rol'];

          $productos = $this->producto->getAllPaginate();
          $total_productos = count($productos);
          $this->pages->render("farmacia/home", ["productos" => $productos, "total_productos" => $total_productos]);
        } else {
          $errores['error_email'] = '';
          $errores['error_password'] = '';
          $errores['error_login'] = 'El usuario o contraseña son incorrectos';
          $productos = $this->producto->getAll();
          $categorias = $this->categoria->getAll();
          $this->pages->render("farmacia/form_login", ["errores" => $errores, "productos" => $productos, 'categorias' => $categorias]);

        }
      } else {

        // Si no es la validacion correcta mandara mensajes en cada error
        $errores['error_email'] = Validador::es_obligatorio($email, "email");
        $errores['error_password'] = Validador::es_obligatorio($password, "password");
        $errores['error_login'] = '';
        $productos = $this->producto->getAll();
        $categorias = $this->categoria->getAll();
        $this->pages->render("farmacia/form_login", ["errores" => $errores, "productos" => $productos, 'categorias' => $categorias]);

      }
    } else {
      $productos = $this->producto->getAll();
      $categorias = $this->categoria->getAll();
      $this->pages->render("farmacia/form_login", ["errores" => $errores, "productos" => $productos, 'categorias' => $categorias]);

    }
  }
  public function mostrarRegistro()
  {
    $errores = [];
    $this->pages->render("farmacia/form_register", ["errores" => $errores]);

  }
  public function registrar()
  {
    $errores = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // print_r($_POST);

      // Valida los siguientes datos
      $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
      $apellidos = filter_var($_POST["apellidos"], FILTER_SANITIZE_STRING);
      $email = filter_var($_POST["email"], FILTER_SANITIZE_STRING);
      $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

      // Deben estar estos campos completos
      if (!empty($nombre) && !empty($apellidos) && !empty($email) && !empty($password)) {

        $rol = 'usuario';

        $resultado = $this->usuario->registro($nombre, $apellidos, $email, $password, $rol);
        // if($email = $this->usuario->existeEmail($email)){
        //   echo "El email ya ha sido utilizado,vuelve atras y pon uno correcto";
        // }else{
        if ($resultado) {
          //MANDAR CORREO
          Email::send('Registro', 'Gracias por registrarse en nuestra farmacia', $email, $nombre);
          $this->inicio();
        }
        echo '<span class="badge badge-danger">Usuario Registrado</span>';
        // }
      } else {
        // Si no es la validacion correcta mandara mensajes en cada error
        $errores['error_nombre'] = Validador::es_obligatorio($nombre, "nombre");
        $errores['error_apellidos'] = Validador::es_obligatorio($apellidos, "apellidos");
        $errores['error_email'] = Validador::es_obligatorio($email, "email");
        $errores['error_password'] = Validador::es_obligatorio($password, "password");

        $this->pages->render("farmacia/form_register", ["errores" => $errores]);

      }
    } else {
      $this->pages->render("farmacia/form_register", ["errores" => $errores]);

    }
  }

  // Metodo para mostrar la tabla de Usuarios
  public function mostrarUsuarios()
  {
    $usuarios = $this->usuario->getAll();
    $this->pages->render("farmacia/usuarios", ["usuarios" => $usuarios]);
  }

  //Metodo para borrar un usuario
  public function borrarUsuario()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!empty($_POST['id_usuario'])) {
        $id = $_POST['id_usuario'];

        $this->usuario->eliminar($id);

      } else {
        echo "No se puede borrar el Usuario";
      }
    }
    $this->mostrarUsuarios();
  }

  public function mostrarEditarUsuario()
  {
    $errores = [];
    $usuario = $this->usuario->getUsuarioById($_POST['id_usuario']);
    $this->pages->render("farmacia/form_editarUsuario", ["errores" => $errores, "usuario" => $usuario]);
  }

  // Metodo para editar un Usuario
  public function editarUsuario()
  {
    // DEVUELVE UNA VISTA NUEVA CON UN FORMULARIO Y COMO TIENES EL ID EN EL POST $_POST['id_usuario'] A LA VISTA
    // LE DEVUELVES LOS CAMPOS DEL USUARIO Y LOS PINTAS EN EL VALUE DE LOS INPUT DEL FORM.

    $errores = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // print_r($_POST);

      // Valida los siguientes datos
      $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
      $apellidos = filter_var($_POST["apellidos"], FILTER_SANITIZE_STRING);
      $email = filter_var($_POST["email"], FILTER_SANITIZE_STRING);
      $id = $_POST['id_usuario'];
      $rol = $_POST['rol'];

      // No incluir la contraseña en la actualización si no se proporciona
      $password = !empty($_POST['password']) ? filter_var($_POST["password"], FILTER_SANITIZE_STRING) : null;

      $resultado = $this->usuario->editarUsuario($id, $nombre, $apellidos, $email, $rol, $password);

      if ($resultado === true) {
        $this->mostrarUsuarios();
      } else {
        // En caso de error, puedes depurar con:
        // echo 'Error: ' . $resultado;
        $errores['error_general'] = 'No se pudo actualizar el usuario. ' . $resultado;
        $usuario = $this->usuario->getUsuarioById($id);
        $this->pages->render("farmacia/form_editarUsuario", ["errores" => $errores, "usuario" => $usuario]);
      }
    } else {
      $this->pages->render("farmacia/form_editarUsuario", ["errores" => $errores]);
    }
  }

  public function mostrarCrearUsuario()
  {
    $errores = [];
    $this->pages->render("farmacia/form_insertarUsuario", ["errores" => $errores]);

  }
  // Funcion para crear un nuevo Usuario
  public function crearUsuario()
  {
    $errores = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // print_r($_POST);

      // Valida los siguientes datos
      $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
      $apellidos = filter_var($_POST["apellidos"], FILTER_SANITIZE_STRING);
      $email = filter_var($_POST["email"], FILTER_SANITIZE_STRING);
      $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);

      // Deben estar estos campos completos
      if (!empty($nombre) && !empty($apellidos) && !empty($email) && !empty($password)) {

        $rol = 'usuario';

        $resultado = $this->usuario->registro($nombre, $apellidos, $email, $password, $rol);
        if ($resultado) {
          $this->mostrarUsuarios();
        }
      } else {
        // Si no es la validacion correcta mandara mensajes en cada error
        $errores['error_nombre'] = Validador::es_obligatorio($nombre, "nombre");
        $errores['error_apellidos'] = Validador::es_obligatorio($apellidos, "apellidos");
        $errores['error_email'] = Validador::es_obligatorio($email, "email");
        $errores['error_password'] = Validador::es_obligatorio($password, "password");

        $this->pages->render("farmacia/form_insertarUsuario", ["errores" => $errores]);

      }
    } else {
      $this->pages->render("farmacia/form_insertarUsuario", ["errores" => $errores]);

    }
  }

  // 
  public function logout()
  {
    session_start();

    session_destroy();
    $this->inicio();
  }

  public function mostrarProductosLogout()
  {
    $productos = $this->producto->getAll();
    $categorias = $this->categoria->getAll();
    $this->pages->render("farmacia/login_productos", ["productos" => $productos, 'categorias' => $categorias]);
  }

}
?>