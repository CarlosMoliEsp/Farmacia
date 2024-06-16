<?php
namespace Controllers;

use Models\Producto;
use Models\Pedido;
use Models\Pedido_Producto;
use Models\Usuario;
use Models\Categoria;
use Lib\Pages;
use Lib\Validador;

class ProductoController
{

  private Pages $pages;
  private Producto $producto;
  private Pedido_Producto $pedido_producto;
  private Pedido $pedido;
  private Categoria $categoria;
  private Usuario $usuario;

  function __construct()
  {
    $this->producto = new Producto();
    $this->pedido_producto = new Pedido_Producto();
    $this->pedido = new Pedido();
    $this->pages = new Pages();
    $this->categoria = new Categoria();
    $this->usuario = new Usuario();
  }

  // Metodo que saldra por defecto
  public function mostrarProductos()
  {
    $productos = $this->producto->getAllPaginate();
    $total_productos = count($productos);
    // $categorias = $this->categoria->getAll();
    $this->pages->render("farmacia/home", ["productos" => $productos, "total_productos" => $total_productos]);
  }
  //Paginacion del Inicio
  public function pagina()
  {
    $productos = $this->producto->getAllPaginate(4, $_POST['page']);
    $total_productos = count($productos);
    $this->pages->render("farmacia/home", ["productos" => $productos, "total_productos" => $total_productos]);
  }

  // Método para mostrar productos no paginados (si es necesario)
  public function mostrarProductos2()
  {
    $productos = $this->producto->getAllPaginate();
    $total_productos = count($productos);
    $this->pages->render("farmacia/productos", ["productos" => $productos, "total_productos" => $total_productos]);
  }

  public function pagina2()
  {
    $productos = $this->producto->getAllPaginate(4, $_POST['page']);
    $total_productos = count($productos);
    $this->pages->render("farmacia/productos", ["productos" => $productos, "total_productos" => $total_productos]);
  }

  public function añadirCarrito()
  {
    $productos = $this->producto->getAll();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      session_start();
      $id_usuario = $_SESSION['idUsuario'];
      $precio_total = 0;
      $estado = 0;
      $id_producto = $_POST['id_producto'];
      $cantidad = 1;

      $producto = $this->producto->getProductoById($id_producto);
      $cantidad_producto = $producto['cantidad'];

      // Verificar el stock del producto
      if ($cantidad_producto <= 0) {
        $_SESSION['error'] = 'Este producto está agotado';
        header('Location: inicio');
        exit;
      }

      $comprobarPedido = $this->pedido->mostrarPedido($id_usuario);
      if ($comprobarPedido) {
        $id_pedido = $comprobarPedido['id'];

        $cantidad_producto_pedido = $this->pedido_producto->getPedidoProductoByIdProducto($id_producto, $id_pedido);
        if ($cantidad_producto_pedido && $cantidad_producto_pedido['cantidad'] >= $cantidad_producto) {
          $_SESSION['error'] = 'No hay suficiente stock para añadir más de este producto';
          header('Location: carrito');
          exit;
        }

        $pedido_producto = $this->pedido_producto->añadirProductoAPedido($id_pedido, $id_producto, $cantidad);
      } else {
        $pedido = $this->pedido->añadirPedido($id_usuario, $precio_total, $estado);
        $id_pedido = $pedido;

        $pedido_producto = $this->pedido_producto->añadirProductoAPedido($id_pedido, $id_producto, $cantidad);
      }

      $pedido = $this->pedido->mostrarPedido($id_usuario);
      $productos = $this->producto->getProductosWithPedidosProductos($pedido['id']);
      $precio_total_carrito = 0;
      foreach ($productos as $value) {
        $precio_total_carrito += $value['precio_total'];
      }

      $this->pages->render("farmacia/carrito", ["productos" => $productos, 'idPedido' => $pedido['id'], 'precio_total_carrito' => $precio_total_carrito]);

    } else {
      $productos = $this->producto->getAllPaginate(3, $_POST['page']);
      $total_productos = count($productos);
      $this->pages->render("farmacia/home", ["productos" => $productos, "total_productos" => $total_productos]);
    }
  }

  // Metodo para buscar por un valor
  public function buscar()
  {
    $productos = $this->producto->getAll();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!empty($_POST['campo']) && !empty($_POST['buscar'])) {
        $campo = $_POST['campo'];
        $buscar = $_POST['buscar'];

        $productos = $this->producto->buscar($campo, $buscar);

        $total_productos = count($productos);
        $this->pages->render("farmacia/home", ["productos" => $productos, "total_productos" => $total_productos]);

      } else {
        echo "No se puede buscar";
      }
    }
    $this->pages->render("farmacia/home", ["productos" => $productos, "total_productos" => $total_productos]);
  }

  // Metodo para borrar un Producto desde el Inicio
  public function borrarProducto()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!empty($_POST['id_producto'])) {
        $id = $_POST['id_producto'];

        $this->producto->eliminar($id);

      } else {
        echo "No se puede borrar el Usuario";
      }
    }
    $this->mostrarProductos();
  }

  // Metodo para borrar un Producto desde Productos
  public function borrarProducto2()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!empty($_POST['id_producto'])) {
        $id = $_POST['id_producto'];

        $this->producto->eliminar($id);

      } else {
        echo "No se puede borrar el Usuario";
      }
    }
    $this->mostrarProductos2();
  }
  public function mostrarAñadirProducto()
  {
    //DEVOLVER EL FORMULARIO DE AÑADIR
    $errores = [];
    $categorias = $this->categoria->getAll();

    $this->pages->render("farmacia/form_insertarProducto", ["errores" => $errores, "categorias" => $categorias]);

  }
  // Metodo para añadir un Producto
  public function añadirProducto()
  {
    $errores = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // print_r($_POST);

      // Valida los siguientes datos
      $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
      $descripcion = filter_var($_POST["descripcion"], FILTER_SANITIZE_STRING);
      $precio = filter_var($_POST["precio"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      $cantidad_producto = filter_var($_POST["cantidad"], FILTER_SANITIZE_NUMBER_INT);
      $id_categoria = $_POST['id_categoria'];

      // Deben estar estos campos completos
      if (!empty($nombre) && !empty($descripcion) && !empty($precio) && !empty($cantidad_producto) && !empty($id_categoria)) {
        //$id_categoria = $_POST['id_categoria'];

        $resultado = $this->producto->insertarProducto($nombre, $descripcion, $precio, $cantidad_producto, $id_categoria);
        if ($resultado) {
          $this->mostrarProductos2();
        }
      } else {
        // Si no es la validacion correcta mandara mensajes en cada error
        $errores['error_nombre'] = Validador::es_obligatorio($nombre, "nombre");
        $errores['error_descripcion'] = Validador::es_obligatorio($descripcion, "descripcion");
        $errores['error_precio'] = Validador::es_obligatorio($precio, "precio");
        $errores['error_cantidad'] = Validador::es_obligatorio($cantidad_producto, "cantidad");


        $this->pages->render("farmacia/form_insertarProducto", ["errores" => $errores]);

      }
    } else {
      $this->pages->render("farmacia/form_insertarProducto", ["errores" => $errores]);

    }
  }

  public function mostrarEditarProducto()
  {
    //DEVOLVER EL FORMULARIO DE EDITAR
    $errores = [];
    $id_producto = $_POST['id_producto'];

    $producto = $this->producto->getProductoById($id_producto);
    $categorias = $this->categoria->getAll();

    $this->pages->render("farmacia/form_editarProducto", ["errores" => $errores, "producto" => $producto, "categorias" => $categorias, 'id_producto' => $id_producto]);
  }

  // Metodo para editar un Producto
  public function editarProducto()
  {
    $errores = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Validar la existencia de id_producto
      if (isset($_POST['id_producto'])) {
        $id = filter_input(INPUT_POST, 'id_producto', FILTER_SANITIZE_NUMBER_INT);
        $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
        $descripcion = filter_var($_POST["descripcion"], FILTER_SANITIZE_STRING);
        $precio = filter_var($_POST["precio"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $cantidad = filter_var($_POST["cantidad"], FILTER_SANITIZE_NUMBER_INT);

        // Editar el producto
        $resultado = $this->producto->editarProducto($id, $nombre, $descripcion, $precio, $cantidad);

        if ($resultado) {
          $this->mostrarProductos2();
        }
      } else {
        echo "Error: No se ha proporcionado el ID del producto.";
      }
    } else {
      if (isset($_POST['id_producto'])) {
        $id_producto = filter_input(INPUT_POST, 'id_producto', FILTER_SANITIZE_NUMBER_INT);
        $producto = $this->producto->getProductoById($id_producto);
        $categorias = $this->categoria->getAll();

        $this->pages->render("farmacia/form_editarProducto", ["errores" => $errores, "producto" => $producto, "categorias" => $categorias]);
      } else {
        echo "Error: No se ha proporcionado el ID del producto.";
      }
    }
  }


  public function mostrarProductosCategorias()
  {
    $errores = [];
    $productos = $this->producto->mostrarProductosCategorias($_POST['id_categoria']);
    $categorias = $this->categoria->getAll();
    $this->pages->render("farmacia/login_productos", ["errores" => $errores, "productos" => $productos, 'categorias' => $categorias]);
  }

}
?>