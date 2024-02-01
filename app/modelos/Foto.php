<?php

class Foto{
    private $id;
    private $idTarea;
    private $nombreArchivo;

    

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of idTarea
     */
    public function getIdTarea()
    {
        return $this->idTarea;
    }

    /**
     * Set the value of idTarea
     */
    public function setIdTarea($idTarea): self
    {
        $this->idTarea = $idTarea;

        return $this;
    }

    /**
     * Get the value of nombreArchivo
     */
    public function getNombreArchivo()
    {
        return $this->nombreArchivo;
    }

    /**
     * Set the value of nombreArchivo
     */
    public function setNombreArchivo($nombreArchivo): self
    {
        $this->nombreArchivo = $nombreArchivo;

        return $this;
    }
}