<?php

class ControladorTick {
    function marcarComoCompletada(){
        // Crea la conexión utilizando la clase que has creado
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $idTarea = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $idUsuario = Sesion::getUsuario()->getId();

        // Verifica si ya existe un registro para esta tarea y usuario
        $tickDAO = new TickDAO($conn);
        if($tickDAO->existByIdUsuarioIdTarea($idUsuario, $idTarea)){
            print json_encode(['respuesta'=>'error', 'mensaje'=>'La tarea ya está marcada como completada']);
            die();
        }

        // Inserta un nuevo registro para marcar la tarea como completada
        if($tickDAO->insert($idUsuario, $idTarea)){
            print json_encode(['respuesta'=>'ok', 'mensaje'=>'Tarea marcada como completada']);
        }else{
            print json_encode(['respuesta'=>'error', 'mensaje'=>'Error al marcar la tarea como completada']);
        }
    }

    function desmarcarComoCompletada(){
        // Crea la conexión utilizando la clase que has creado
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $idTarea = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $idUsuario = Sesion::getUsuario()->getId();

        // Verifica si existe un registro para esta tarea y usuario
        $tickDAO = new TickDAO($conn);
        if(!$tickDAO->existByIdUsuarioIdTarea($idUsuario, $idTarea)){
            print json_encode(['respuesta'=>'error', 'mensaje'=>'La tarea no está marcada como completada']);
            die();
        }
        $tick=$tickDAO->getByIdUsuarioIdTarea($idUsuario,$idTarea);
        if($tickDAO->delete($tick)){
            print json_encode(['respuesta'=>'ok', 'mensaje'=>'Tarea marcada como no completada']);
        }else{
            print json_encode(['respuesta'=>'error', 'mensaje'=>'Error al marcar la tarea como no completada']);
        }
    }
}
