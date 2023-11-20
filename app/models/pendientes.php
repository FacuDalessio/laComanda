<?php

class Pendientes implements JsonSerializable
{
    private $id;
    private $idPedido;
    private $sector;
    private $idProducto;
    private $cantidad;
    private $estado;

    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getIdPedido()
    {
        return $this->idPedido;
    }

    public function setIdPedido($value)
    {
        $this->idPedido = $value;
    }

    public function getSector()
    {
        return $this->sector;
    }

    public function setSector($value)
    {
        $this->sector = $value;
    }

    public function getIdProducto()
    {
        return $this->idProducto;
    }

    public function setIdProducto($value)
    {
        $this->idProducto = $value;
    }

    public function getCantidad()
    {
        return $this->cantidad;
    }

    public function setCantidad($value)
    {
        $this->cantidad = $value;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado($value)
    {
        $this->estado = $value;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'idPedido' => $this->idPedido,
            'sector' => $this->sector,
            'idProducto' => $this->idProducto,
            'cantidad' => $this->cantidad,
            'estado' => $this->estado,
        ];
    }

    public function crearPendiente()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pendientes (idPedido, sector, idProducto, cantidad, estado)
            VALUES (:idPedido, :sector, :idProducto, :cantidad, :estado)");
        $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':idProducto', $this->idProducto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM pendientes");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pendientes');
    }

    public static function modificarPendiente($pendiente)
{
    $objAccesoDato = AccesoDatos::obtenerInstancia();
    $consulta = $objAccesoDato->prepararConsulta("UPDATE pendientes SET idPedido = :idPedido, sector = :sector, 
                                            idProducto = :idProducto, cantidad = :cantidad, estado = :estado WHERE id = :id");
    $consulta->bindValue(':idPedido', $pendiente->idPedido, PDO::PARAM_INT);
    $consulta->bindValue(':sector', $pendiente->sector, PDO::PARAM_STR);
    $consulta->bindValue(':idProducto', $pendiente->idProducto, PDO::PARAM_INT);
    $consulta->bindValue(':cantidad', $pendiente->cantidad, PDO::PARAM_INT);
    $consulta->bindValue(':estado', $pendiente->estado, PDO::PARAM_STR);
    $consulta->bindValue(':id', $pendiente->id);
    $consulta->execute();
}
public static function buscarUno($idPendiente)
{
    $objAccesoDato = AccesoDatos::obtenerInstancia();
    $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM pendientes WHERE id = :id");
    $consulta->bindValue(':id', $idPendiente, PDO::PARAM_INT);
    $consulta->execute();
    return $consulta->fetchObject('Pendientes');
}

}

?>
