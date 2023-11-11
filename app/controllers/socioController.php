<?php
    class SocioController{
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
    }
?>