<?php
    require './baseDatos/accesoDatos.php';

    class Trabajador implements JsonSerializable{
        private $id;
        private $nombre;
        private $rol;
        private $idMesa;
        private $pendientes;
        private $idPedido;
        private $ingresoSistema;
        private $softDelete;
        
        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
        }

        public function getNombre() {
            return $this->nombre;
        }

        public function setNombre($nombre) {
            $this->nombre = $nombre;
        }

        public function getRol() {
            return $this->rol;
        }

        public function setRol($rol) {
            $this->rol = $rol;
        }

        public function getIdMesa() {
            return $this->idMesa;
        }

        public function setIdMesa($idMesa) {
            $this->idMesa = $idMesa;
        }

        public function getPendientes() {
            return $this->pendientes;
        }

        public function setPendientes($pendientes) {
            $this->pendientes = $pendientes;
        }

        public function getIdPedido() {
            return $this->idPedido;
        }

        public function setIdPedido($idPedido) {
            $this->idPedido = $idPedido;
        }

        public function getIngresoSistema() {
            return $this->ingresoSistema;
        }

        public function setIngresoSistema($ingresoSistema) {
            $this->ingresoSistema = $ingresoSistema;
        }

        public function getSoftDelete() {
            return $this->softDelete;
        }

        public function setSoftDelete($softDelete) {
            $this->softDelete = $softDelete;
        }

        public function jsonSerialize() {
            return [
                'id' => $this->id,
                'nombre' => $this->nombre,
                'rol' => $this->rol,
                'idMesa' => $this->idMesa,
                'pendientes' => $this->pendientes,
                'idPedido' => $this->idPedido,
                'ingresoSistema' => $this->ingresoSistema,
                'softDelete' => $this->softDelete
            ];
        }

        public function crearTrabajador()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO trabajadores (nombre, rol, idMesa, pendientes, idPedido, ingresoSistema, softDelete)
         VALUES (:nombre, :rol, :idMesa, :pendientes, :idPedido, :ingresoSistema, :softDelete)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':pendientes', $this->pendientes, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':ingresoSistema', $this->ingresoSistema);
        $consulta->bindValue(':softDelete', $this->softDelete, PDO::PARAM_BOOL);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

        public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM trabajadores");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Trabajador');
    }
    }    
?>