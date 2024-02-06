<?php 

class FotosDAO {
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insert($foto){
        if(!$stmt = $this->conn->prepare("INSERT INTO fotos (nombreArchivo, idTarea) VALUES (?,?)")){
            die("Error al preparar la consulta insert: " . $this->conn->error );
        }
        $nombreArchivo = $foto->getNombreArchivo();
        $idTarea = $foto->getIdTarea();
        $stmt->bind_param('si',$nombreArchivo, $idTarea);
        if($stmt->execute()){
            return $stmt->insert_id;
        }
        else{
            return false;
        }
    }

    /**
     * 
     */
    public function delete($foto) {
        // Obtener el nombre del archivo de la foto desde la base de datos
        $id = $foto->getId();
        $nombreArchivo = ""; // Asegúrate de reemplazar esto con el nombre de la columna en tu tabla de base de datos que almacena el nombre del archivo
        if(!$stmt = $this->conn->prepare("SELECT nombreArchivo FROM fotos WHERE id = ?")){
            die("Error al preparar la consulta de selección: " . $this->conn->error);
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($nombreArchivo);
            $stmt->fetch();
        }
        $stmt->close();
    
        $rutaCompleta = "web/imagenes/" .$nombreArchivo; 
    
        // Eliminar la foto del sistema de archivos
        if (!empty($nombreArchivo) && file_exists($rutaCompleta)) {
            if (!unlink($rutaCompleta)) {
                die("Error al eliminar el archivo de imagen del sistema de archivos.");
            }
        }
    
        // Eliminar la fila de la base de datos
        if(!$stmt = $this->conn->prepare("DELETE FROM fotos WHERE id = ?")){
            die("Error al preparar la consulta de eliminación: " . $this->conn->error);
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        if($stmt->affected_rows >=1 ){
            return true;
        } else {
            return false;
        }
    }
    

    public function getAllByIdTarea($idTarea){
        if(!$stmt = $this->conn->prepare("SELECT * FROM fotos WHERE idTarea=?")){
            die("Error al preparar la consulta delete: " . $this->conn->error );
        }
        $stmt->bind_param('i',$idTarea);
        $stmt->execute();
        $result = $stmt->get_result();
        $fotos = array();
        while($foto = $result->fetch_object(Foto::class)){
            $fotos[] = $foto;
        }
        return $fotos;
    }

}

