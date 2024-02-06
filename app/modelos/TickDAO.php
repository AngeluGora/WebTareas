<?php

class TickDAO {
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insert($idUsuario, $idTarea) {
        // No debería haber un punto y coma después de la condición if
        if ($this->existByIdUsuarioIdTarea($idUsuario, $idTarea)) {
            return false;  // O puedes lanzar una excepción o manejar de alguna otra manera
        }
        if (!$stmt = $this->conn->prepare("INSERT INTO tick (idUsuario, idTarea) VALUES (?, ?)")) {
            die("Error al preparar la consulta insert: " . $this->conn->error);
        }
        $stmt->bind_param('ii', $idUsuario, $idTarea);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    public function delete($tick) {
        if (!$stmt = $this->conn->prepare("DELETE FROM tick WHERE id = ?")) {
            die("Error al preparar la consulta delete: " . $this->conn->error);
        }

        $id = $tick->getId();
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return $stmt->affected_rows >= 1;
    }

    public function existByIdUsuarioIdTarea($idUsuario, $idTarea) {
        if (!$stmt = $this->conn->prepare("SELECT 1 FROM tick WHERE idTarea = ? AND idUsuario = ?")) {
            die("Error al preparar la consulta select count: " . $this->conn->error);
        }

        $stmt->bind_param('ii', $idTarea, $idUsuario);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows >= 1;
    }

    public function getByIdUsuarioIdTarea($idUsuario, $idTarea) {
        if (!$stmt = $this->conn->prepare("SELECT * FROM tick WHERE idTarea = ? AND idUsuario = ?")) {
            die("Error al preparar la consulta select count: " . $this->conn->error);
        }

        $stmt->bind_param('ii', $idTarea, $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_object(Tick::class);
    }
}
