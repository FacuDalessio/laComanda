<?php
    class Encuesta implements JsonSerializable{
        private $id;
        private $mesa;
        private $restaurante;
        private $mozo;
        private $cocinero;
        private $experiencia;

    public function __construct($mesa, $restaurante, $mozo, $cocinero, $experiencia)
    {
        $this->mesa = $mesa;
        $this->restaurante = $restaurante;
        $this->mozo = $mozo;
        $this->cocinero = $cocinero;
        $this->experiencia = $experiencia;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getMesa()
    {
        return $this->mesa;
    }

    public function setMesa($value)
    {
        $this->mesa = $value;
    }

    public function getRestaurante()
    {
        return $this->restaurante;
    }

    public function setRestaurante($value)
    {
        $this->restaurante = $value;
    }

    public function getMozo()
    {
        return $this->mozo;
    }

    public function setMozo($value)
    {
        $this->mozo = $value;
    }

    public function getCocinero()
    {
        return $this->cocinero;
    }

    public function setCocinero($value)
    {
        $this->cocinero = $value;
    }

    public function getExperiencia()
    {
        return $this->experiencia;
    }

    public function setExperiencia($value)
    {
        $this->experiencia = $value;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'mesa' => $this->mesa,
            'restaurante' => $this->restaurante,
            'mozo' => $this->mozo,
            'cocinero' => $this->cocinero,
            'experiencia' => $this->experiencia,
        ];
    }

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuestas (mesa, restaurante, mozo, cocinero, experiencia)
            VALUES (:mesa, :restaurante, :mozo, :cocinero, :experiencia)");
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':restaurante', $this->restaurante, PDO::PARAM_INT);
        $consulta->bindValue(':mozo', $this->mozo, PDO::PARAM_INT);
        $consulta->bindValue(':cocinero', $this->cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':experiencia', $this->experiencia, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
    }
?>
