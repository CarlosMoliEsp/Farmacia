<?php
namespace Controllers;

use Models\Categoria;
use Lib\Pages;
use Lib\Validador;

class CategoriaController
{

  private Pages $pages;
  private Categoria $categoria;

  function __construct()
  {
    $this->categoria = new Categoria();
    $this->pages = new Pages();

  }

  // Metodo para mostrar la tabla de Categorias
  public function mostrarCategorias()
  {
    $categorias = $this->categoria->getAll();
    $this->pages->render("farmacia/categorias", ["categorias" => $categorias]);
  }

  public function mostrarAñadirCategoria()
  {
    //DEVOLVER EL FORMULARIO DE AÑADIR
    $errores = [];
    $this->pages->render("farmacia/form_insertarCategoria", ["errores" => $errores]);
  }
  // Metodo para añadir una Categoria
  public function añadirCategoria()
  {
    $errores = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // print_r($_POST);

      // Valida los siguientes datos
      $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);

      // Deben estar estos campos completos
      if (!empty($nombre)) {

        $resultado = $this->categoria->insertarCategoria($nombre);
        if ($resultado) {
          $this->mostrarCategorias();
        }
      } else {
        // Si no es la validacion correcta mandara mensajes en cada error
        $errores['error_nombre'] = Validador::es_obligatorio($nombre, "nombre");

        $this->pages->render("farmacia/form_insertarCategoria", ["errores" => $errores]);

      }
    } else {
      $this->pages->render("farmacia/form_insertarCategoria", ["errores" => $errores]);

    }
  }

  //Metodo para borrar una Categoria
  public function borrarCategoria()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (!empty($_POST['id_categoria'])) {
        $id = $_POST['id_categoria'];

        $this->categoria->eliminar($id);

      } else {
        echo "No se puede borrar la Categoria";
      }
    }
    $this->mostrarCategorias();
  }


  // Método para mostrar el formulario de editar categoría
  public function mostrarEditarCategoria()
  {
    //DEVOLVER EL FORMULARIO DE EDITAR
    $errores = [];
    $id_categoria = $_POST['id_categoria'];

    // Obtener la categoría por su ID
    $categoria = $this->categoria->getCategoriaById($id_categoria);

    // Pasar la categoría como 'categoria' en lugar de 'categorias'
    $this->pages->render("farmacia/form_editarCategoria", ["errores" => $errores, "categoria" => $categoria]);
  }


  // Metodo para editar un Categoria
  public function editarCategoria()
  {
    $errores = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Valida los siguientes datos
      $nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_STRING);
      $id_categoria = $_POST['id'];

      if (!empty($nombre) && !empty($id_categoria)) {
        $resultado = $this->categoria->editarCategoria($id_categoria, $nombre);
        if ($resultado === true) {
          // Si la edición es exitosa, mostramos las categorías
          $this->mostrarCategorias();
          return;
        } else {
          // Si hay un error en la consulta SQL, lo mostramos
          $errores['error_general'] = 'No se pudo actualizar la categoría. ' . $resultado->getMessage();
        }
      } else {
        // Si los campos están vacíos, mostramos el error
        if (empty($nombre)) {
          $errores['error_nombre'] = 'El nombre es obligatorio.';
        }
      }

      // Si hay errores, volvemos a mostrar el formulario con los errores
      $categoria = $this->categoria->getCategoriaById($id_categoria);
      $this->pages->render("farmacia/form_editarCategoria", ["errores" => $errores, "categorias" => $categoria]);
    } else {
      // Si no es POST, redirigimos al listado de categorías
      $this->mostrarCategorias();
    }
  }


}
?>