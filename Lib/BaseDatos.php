<?php
namespace Lib;
use PDO;

class BaseDatos  extends PDO{
  

  //private mixed $resultado; //mixed novedad en PHP cualquier valor
    private PDO $conexion;
    private string $servidor;
    private string $usuario;
    private string $pass;
    private string $base_datos;
    private string $tipo_de_base = 'mysql';
  function __construct()
  {
      $this->servidor = $_ENV['DB_HOST'];
      $this->usuario = $_ENV['DB_USER'];
      $this->pass = $_ENV['DB_PASS'];
      $this->base_datos = $_ENV['DB_DATABASE'];
      $this->conexion = $this->conectar();
  }

  private function conectar(): PDO {
    try {
      $conexion = new PDO("mysql:host={$this->servidor};dbname={$this->base_datos}", $this->usuario, $this->pass);
      return $conexion;
    } catch(PDOException $e){
      die('Error: '. $e->getMessage());
    }
  }

  
   public function consulta(string $consultaSQL): void {
       $this->resultado = $this->conexion->query($consultaSQL);
  }
  
  public function extraer_registro(): mixed {
    return ( $fila = $this->resultado->fetch(PDO::FETCH_ASSOC ))? $fila:false;
    
  }

  public function extraer_todos(): array {
    return $this->resultado->fetchAll(PDO::FETCH_ASSOC);
  }
  


  public function ultimoIdInsertado(): int{
    return $this->conexion->lastInsertId;
  }
  public function preparada($pre){
    return $this->conexion->prepare($pre);
  }
  
  public function insertId(){
    return $this->conexion->lastInsertId();
  }
}
