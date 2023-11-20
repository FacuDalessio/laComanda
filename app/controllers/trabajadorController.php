<?php
    require_once './models/trabajador.php';
    require_once './models/mesa.php';
    require_once './models/pendientes.php';
    require_once './utils/autentificadorJWT.php';

    class TrabajadorController{

        public function CargarUno($request, $response, $args)
    {
        $body = $request->getParsedBody();
       
        $trabajador = new Trabajador();

        $trabajador->setNombre($body['nombre']);
        $trabajador->setRol($body['rol']);
        $trabajador->setSector($body['sector']);
        $trabajador->setSoftDelete(false);

        $trabajador->crearTrabajador();

        $payload = json_encode(array("mensaje" => "Trabajador creado con exito"));

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Trabajador::obtenerTrabajadoresActivos();
        $payload = json_encode(array("listaTrabajadores" => $lista));
        $response->getBody()->write($payload);
        return $response;
    }

    public function TraerUno($request, $response, $args)
    {
        $trabajadorId = $args['idTrabajador'];
        $trabajador = Trabajador::buscarUno($trabajadorId);
        if($trabajador != false){
            $payload = json_encode($trabajador);
        }else{
            $payload = json_encode(array("Error" => "No se encontro un trabajador con ese id"));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $trabajadorId = $args['idTrabajador'];
        if (Trabajador::buscarUno($trabajadorId) != false) {

            Trabajador::borrarTrabajador($trabajadorId);
            $payload = json_encode(array("mensaje" => "Trabajador borrado con exito"));
        }else{
            $payload = json_encode(array("Error" => "No se encontro un trabajador con ese id"));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        $trabajador = $request->getAttribute('trabajador');
        
        Trabajador::modificarTrabajador($trabajador);

        $payload = json_encode(array("mensaje" => "Trabajador modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function login($request, $response, $args){
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $id = $parametros['id'];
    
        $trabajador = Trabajador::buscarUno($id);

        if($trabajador != false && $nombre == $trabajador->getNombre()){
          $datos = array('rol' => $trabajador->getRol());
    
          $token = AutentificadorJWT::CrearToken($datos);
          $payload = json_encode(array('jwt' => $token));
        } else {
          $payload = json_encode(array('error' => 'Nombre o id incorrectos'));
        }
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
      
    }

    public function asignarMozo($request, $response, $args){
        $idMesa = $request->getAttribute('idMesa');
        $idMozo = $request->getAttribute('idMozo');
        
        $mozo = Trabajador::buscarUno($idMozo);
        $mesa = Mesa::buscarUno($idMesa);

        if($mozo != false && $mesa != false){
            $mozo->setIdMesa($idMesa);
            $mesa->setIdMozo($idMozo);

            Mesa::modificarMesa($mesa);
            Trabajador::modificarTrabajador($mozo);

            $payload = json_encode(array("mensaje" => "Se asigno el mozo correctamente"));
        }else{
            $payload = json_encode(array("Error" => "No se encontro la mesa o el mozo con esos ids"));
        }

        $response->getBody()->write($payload);
        return $response;
    }

    public function tomarPendiente($request, $response, $args){
        $pendiente = $request->getAttribute('pendiente');
        $tiempo = $request->getAttribute('tiempo');

        $pedido = Pedido::buscarUno($pendiente->getIdPedido());

        if($tiempo > $pedido->getTiempo()){
            $pedido->setTiempo($tiempo);
        }
        $pedido->setEstado('en preparacion');
        Pedido::modificarPedido($pedido);
        $pendiente->setEstado('en preparacion');
        Pendientes::modificarPendiente($pendiente);

        $payload = json_encode(array("mensaje" => "Se tome el pendiente correctamente"));
        $response->getBody()->write($payload);
        return $response;
    }

    public function guardarCSV($request, $response, $args){

        $listaTrabajadores = Trabajador::obtenerTodos();
        Trabajador::guardarCSV($listaTrabajadores);

        $payload = json_encode(array("mensaje" => "Se guardo el CVS correctamente"));
        $response->getBody()->write($payload);
        return $response;
    }

    public function leerCSV($request, $response, $args){
        $uploadedFiles = $request->getUploadedFiles();

        if (isset($uploadedFiles['csv_file'])) {

            $csvFile = $uploadedFiles['csv_file'];

            if ($csvFile->getError() === UPLOAD_ERR_OK) {

                if (!is_dir("./uploads")) {
                    mkdir("./uploads", 0777, true);
                }

                $tempPath = './uploads/';
                $csvPath = $tempPath . $csvFile->getClientFilename();
                $csvFile->moveTo($csvPath);

                $trabajadoresDesdeCSV = Trabajador::leerCSV($csvPath);

                foreach($trabajadoresDesdeCSV as $trabajador){
                    $trabajador->crearTrabajador();
                }

                unlink($csvPath);

                $payload = json_encode(array("mensaje" => 'Archivo CSV procesado con éxito.'));

            } else {
                $payload = json_encode(array("Error" => 'Error al cargar el archivo CSV.'));
            }
        } else {
            $payload = json_encode(array("Error" => 'No se ha proporcionado el archivo CSV.'));
        }

        $response->getBody()->write($payload);
        return $response;
    }
}
