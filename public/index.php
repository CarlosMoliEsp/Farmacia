<?php
use Controllers\CategoriaController;

error_reporting(E_ERROR | E_WARNING | E_PARSE);
//require '../includes/bootstrap.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\UsuarioController;
use Controllers\ProductoController;
use Controllers\CarritoController;
// use Controllers\CategoriaController;
use Lib\Router;

use Dotenv\Dotenv;

// Añadir Dotenv
$dotenv = Dotenv::createImmutable(dirname(__DIR__ . '/')); // para acceder al contenido del archivo .env
//$dotenv =Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad(); // si no existe no nos marca error*/

//ROUTER

// Rout del login
Router::add('GET', '/', function () {
    (new UsuarioController())->inicio();
});

// Rout que muestra la lista de productos
Router::add('GET', 'inicio', function () {
    (new ProductoController())->mostrarProductos();
});

// Rout que muestra la lista de productos sin scrollbar
Router::add('GET', '/productos', function () {
    (new ProductoController())->mostrarProductos2();
});

// Rout del form de registro
Router::add('GET', '/registro', function () {
    (new UsuarioController())->mostrarRegistro();
});

// Rout del login
Router::add('POST', '/login', function () {
    (new UsuarioController())->login();
});

// Rout para registrar un usuario
Router::add('POST', '/registrar', function () {
    (new UsuarioController())->registrar();
});

// Rout que muestra la lista de usuarios
Router::add('GET', '/usuarios', function () {
    (new UsuarioController())->mostrarUsuarios();
});

// Rout para añadir un producto al carrito
Router::add('POST', '/comprar', function () {
    (new ProductoController())->añadirCarrito();
});

// Rout para buscar un producto
Router::add('POST', '/buscarProducto', function () {
    (new ProductoController())->buscar();
});

// Rout para MOSTRAR el form de añadir un producto 
Router::add('GET', '/form_insertarProducto', function () {
    (new ProductoController())->mostrarAñadirProducto();
});

// Rout para crear un nuevo producto
Router::add('POST', '/insertarProducto', function () {
    (new ProductoController())->añadirProducto();
});

// Rout para MOSTRAR el form de editar un producto 
Router::add('POST', '/mostrarEditarProducto', function () {
    (new ProductoController())->mostrarEditarProducto();
});

// Rout para editar un producto 
Router::add('POST', '/editarProducto', function () {
    (new ProductoController())->editarProducto();
});

// Rout para borrar un producto
Router::add('POST', '/borrarProducto', function () {
    (new ProductoController())->borrarProducto();
});

// Rout para mostrar el carrito
Router::add('GET', '/carrito', function () {
    (new CarritoController())->mostrarCarrito();
});

// Rout para eliminar un usuario
Router::add('POST', '/borrarUsuario', function () {
    (new UsuarioController())->borrarUsuario();
});

// Rout para editar un usuario
Router::add('POST', '/editarUsuario', function () {
    (new UsuarioController())->editarUsuario();
});

// Rout para editar un usuario
Router::add('POST', '/mostrarEditarUsuario', function () {
    (new UsuarioController())->mostrarEditarUsuario();
});

// Rout para realizar la compra del carrito
Router::add('POST', '/pagar', function () {
    (new CarritoController())->pagar();
});

// Rout para crear un nuevo usuario
Router::add('POST', '/insertarUsuario', function () {
    (new UsuarioController())->crearUsuario();
});

// Rout para MOSTRAR el crear un nuevo usuario
Router::add('GET', '/form_insertarUsuario', function () {
    (new UsuarioController())->mostrarCrearUsuario();
});

// Rout para borrar usuario
Router::add('GET', '/logout', function () {
    (new UsuarioController())->logout();
});

// Rout para borrar pedido
Router::add('POST', '/borrarProducto_Carrito', function () {
    (new CarritoController())->borrarProducto_Carrito();
});

// Rout para sumar un producto al pedido
Router::add('GET', '/pagar', function () {
    (new CarritoController())->pagar();
});

// Rout para restar un producto al pedido
Router::add('POST', '/borrarCantidadProductoCarrito', function () {
    (new CarritoController())->borrarCantidadProductoCarrito();
});

// Rout que muestra la lista de categorias
Router::add('GET', '/categorias', function () {
    (new CategoriaController())->mostrarCategorias();
});

// Rout para MOSTRAR el form de añadir una Categoria 
Router::add('GET', '/form_insertarCategoria', function () {
    (new CategoriaController())->mostrarAñadirCategoria();
});

// Rout para crear una nueva Categoria
Router::add('POST', '/insertarCategoria', function () {
    (new CategoriaController())->añadirCategoria();
});

// Rout para eliminar una Categoria
Router::add('POST', '/borrarCategoria', function () {
    (new CategoriaController())->borrarCategoria();
});

// Rout para eliminar una Categoria
Router::add('POST', '/mostrarProductosCategorias', function () {
    (new ProductoController())->mostrarProductosCategorias();
});

// Rout para MOSTRAR el form de editar una Categoria
Router::add('POST', '/mostrarEditarCategoria', function () {
    (new CategoriaController())->mostrarEditarCategoria();
});

// Rout para editar una Categoria 
Router::add('POST', '/editarCategoria', function () {
    (new CategoriaController())->editarCategoria();
});

// Rout para buscar una pagina de productos
Router::add('POST', '/pagina', function () {
    (new ProductoController())->pagina();
});

// Rout para buscar una pagina de productos sin scrollbar
Router::add('POST', '/pagina2', function () {
    (new ProductoController())->pagina2();
});

// Rout que muestra la lista de productos cuando no está logueado
Router::add('GET', '/productos_logout', function () {
    (new UsuarioController())->mostrarProductosLogout();
});


// FIN DE LOS ROUTER
Router::dispatch();
?>