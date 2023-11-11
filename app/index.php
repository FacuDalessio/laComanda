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
require './middlewares/verificarSocioMiddleware.php';
require './middlewares/trabajadorMiddleware.php';
require './middlewares/productoMiddleware.php';
require './middlewares/pedidoMiddleware.php';
require './middlewares/mesaMiddleware.php';
require './middlewares/clienteMiddleware.php';
require './middlewares/socioMiddleware.php';


$app = AppFactory::create();

$app->group('/trabajadores', function (RouteCollectorProxy $group) {
    $group->get('[/]', \TrabajadorController::class . ':TraerTodos');
    $group->get('/mozos', \TrabajadorController::class . ':TraerTodos')->add(new FiltrarMozos());
    $group->get('/{idTrabajador}', \TrabajadorController::class . ':TraerUno');
    $group->delete('/{idTrabajador}', \TrabajadorController::class . ':BorrarUno');
    $group->put('/{idTrabajador}', \TrabajadorController::class . ':ModificarUno')->add(new ModificarTrabajador());
    $group->post('[/]', \TrabajadorController::class . ':CargarUno')->add(new CargarTrabajador());
  })->add(new VerificarSocio());

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos');
    $group->post('[/]', \ProductoController::class . ':CargarUno')->add(new VerificarSocio())->add(new CargarProducto);
    $group->delete('/{idProducto}', \ProductoController::class . ':BorrarUno')->add(new VerificarSocio());
    $group->get('/{idProducto}', \ProductoController::class . ':TraerUno');
    $group->put('/{idProducto}', \ProductoController::class . ':ModificarUno')->add(new VerificarSocio())->add(new ModificarProducto());
  });

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos')->add(new VerificarSocio());
    $group->post('[/]', \MesaController::class . ':CargarUno')->add(new VerificarSocio());
    $group->delete('/{idMesa}', \MesaController::class . ':BorrarUno')->add(new VerificarSocio());
    $group->get('/{idMesa}', \MesaController::class . ':TraerUno')->add(new VerificarSocio());
    $group->put('/{idMesa}', \MesaController::class . ':ModificarUno')->add(new VerificarSocio())->add(new ModificarMesa());
    // $group->get('/cerradas', \MesaController::class . ':TraerMesasCerradas');
  });

  $app->group('/clientes', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerMesasCerradas');
    $group->put('/sentarse/{idMesa}', \MesaController::class . ':ModificarUno')->add(new ModificarMesa());
    // $group->get('/sentarse', \MesaController::class . ':sentarse');
  });

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos')->add(new VerificarSocio());
    $group->post('[/]', \PedidoController::class . ':CargarUno')->add(new CargarPedido());
    $group->delete('/{idPedido}', \PedidoController::class . ':BorrarUno')->add(new VerificarSocio());
    $group->get('/{idPedido}', \PedidoController::class . ':TraerUno');
    $group->put('/{idPedido}', \PedidoController::class . ':ModificarUno')->add(new ModificarPedido());
  });

$app->group('/socios', function (RouteCollectorProxy $group){
  $group->put('/asignarMozo', \SocioController::class . ':asignarMozo')->add(new AsignarMozo());
})->add(new VerificarSocio());

$app->run();
?>