<?php
namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

class Categoria
{
    private BaseDatos $conexion;
    private string $id;
    private string $nombre;

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

    //Funcion para mostrar todas los usuarios
    public function getAll()
    {
        $this->conexion->consulta("SELECT * FROM categorias");
        return $this->conexion->extraer_todos();
    }

    // 
    public function getCategoriaById($id_categoria)
    {
        $this->conexion->consulta("SELECT * FROM categorias WHERE id = $id_categoria");

        return $this->conexion->extraer_registro();
    }

    // Funcion para eliminar una Categoria
    public function eliminar($id)
    {
        $sql = ("DELETE FROM categorias WHERE id = $id");
        $this->conexion->consulta($sql);

    }

    // Funcion para insertar una Categoria
    public function insertarCategoria($nombre)
    {
        $sql = $this->conexion->preparada("INSERT INTO categorias (nombre) 
        VALUES (:nombre)");

        $sql->bindParam(':nombre', $nombre);

        try {
            $sql->execute();
            return true;
        } catch (PDOException $a) {
            return $a;
        }
    }

    // Funcion para editar una categoria
    public function editarCategoria($id,$nombre)
    {
        $sql = $this->conexion->preparada("UPDATE categorias 
        SET nombre = :nombre WHERE id = :id");

        $sql->bindParam(':nombre', $nombre);
        $sql->bindParam(':id', $id);

        try {
            $sql->execute();
            return true;
        } catch (PDOException $a) {
            return $a;
        }
    }

}