<?php

class TareasDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function obtenerLasTareasUsuario($idUsuario) {
        $query = "SELECT * FROM tareas WHERE idUsuario = ?";
        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conexion->error);
        }

        $stmt->bind_param("i", $idUsuario);

        if (!$stmt->execute()) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }

        $resultados = $stmt->get_result();
        $tareasUsuario = array();

        while ($tarea = $resultados->fetch_object(Tarea::class)) {
            $tareasUsuario[] = $tarea;
        }

        $stmt->close();
        return $tareasUsuario;

    }
    
    public function insertar($tarea){
        if(!$stmt = $this->conexion->prepare("INSERT INTO tareas (texto, idUsuario) VALUES (?,?)")){
            die("Error al preparar la consulta insert: " . $this->conn->error );
        }

        $texto = $tarea->getTexto();
        $idUsuario = $tarea->getIdUsuario();
        $stmt->bind_param('si', $texto, $idUsuario);
        if($stmt->execute()){
            $idtarea = $stmt->insert_id;
            return $this->obtenerTareaPorId($idtarea);
        } else {
            return false;
        }
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

        if($resultado->num_rows == 1){
            $tarea = $resultado->fetch_object(Tarea::class);
            return $tarea;
        }
        else{
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
        if(!$stmt = $this->conexion->prepare("UPDATE tareas SET texto=? WHERE id=?")){
            die("Error al preparar la consulta update: " . $this->conexion->error );
        }
        $texto = $tarea->getTexto();
        $id = $tarea->getId();
        $stmt->bind_param('si', $texto,$id);
        return $stmt->execute();
    }
}

?>
