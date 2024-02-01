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
    public function delete($foto){
        if(!$stmt = $this->conn->prepare("DELETE FROM fotos WHERE id = ?")){
            die("Error al preparar la consulta delete: " . $this->conn->error );
        }
        $id = $foto->getId();
        $stmt->bind_param('i',$id);
        $stmt->execute();
        if($stmt->affected_rows >=1 ){
            return true;
        }
        else{
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

