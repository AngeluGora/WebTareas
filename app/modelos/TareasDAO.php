<?php

class TareasDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "tareas");

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function obtenerLasTareasUsuario($idUsuario) {
        $query = "SELECT * FROM tareas where idUsuario = $idUsuario";
        $resultados = $this->conexion->query($query);
        $tareas = array();

        if ($resultados->num_rows > 0) {
            while ($tarea = $resultados->fetch_object(Tarea::class)) {
                $tareas[] = $tarea;
            }
        }

        return $tareas;
    }

    public function insertarTarea($texto) {
        $texto = $this->conexion->real_escape_string($texto);
        $query = "INSERT INTO tareas (texto) VALUES ('$texto')";
        
        if ($this->conexion->query($query) === TRUE) {
            $idInsertado = $this->conexion->insert_id;
            $nuevaTarea = $this->obtenerTareaPorID($idInsertado);
            return $nuevaTarea;
        } else {
            return null;
        }
    }

    public function obtenerTareaPorId($id) {
        $query = "SELECT * FROM tareas WHERE id = $id";
        $resultado = $this->conexion->query($query);

        if ($resultado->num_rows > 0) {
            $tarea = $resultado->fetch_object(Tarea::class);
            
            return $tarea;
        } else {
            return null;
        }
    }

    public function cerrarConexion() {
        $this->conexion->close();
    }


    public function borrarTarea($id) {
        $id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);
        $query = "delete from tareas where id=$id";
        
        $this->conexion->query($query);
        if($this->conexion->affected_rows==1){
            return true;
        } else {
            return false;
        }
    }

    function update($tarea){
        if(!$stmt = $this->conn->prepare("UPDATE tareas SET fecha=?, texto=?, idUsuario=? WHERE id=?")){
            die("Error al preparar la consulta update: " . $this->conn->error );
        }
        $fecha = $tarea->getFecha();
        $texto = $tarea->getTexto();
        $idUsuario = $tarea->getIdUsuario();
        $id = $tarea->getId();
        $stmt->bind_param('dsii',$fecha, $texto, $idUsuario,$id);
        return $stmt->execute();
    }
}

?>
