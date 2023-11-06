<?php
    class Mesa implements JsonSerializable{
        private $id;
        private $cliente;
        private $idPedido;
        private $estado;
        private $idMozo;

        public function getId() {
            return $this->id;
        }
    
        public function setId($id) {
            $this->id = $id;
        }
    
        public function getCliente() {
            return $this->cliente;
        }
    
        public function setCliente($cliente) {
            $this->cliente = $cliente;
        }
    
        public function getIdPedido() {
            return $this->idPedido;
        }
    
        public function setIdPedido($idPedido) {
            $this->idPedido = $idPedido;
        }
    
        public function getEstado() {
            return $this->estado;
        }
    
        public function setEstado($estado) {
            $this->estado = $estado;
        }
    
        public function getIdMozo() {
            return $this->idMozo;
        }
    
        public function setIdMozo($idMozo) {
            $this->idMozo = $idMozo;
        }

        public function jsonSerialize() {
            return [
                'id' => $this->id,
                'cliente' => $this->cliente,
                'idPedido' => $this->idPedido,
                'estado' => $this->estado,
                'idMozo' => $this->idMozo
            ];
        }

        public function crearMesa()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (cliente, idPedido, estado, idMozo)
             VALUES (:cliente, :idPedido, :estado, :idMozo)");
            $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
            $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
            $consulta->execute();
    
            return $objAccesoDatos->obtenerUltimoId();
        }
    
            public static function obtenerTodos()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas");
            $consulta->execute();
    
            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        }
    }
?>