<?php
namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

class Producto
{
    private BaseDatos $conexion;
    private string $id;
    private string $nombre;
    private string $descripcion;
    private float $precio;
    private int $cantidad;
    private int $id_categoria;

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
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }
    public function getPrecio()
    {
        return $this->precio;
    }
    public function setPrecio($precio)
    {
        $this->precio = $precio;

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
    public function getId_categoria()
    {
        return $this->id_categoria;
    }
    public function setId_categoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;

        return $this;
    }

    //Funcion para mostrar todas los productos
    public function getAll()
    {
        $this->conexion->consulta("SELECT * FROM productos");
        return $this->conexion->extraer_todos();
    }

    public function getAllPaginate($limit = 4, $page = 2)
    {
        $this->conexion->consulta("SELECT p.*, c.nombre as nombre_categoria 
        FROM productos p
        INNER JOIN categorias c ON p.id_categoria = c.id
        LIMIT " . (($page - 1) * $limit) . " , $limit");
        return $this->conexion->extraer_todos();
    }

    public function getProductosWithPedidosProductos($id_pedido)
    {
        $this->conexion->consulta("
            SELECT pp.*, p.nombre as nombreProducto, p.precio as precioProducto, SUM(p.precio) as precio_total, COUNT(p.id) as total_productos 
            FROM pedidos_productos pp 
            INNER JOIN productos p ON p.id = pp.id_producto 
            WHERE pp.id_pedido = $id_pedido GROUP BY p.id;
            ");
        return $this->conexion->extraer_todos();
    }

    public function getProductoById($id_producto)
    {
        $this->conexion->consulta("SELECT * FROM productos WHERE id = $id_producto");

        return $this->conexion->extraer_registro();
    }

    // Funcion para buscar
    public function buscar($campo, $buscar)
    {
        $this->conexion->consulta("SELECT * FROM productos WHERE $campo LIKE '%$buscar%' ");
        return $this->conexion->extraer_todos();
    }

    // Funcion para insertar un producto dandole todos los datos *
    public function insertarProducto($nombre, $descripcion, $precio, $cantidad, $id_categoria)
    {
        $sql = $this->conexion->preparada("INSERT INTO productos (nombre, descripcion, precio, cantidad, id_categoria) 
    VALUES (:nombre, :descripcion, :precio, :cantidad, :id_categoria)");

        $sql->bindParam(':nombre', $nombre);
        $sql->bindParam(':descripcion', $descripcion);
        $sql->bindParam(':precio', $precio);
        $sql->bindParam(':cantidad', $cantidad);
        $sql->bindParam(':id_categoria', $id_categoria);

        try {
            $sql->execute();
            return true;
        } catch (PDOException $e) {
            // Devuelve el mensaje de error
            return $e->getMessage();
        }
    }


    // Funcion para eliminar 
    public function eliminar($id)
    {
        $sql = ("DELETE FROM productos WHERE id = $id");
        $this->conexion->consulta($sql);

    }
    // Funcion para aumentar la cantidad de un producto en el pedido
    public function updateCantidad($id_producto, $cantidad)
    {

        $this->conexion->consulta("SELECT * FROM productos WHERE id = $id_producto");

        $producto = $this->conexion->extraer_registro();

        $cantidadTotal = $producto['cantidad'] - $cantidad;

        $sql = $this->conexion->preparada("UPDATE productos 
        SET cantidad = :cantidad WHERE id =:id");

        $sql->bindParam(':cantidad', $cantidadTotal);
        $sql->bindParam(':id', $id_producto);

        try {
            $sql->execute();
            return true;
        } catch (PDOException $a) {
            return $a;
        }
    }

    // Funcion para editar un producto
    public function editarProducto($id, $nombre, $descripcion, $precio, $cantidad)
    {
        $sql = $this->conexion->preparada("UPDATE productos 
        SET nombre = :nombre, descripcion = :descripcion, precio = :precio , cantidad = :cantidad WHERE id =:id");

        $sql->bindParam(':nombre', $nombre);
        $sql->bindParam(':descripcion', $descripcion);
        $sql->bindParam(':precio', $precio);
        $sql->bindParam(':cantidad', $cantidad);
        $sql->bindParam(':id', $id);

        try {
            $sql->execute();
            return true;
        } catch (PDOException $a) {
            return $a;
        }
    }

    public function mostrarProductosCategorias($id_categoria)
    {
        $this->conexion->consulta("SELECT * FROM productos WHERE id_categoria = $id_categoria");
        return $this->conexion->extraer_todos();
    }

}