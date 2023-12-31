<?php
class Mesa implements JsonSerializable
{
    private $id;
    private $cliente;
    private $idPedido;
    private $estado;
    private $idMozo;
    private $softDelete;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCliente()
    {
        return $this->cliente;
    }

    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }

    public function getIdPedido()
    {
        return $this->idPedido;
    }

    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function getIdMozo()
    {
        return $this->idMozo;
    }

    public function setIdMozo($idMozo)
    {
        $this->idMozo = $idMozo;
    }

    public function getSoftDelete()
    {
        return $this->softDelete;
    }

    public function setSoftDelete($softDelete)
    {
        $this->softDelete = $softDelete;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'cliente' => $this->cliente,
            'idPedido' => $this->idPedido,
            'estado' => $this->estado,
            'idMozo' => $this->idMozo,
            'softDelete' => $this->softDelete
        ];
    }

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (cliente, idPedido, estado, idMozo, softDelete)
             VALUES (:cliente, :idPedido, :estado, :idMozo, :softDelete)");
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':softDelete', $this->softDelete, PDO::PARAM_BOOL);
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

    public static function obtenerMesasCerradas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id as numeroMesa FROM mesas WHERE estado = 'cerrada'");
        $consulta->execute();

        return $consulta->fetchAll();
    }

    public static function buscarUno($idMesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
    }

    public static function borrarMesa($idMesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET softDelete = true WHERE id = :id");
        $consulta->bindValue(':id', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function modificarMesa($mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET cliente = :cliente, idPedido = :idPedido, estado = :estado, idMozo = :idMozo
                                                WHERE id = :id");
        $consulta->bindValue(':cliente', $mesa->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':idPedido', $mesa->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $mesa->estado, PDO::PARAM_STR);
        $consulta->bindValue(':idMozo', $mesa->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':id', $mesa->id);
        $consulta->execute();
    }
}
