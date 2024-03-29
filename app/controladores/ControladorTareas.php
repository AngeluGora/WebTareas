<?php

class ControladorTareas{
    public function ver(){
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $tareasDAO = new TareasDAO($conn);

        $idTarea = htmlspecialchars($_GET['id']);
        $tarea = $tareasDAO->obtenerTareaPorID($idTarea);

        require 'app/vistas/ver_tarea.php';
    }

    public function inicio() {
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();
    
        // Verificar si se proporcionó el idUsuario en la URL
        if (isset($_GET['idUsuario'])) {
            $idUsuario = $_GET['idUsuario'];
            // Asegurarse de que $idUsuario sea un entero antes de usarlo
            if (!is_numeric($idUsuario)) {
                // Manejar el caso en el que idUsuario no es un número (puedes redirigir o manejarlo según tu lógica)
                echo 'El idUsuario no es un número válido.';
                die();
            }
        } elseif (Sesion::existeSesion()) {
            // Si no se proporciona, pero hay una sesión activa, obtener el idUsuario de la sesión
            $idUsuario = Sesion::getUsuario()->getId();
        } else {
            $idUsuario = 0;
        }
    
        $tareasDAO = new TareasDAO($conn);
        $tareas = $tareasDAO->obtenerLasTareasUsuario($idUsuario);

        require 'app/vistas/inicio.php';
    }
    

    public function borrar(){
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $tareasDAO = new TareasDAO($conn);

        $idTarea = htmlentities($_POST['idTarea']);
        $fotosDAO=new FotosDAO($conn);
        $fotos=$fotosDAO->getAllByIdTarea($idTarea);
        for ($i=0; $i < count($fotos); $i++) { 
            $fotosDAO->delete($fotos[$i]);
        }
        if($tarea = $tareasDAO->borrarTarea($idTarea)){
            print json_encode(['respuesta'=>'ok']);
        }else{
            print json_encode(['respuesta'=>'error', 'mensaje'=>'Tarea no encontrada']);
        }


    }

    public function irAEditar(){
        $idTarea = $_GET['idTarea'];
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();
        $tareasDAO = new TareasDAO($conn);
        $tarea = $tareasDAO->obtenerTareaPorID($idTarea);
        $fotosDAO= new FotosDAO($conn);
        if($fotosDAO->getAllByIdTarea($idTarea)){
            $fotos =  $fotosDAO->getAllByIdTarea($idTarea);
        }else{
            $fotos=false;
        }
        include('app/vistas/editarTarea.php');  
    }

    public function editar(){
        $error ='';

        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();
        $idTarea = htmlspecialchars($_POST['idTarea']);
        $tareasDAO = new TareasDAO($conn);
        $tarea = $tareasDAO->obtenerTareaPorID($idTarea);

        $fotosDAO = new FotosDAO($conn);
        $fotos = $fotosDAO->getAllByIdTarea($idTarea);

        if($_SERVER['REQUEST_METHOD']=='POST'){

            $fecha = htmlspecialchars($_POST['fecha']);
            $texto = htmlspecialchars($_POST['texto']);
            $idUsuario = Sesion::getUsuario()->getId();

            if(empty($texto)){
                $error = "Los campos son obligatorios";
            }
            else{
                $tarea->setFecha($fecha);
                $tarea->setTexto($texto);

                $tareasDAO->update($tarea);
                header('location: index.php');
                die();
            }

        }
        
            require 'app/vistas/editarTarea.php';
    }

    public function insertar(){
        $error ='';

        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $usuariosDAO = new UsuariosDAO($conn);
        $usuarios = $usuariosDAO->getAll();

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $texto =  htmlspecialchars($_POST['texto']);

            if(empty($texto)){
                $error = "Los campos son obligatorios";
            }
            else{
                $tareasDAO = new TareasDAO($conn);
                $tarea = new Tarea();
                $tarea->setTexto($texto);
                $tarea->setIdUsuario(Sesion::getUsuario()->getId());
                $tarea=$tareasDAO->insertar($tarea);
                $tareaArray=$tarea->toArray();
                print json_encode($tareaArray);
            }
        }
    }

    function addImageTarea(){
        $idTarea = htmlentities($_GET['idTarea']);
        $nombreArchivo = htmlentities($_FILES['foto']['name']);
        $informacionPath = pathinfo($nombreArchivo);
        $extension = $informacionPath['extension'];
        $nombreArchivo = md5(time()+rand()) . '.' . $extension;
        move_uploaded_file($_FILES['foto']['tmp_name'],"web/imagenes/$nombreArchivo");
        
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();
        $fotosDAO = new FotosDAO($conn);
        $foto = new Foto();
        $foto->setIdTarea($idTarea);
        $foto->setNombreArchivo($nombreArchivo);
        $fotosDAO->insert($foto);
        print json_encode(['respuesta'=>'ok', 'nombreArchivo'=> $nombreArchivo]);

    }
}