<?php

class Tick{
    private $id;
    private $idUsuario;
    private $idTarea;

    

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
     * Get the value of idUsuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     */
    public function setIdUsuario($idUsuario): self
    {
        $this->idUsuario = $idUsuario;

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
}