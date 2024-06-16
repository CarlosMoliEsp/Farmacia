<?php
namespace Models;

use Lib\BaseDatos;
use PDO;
use PDOException;

class Usuario
{
    private BaseDatos $conexion;
    private string $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private string $password;

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
    public function getApellidos()
    {
        return $this->apellidos;
    }
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    //Funcion para mostrar todas los usuarios
    public function getAll()
    {
        $this->conexion->consulta("SELECT * FROM usuarios");
        return $this->conexion->extraer_todos();
    }

    public function obtenerUsuarioPorEmail($email)
    {
        $this->conexion->consulta("SELECT * FROM usuarios WHERE email='$email'");
        return $this->conexion->extraer_registro();
    }
    public function existeEmail($email)
    {
        $this->conexion->consulta("SELECT * FROM usuarios WHERE email='$email'");
        return true;
    }

    public function obtenerUsuarioPorID($id)
    {
        $this->conexion->consulta("SELECT * FROM usuarios WHERE id='$id'");
        return $this->conexion->extraer_registro();
    }

    // Funcion para verificar el login de un usuario
    public function login($email, $password)
    {
        $usuario = $this->obtenerUsuarioPorEmail($email);
        if ($usuario) {
            if (password_verify($password, $usuario['password'])) {
                return $usuario;
            } else {
                return false;
            }
        }
    }

    // Funcion para insertar una ruta dandole todos los datos
    public function registro($nombre, $apellidos, $email, $password, $rol)
    {
        $sql = $this->conexion->preparada("INSERT INTO usuarios (nombre,apellidos,email,password,rol) 
        VALUES (:nombre,:apellidos,:email,:password,:rol)");

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql->bindParam(':nombre', $nombre);
        $sql->bindParam(':apellidos', $apellidos);
        $sql->bindParam(':email', $email);
        $sql->bindParam(':password', $password_hash);
        $sql->bindParam(':rol', $rol);

        try {
            $sql->execute();
            return true;
        } catch (PDOException $a) {
            return $a;
        }
    }

    // Funcion para eliminar un usuario
    public function eliminar($id)
    {
        $sql = ("DELETE FROM usuarios WHERE id = $id");
        $this->conexion->consulta($sql);

    }

    public function getUsuarioById($id)
    {
        $this->conexion->consulta("SELECT * FROM usuarios WHERE id='$id'");
        return $this->conexion->extraer_registro();
    }
    // Funcion para editar un usuario
    public function editarUsuario($id, $nombre, $apellidos, $email, $rol, $password = null)
    {
        $sql = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, email = :email, rol = :rol";

        if ($password) {
            $sql .= ", password = :password";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->conexion->preparada($sql);

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':rol', $rol);
        $stmt->bindParam(':id', $id);

        if ($password) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':password', $password_hash);
        }

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $a) {
            return $a;
        }
    }

    public function insertarUsuario($nombre, $apellidos, $email, $password, $rol)
    {
        $sql = $this->conexion->preparada("INSERT INTO usuarios (nombre,apellidos,email,password,rol) 
        VALUES (:nombre,:apellidos,:email,:password,:rol)");

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql->bindParam(':nombre', $nombre);
        $sql->bindParam(':apellidos', $apellidos);
        $sql->bindParam(':email', $email);
        $sql->bindParam(':password', $password_hash);
        $sql->bindParam(':rol', $rol);

        try {
            $sql->execute();
            return true;
        } catch (PDOException $a) {
            return $a;
        }
    }

}