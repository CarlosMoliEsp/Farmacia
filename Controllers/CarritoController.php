<?php
namespace Controllers;

use Models\Producto;
use Models\Pedido;
use Models\Pedido_Producto;
use Models\Usuario;
use Lib\Pages;
use Lib\Validador;
use Lib\Email;

class CarritoController
{

  private Pages $pages;
  private Producto $producto;
  private Pedido_Producto $pedido_producto;
  private Pedido $pedido;

  private Usuario $usuario;

  function __construct()
  {
    $this->producto = new Producto();
    $this->pedido_producto = new Pedido_Producto();
    $this->pedido = new Pedido();
    $this->pages = new Pages();
    $this->usuario = new Usuario();

  }

  // Metodo que saldra por defecto
  public function mostrarCarrito()
  {
    session_start();
    $idUsuario = $_SESSION['idUsuario'];

    //TRAER EL PEDIDO DE ESE USUARIO CON ESTADO A 0
    $pedido = $this->pedido->mostrarPedido($idUsuario);

    if (!empty($pedido)) {
      $productos = $this->producto->getProductosWithPedidosProductos($pedido['id']);
      $precio_total_carrito = 0;
      foreach ($productos as $value) {
        $precio_total_carrito += $value['precio_total'];
      }

      $this->pages->render("farmacia/carrito", ["productos" => $productos, 'idPedido' => $pedido['id'], 'precio_total_carrito' => $precio_total_carrito]);

    } else {
      $this->pages->render("farmacia/carrito", ["productos" => []]);
    }
  }

  // Funcion para poner un pedido como pagado cambiandole el estado a 1
  public function pagar()
  {
    $productosPedidos = $this->producto->getProductosWithPedidosProductos($_POST['id_pedido']);

    //UPDATE A PRODUCTOS PARA REDUCIR LA CANTIDAD
    $contenido_correo = "<ul>";
    foreach ($productosPedidos as $productoPedido) {
      $this->producto->updateCantidad($productoPedido['id_producto'], $productoPedido['total_productos']);
      $contenido_correo .= "<li>";
      $contenido_correo .= $productoPedido['nombreProducto'] . ' - ';
      $contenido_correo .= $productoPedido['precioProducto'] . '€ - ';
      $contenido_correo .= $productoPedido['total_productos'] . ' unidades -->';
      $contenido_correo .= $productoPedido['precioProducto'] * $productoPedido['total_productos'] . '€ ';
      $contenido_correo .= "</li>";
      // $contenido_correo .= $productoPedido['precioProducto']*$productoPedido['total_productos'].' € ';
    }
    $contenido_correo .= "</ul>";

    session_start();
    $id_usuario = $_SESSION['idUsuario'];
    $pedido = $this->pedido->mostrarPedido($id_usuario);
    $productos = $this->producto->getProductosWithPedidosProductos($pedido['id']);
    $precio_total_carrito = 0;
    foreach ($productos as $value) {
      $precio_total_carrito += $value['precio_total'];
    }
    $contenido_correo .= "El total de la compra es ".$precio_total_carrito.'€. <br>';
    
    $contenido_correo .= "Gracias por comprar en nuestra tienda, tiene 15 días para devoluciones con este ticket.";

    $this->pedido->updateEstado($_POST['id_pedido']);

    session_start();
    $idUsuario = $_SESSION['idUsuario'];
    $usuario = $this->usuario->obtenerUsuarioPorID($idUsuario);

    Email::send('Compra', $contenido_correo, $usuario['email'], $usuario['nombre']);

    $this->pedido_producto->borrarProductosPorPedido($_POST['id_pedido']);
                  
    echo '<span class="badge badge-danger">Gracias por comprar</span>';

    $productos = $this->producto->getAllPaginate();
    $total_productos = count($productos);
    $this->pages->render("farmacia/productos", ["productos" => $productos, "total_productos" => $total_productos]);
  }

  // Funcion para poner un producto del pedido
  public function borrarProducto_Carrito()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!empty($_POST['id_pedido'])) {
        $id_pedido = $_POST['id_pedido'];
        $id_producto = $_POST['id_producto'];

        $this->pedido_producto->borrarProductoAPedido($id_pedido, $id_producto);
        session_start();
        $id_usuario = $_SESSION['idUsuario'];
        $pedido = $this->pedido->mostrarPedido($id_usuario);
        $productos = $this->producto->getProductosWithPedidosProductos($pedido['id']);
        $precio_total_carrito = 0;
        foreach ($productos as $value) {
          $precio_total_carrito += $value['precio_total'];
        }

        $this->pages->render("farmacia/carrito", ["productos" => $productos, 'idPedido' => $pedido['id'], 'precio_total_carrito' => $precio_total_carrito]);


      } else {
        echo "No se puede borrar";
      }
    }
    $this->pages->render("farmacia/carrito", ["productos" => []]);

  }

  public function borrarCantidadProductoCarrito()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!empty($_POST['id_producto'])) {
        $id_pedido = $_POST['id_pedido'];
        $id_producto = $_POST['id_producto'];

        $this->pedido_producto->borrarCantidadProductoAPedido($id_pedido, $id_producto);
        session_start();
        $id_usuario = $_SESSION['idUsuario'];
        $pedido = $this->pedido->mostrarPedido($id_usuario);
        $productos = $this->producto->getProductosWithPedidosProductos($pedido['id']);
        $precio_total_carrito = 0;
        foreach ($productos as $value) {
          $precio_total_carrito += $value['precio_total'];
        }

        $this->pages->render("farmacia/carrito", ["productos" => $productos, 'idPedido' => $pedido['id'], 'precio_total_carrito' => $precio_total_carrito]);


      } else {
        echo "No se puede borrar";
      }
    }
    $this->pages->render("farmacia/carrito", ["productos" => []]);

  }
}

?>