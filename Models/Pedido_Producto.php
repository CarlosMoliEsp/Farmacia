<?php
namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

class Pedido_Producto
{
    private BaseDatos $conexion;
    private string $id;
    private string $id_pedido;
    private string $id_producto;
    private string $cantidad;

    function __construct(
    ) {
        $this->conexion = new BaseDatos;
    }

    // GETERS Y SETERS

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    public function getId_pedido()
    {
        return $this->id_pedido;
    }
    public function setId_pedido($id_pedido)
    {
        $this->id_pedido = $id_pedido;

        return $this;
    }
    public function getId_producto()
    {
        return $this->id_producto;
    }
    public function setId_producto($id_producto)
    {
        $this->id_producto = $id_producto;

        return $this;
    }
    public function getCantidad()
    {
        return $this->cantidad;
    }
    public function setCantidad($cantidad)
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getPedidoProductoByIdProducto($id_producto, $id_pedido)
    {
        $this->conexion->consulta("SELECT SUM(cantidad) as cantidad FROM pedidos_productos WHERE id_producto = $id_producto AND id_pedido = $id_pedido");
        return $this->conexion->extraer_registro();
    }

    //Funcion para añdir el producto a los pedidos
    public function añadirProductoAPedido($id_pedido, $id_producto, $cantidad)
    {
        $sql = $this->conexion->preparada("INSERT INTO pedidos_productos (id_pedido,id_producto,cantidad) 
        VALUES (:id_pedido,:id_producto,:cantidad)");

        $sql->bindParam(':id_pedido', $id_pedido);
        $sql->bindParam(':id_producto', $id_producto);
        $sql->bindParam(':cantidad', $cantidad);

        try {
            $sql->execute();
            return true;
        } catch (PDOException $a) {
            return $a;

        }
    }

    //Funcion para borrar producto del pedido
    public function borrarProductoAPedido($id_pedido, $id_producto)
    {
        $sql = ("DELETE FROM pedidos_productos WHERE id_pedido = $id_pedido and id_producto = $id_producto");
        $this->conexion->consulta($sql);
    }

    public function borrarCantidadProductoAPedido($id_pedido, $id_producto)
    {
        $sql = ("DELETE FROM pedidos_productos WHERE id_pedido = $id_pedido and id_producto = $id_producto LIMIT 1");
        $this->conexion->consulta($sql);
    }

    public function comprar($id_pedido, $id_producto, $cantidad)
    {
        $sql = ("INSERT FROM pedidos_productos WHERE id_pedido = $id_pedido and id_producto = $id_producto LIMIT $cantidad");
        $this->conexion->consulta($sql);
    }
    public function borrarProductosPorPedido($id_pedido)
    {
        $sql = $this->conexion->preparada("DELETE FROM pedidos_productos WHERE id_pedido = :id_pedido");
        $sql->bindParam(':id_pedido', $id_pedido);
        try {
            $sql->execute();
            return true;
        } catch (PDOException $e) {
            return $e;
        }
    }

}