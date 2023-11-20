<?php
require './baseDatos/accesoDatos.php';

use League\Csv\Writer;
use League\Csv\Reader;

class Trabajador implements JsonSerializable
{
    private $id;
    private $nombre;
    private $rol;
    private $sector;
    private $idMesa;
    private $idPedido;
    private $ingresoSistema;
    private $softDelete;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function setRol($rol)
    {
        $this->rol = $rol;
    }

    public function getSector()
    {
        return $this->sector;
    }

    public function setSector($sector)
    {
        $this->sector = $sector;
    }

    public function getIdMesa()
    {
        return $this->idMesa;
    }

    public function setIdMesa($idMesa)
    {
        $this->idMesa = $idMesa;
    }

    public function getIdPedido()
    {
        return $this->idPedido;
    }

    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;
    }

    public function getIngresoSistema()
    {
        return $this->ingresoSistema;
    }

    public function setIngresoSistema($ingresoSistema)
    {
        $this->ingresoSistema = $ingresoSistema;
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
            'nombre' => $this->nombre,
            'rol' => $this->rol,
            'sector' => $this->sector,
            'idMesa' => $this->idMesa,
            'idPedido' => $this->idPedido,
            'ingresoSistema' => $this->ingresoSistema,
            'softDelete' => $this->softDelete
        ];
    }

    public function crearTrabajador()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO trabajadores (nombre, rol, sector, idMesa, idPedido, ingresoSistema, softDelete)
         VALUES (:nombre, :rol, :sector, :idMesa, :idPedido, :ingresoSistema, :softDelete)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
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
    public static function obtenerTrabajadoresActivos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM trabajadores WHERE softDelete = false");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Trabajador');
    }

    public static function buscarUno($idTrabajador)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM trabajadores WHERE id = :id");
        $consulta->bindValue(':id', $idTrabajador, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Trabajador');
    }

    public static function borrarTrabajador($idTrabajador)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE trabajadores SET softDelete = true WHERE id = :id");
        $consulta->bindValue(':id', $idTrabajador, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function modificarTrabajador($trabajador)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE trabajadores SET nombre = :nombre, rol = :rol, sector = :sector, idMesa = :idMesa, 
                                                 idPedido = :idPedido, ingresoSistema = :ingresoSistema WHERE id = :id");
        $consulta->bindValue(':nombre', $trabajador->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $trabajador->rol, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $trabajador->sector, PDO::PARAM_STR);
        $consulta->bindValue(':idMesa', $trabajador->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idPedido', $trabajador->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':ingresoSistema', $trabajador->ingresoSistema);
        $consulta->bindValue(':id', $trabajador->id);
        $consulta->execute();
    }

    public static function guardarCSV($lista)
    {
        if (!is_dir("./csv")) {
            mkdir("./csv", 0777, true);
        }

        $csv = Writer::createFromPath('./csv/trabajadores.csv', 'w+')->setDelimiter(';');

        $csv->insertOne(['id', 'nombre', 'rol', 'sector', 'idMesa', 'idPedido', 'ingresoSistema', 'softDelete']);

        foreach ($lista as $trabajador) {
            $csv->insertOne([
                $trabajador->getId(),
                $trabajador->getNombre(),
                $trabajador->getRol(),
                $trabajador->getSector(),
                $trabajador->getIdMesa(),
                $trabajador->getIdPedido(),
                $trabajador->getIngresoSistema(),
                $trabajador->getSoftDelete(),
            ]);
        }
    }

    public static function leerCSV($rutaArchivo)
    {
        $csv = Reader::createFromPath($rutaArchivo, 'r');
        $csv->setDelimiter(';');

        $records = $csv->getRecords();

        $trabajadores = [];
        $isFirstRow = true;

        foreach ($records as $record) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }

            $trabajador = new Trabajador();
            $trabajador->setId($record[0]);
            $trabajador->setNombre($record[1]);
            $trabajador->setRol($record[2]);
            $trabajador->setSector($record[3]);
            $trabajador->setIdMesa($record[4]);
            $trabajador->setIdPedido($record[5]);
            $trabajador->setIngresoSistema($record[6]);
            $trabajador->setSoftDelete($record[7]);

            $trabajadores[] = $trabajador;
        }

        return $trabajadores;
    }
}
