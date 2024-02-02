<?php 

require_once 'app/config/config.php';
require_once 'app/modelos/ConexionDB.php';
require_once 'app/modelos/Tarea.php';
require_once 'app/modelos/TareasDAO.php';
require_once 'app/modelos/Usuario.php';
require_once 'app/modelos/UsuariosDAO.php';
require_once 'app/modelos/Tick.php';
require_once 'app/modelos/TickDAO.php';
require_once 'app/modelos/Foto.php';
require_once 'app/modelos/FotosDAO.php';
require_once 'app/controladores/ControladorTareas.php';
require_once 'app/controladores/ControladorUsuarios.php';
require_once 'app/controladores/ControladorTick.php';
require_once 'app/utils/funciones.php';
require_once 'app/modelos/Sesion.php';

session_start();

$mapa = array(
    'inicio'=>array("controlador"=>'ControladorTareas',
                    'metodo'=>'inicio',
                    'privada'=>false),
    'insertarTarea'=>array('controlador'=>'ControladorTareas',
                            'metodo'=>'insertar', 
                            'privada'=>true),
    'borrarTarea'=>array('controlador'=>'ControladorTareas',
                            'metodo'=>'borrar', 
                            'privada'=>true),
    'editarTarea'=>array('controlador'=>'ControladorTareas',
                            'metodo'=>'editar', 
                            'privada'=>true),
    'irALogin'=>array('controlador'=>'ControladorUsuarios', 
                    'metodo'=>'irALogin', 
                    'privada'=>false),
    'login'=>array('controlador'=>'ControladorUsuarios', 
                    'metodo'=>'login', 
                    'privada'=>false),
    'logout'=>array('controlador'=>'ControladorUsuarios', 
                    'metodo'=>'logout', 
                    'privada'=>true),
    'registrar'=>array('controlador'=>'ControladorUsuarios', 
                        'metodo'=>'registrar', 
                        'privada'=>false),
    'marcarComoCompletada'=>array('controlador'=>'ControladorTick', 
                        'metodo'=>'marcarComoCompletada', 
                        'privada'=>false),                       
    'desmarcarComoCompletada'=>array('controlador'=>'ControladorTick', 
                        'metodo'=>'desmarcarComoCompletada', 
                        'privada'=>false),
    'addImageTarea'=>array('controlador'=>'ControladorMensajes', 
                        'metodo'=>'addImageTarea', 
                        'privada'=>false),
);



//Parseo de la ruta
if (isset($_GET['accion'])) {
    if (isset($mapa[$_GET['accion']])) {
        $accion = $_GET['accion'];
    } else {
        // La acción no existe
        header('Status: 404 Not found');
        echo 'Página no encontrada';
        die();
    }
} else if (Sesion::existeSesion()) {
    if (!$idUsuario) {
        // Obtener el ID del usuario desde la sesión y redirigir a index.php con el ID
        $idUsuario = Sesion::getUsuario()->getId();
        header("location: index.php?accion=inicio&idUsuario=$idUsuario");
        die();
    }

    } else {
        // Si no hay una sesión activa, redirige a la página de login
        $accion = 'irALogin';
    }


//Si existe la cookie y no ha iniciado sesión, le iniciamos sesión de forma automática
//if( !isset($_SESSION['email']) && isset($_COOKIE['sid'])){
if( !Sesion::existeSesion() && isset($_COOKIE['sid'])){
    //Conectamos con la bD
    $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
    $conn = $connexionDB->getConnexion();
    
    //Nos conectamos para obtener el id y la foto del usuario
    $usuariosDAO = new UsuariosDAO($conn);
    if($usuario = $usuariosDAO->getBySid($_COOKIE['sid'])){
        //$_SESSION['email']=$usuario->getEmail();
        //$_SESSION['id']=$usuario->getId();
        //$_SESSION['foto']=$usuario->getFoto();
        Sesion::iniciarSesion($usuario);
    }
    
}

//Si la acción es privada compruebo que ha iniciado sesión, sino, lo echamos a index
// if(!isset($_SESSION['email']) && $mapa[$accion]['privada']){
if(!Sesion::existeSesion() && $mapa[$accion]['privada']){
    header('location: index.php');
    guardarMensaje("Debes iniciar sesión para acceder a $accion");
    die();
}


//$acción ya tiene la acción a ejecutar, cogemos el controlador y metodo a ejecutar del mapa
$controlador = $mapa[$accion]['controlador'];
$metodo = $mapa[$accion]['metodo'];

//Ejecutamos el método de la clase controlador
$objeto = new $controlador();
$objeto->$metodo();