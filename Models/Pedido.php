<?php
namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

class Pedido
{
    private BaseDatos $conexion;
    private string $id;
    private string $id_usuario;
    private float $total_precio;
    private string $estado;

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
    public function getId_usuario()
    {
        return $this->id_usuario;
    }
    public function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }
    public function getTotal_precio()
    {
        return $this->total_precio;
    }
    public function setTotal_precio($total_precio)
    {
        $this->total_precio = $total_precio;

        return $this;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    //Funcion para mostrar los pedidos de un usuario y su estado
    public function mostrarPedido($id_usuario)
    {
        $this->conexion->consulta("SELECT * FROM pedidos WHERE id_usuario=$id_usuario AND estado=0");
        return $this->conexion->extraer_registro();
    }

    // Funcion para guardar el pedido
    public function añadirPedido($id_usuario,$total_precio,$estado)
    {
        $sql = $this->conexion->preparada("INSERT INTO pedidos (id_usuario,total_precio,estado) 
        VALUES (:id_usuario,:total_precio,:estado)");

        $sql->bindParam(':id_usuario', $id_usuario);
        $sql->bindParam(':total_precio', $total_precio);
        $sql->bindParam(':estado', $estado);

        try {
            $sql->execute();
            return $this->conexion->insertId();
        } catch (PDOException $a) {
            return $a;

        }
    }
    // Cambiar el pedido a finalizado,es decir,estado 1
    public function updateEstado($id_pedido){
        $estado = 1;
        $sql = $this->conexion->preparada("UPDATE pedidos 
        SET estado = :estado WHERE id =:id");

        $sql->bindParam(':estado', $estado);
        $sql->bindParam(':id', $id_pedido);



        
    }

}