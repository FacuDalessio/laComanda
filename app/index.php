<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require './controllers/trabajadorController.php';
require './controllers/productoController.php';
require './controllers/mesaController.php';
require './controllers/pedidoController.php';
require './controllers/socioController.php';
require './middlewares/verificarTrabajadorMiddleware.php';
require './middlewares/trabajadorMiddleware.php';
require './middlewares/productoMiddleware.php';
require './middlewares/pedidoMiddleware.php';
require './middlewares/mesaMiddleware.php';
require './middlewares/clienteMiddleware.php';
require './middlewares/socioMiddleware.php';
require './middlewares/authMiddleware.php';


$app = AppFactory::create();

$app->group('/trabajadores', function (RouteCollectorProxy $group) {
    $group->get('[/]', \TrabajadorController::class . ':TraerTodos');
    $group->get('/mozos', \TrabajadorController::class . ':TraerTodos')->add(new FiltrarMozos());
    $group->get('/{idTrabajador}', \TrabajadorController::class . ':TraerUno');
    $group->post('/guardarCSV', \TrabajadorController::class . ':guardarCSV');
    $group->post('/leerCSV', \TrabajadorController::class . ':leerCSV');
    $group->delete('/{idTrabajador}', \TrabajadorController::class . ':BorrarUno');
    $group->put('/{idTrabajador}', \TrabajadorController::class . ':ModificarUno')->add(new ModificarTrabajador());
    $group->post('[/]', \TrabajadorController::class . ':CargarUno')->add(new CargarTrabajador());
  })->add(new VerificarSocio())->add(new AuthMiddleware());

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->post('[/]', \ProductoController::class . ':CargarUno')->add(new VerificarSocio())->add(new AuthMiddleware())->add(new CargarProducto);
    $group->delete('/{idProducto}', \ProductoController::class . ':BorrarUno')->add(new VerificarSocio())->add(new AuthMiddleware());
    $group->get('/{idProducto}', \ProductoController::class . ':TraerUno');
    $group->put('/{idProducto}', \ProductoController::class . ':ModificarUno')->add(new VerificarSocio())->add(new AuthMiddleware())->add(new ModificarProducto());
  });

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos')->add(new VerificarSocio())->add(new AuthMiddleware());
    $group->get('/{idMesa}', \MesaController::class . ':TraerUno')->add(new VerificarSocio())->add(new AuthMiddleware());
    $group->post('[/]', \MesaController::class . ':CargarUno')->add(new VerificarSocio())->add(new AuthMiddleware());
    $group->delete('/{idMesa}', \MesaController::class . ':BorrarUno')->add(new VerificarSocio())->add(new AuthMiddleware());
    $group->put('/modificar/{idMesa}', \MesaController::class . ':ModificarUno')->add(new VerificarSocio())->add(new AuthMiddleware())->add(new ModificarMesa());
    $group->put('/asignarMozo', \TrabajadorController::class . ':asignarMozo')->add(new VerificarMozo())->add(new AuthMiddleware())->add(new AsignarMozo());
  });

  $app->group('/clientes', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerMesasCerradas');
    $group->put('/sentarse/{idMesa}', \MesaController::class . ':ModificarUno')->add(new ModificarMesa());
    // $group->get('/sentarse', \MesaController::class . ':sentarse');
  });

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos')->add(new VerificarSocio())->add(new AuthMiddleware());
    $group->get('/pendientes/listar', \PedidoController::class . ':listarPendientes')->add(new AuthMiddleware());
    $group->post('[/]', \PedidoController::class . ':CargarUno')->add(new VerificarMozo())->add(new AuthMiddleware())->add(new CargarPedido());
    $group->post('/cargarProducto', \PedidoController::class . ':cargarProductoAPedido')->add(new VerificarMozo())->add(new AuthMiddleware())->add(new CargarDetallePedido());
    $group->delete('/{idPedido}', \PedidoController::class . ':BorrarUno')->add(new VerificarSocio())->add(new AuthMiddleware());
    $group->get('/{idPedido}', \PedidoController::class . ':TraerUno');
    $group->put('/{idPedido}', \PedidoController::class . ':ModificarUno')->add(new ModificarPedido());
  });

$app->group('/pendientes', function (RouteCollectorProxy $group){
  $group->put('/tomar', \TrabajadorController::class . ':tomarPendiente')->add(new AuthMiddleware())->add(new TomarPendiente());
});

$app->group('/login', function (RouteCollectorProxy $group){
  $group->post('[/]', \TrabajadorController::class . ':login');
});

$app->run();
?>