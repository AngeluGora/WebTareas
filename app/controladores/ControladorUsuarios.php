<?php
require_once 'app/config/config.php';

class ControladorUsuarios {
    public function registrar() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Limpiamos los datos
            $email = htmlentities($_POST['email']);
            $password = htmlentities($_POST['password']);
            $nombre = htmlentities($_POST['nombre']);

            // Validación

            // Conectamos con la BD
            $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            // Comprobamos que no haya un usuario registrado con el mismo email
            $usuariosDAO = new UsuariosDAO($conn);
            if ($usuariosDAO->getByEmail($email) !== null) {
                $error = "Ya hay un usuario con ese email";
            } else {

                // Si no hay error
                // Insertamos en la BD
                $usuario = new Usuario();
                $usuario->setEmail($email);
                // Encriptamos el password
                $passwordCifrado = password_hash($password, PASSWORD_DEFAULT);
                $usuario->setPassword($passwordCifrado);
                $usuario->setNombre($nombre);

                if ($usuariosDAO->insert($usuario)) {
                    header("location: index.php");
                    die();
                } else {
                    $error = "No se ha podido insertar el usuario";
                }
            }
        } // Fin del if ($_SERVER['REQUEST_METHOD'] == 'POST')

        require 'app/vistas/registrar.php';
    } // Fin de la función registrar()

    public function login() {
        // Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();
    
        // Limpiamos los datos que vienen del usuario
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
    
        // Validamos el usuario
        $usuariosDAO = new UsuariosDAO($conn);
        $usuario = $usuariosDAO->getByEmail($email);
    
        if ($usuario !== null && password_verify($password, $usuario->getPassword())) {
            // Email y password correctos. Iniciamos sesión
            Sesion::iniciarSesion($usuario);
    
            // Creamos la cookie para que nos recuerde 1 semana
            setcookie('sid', $usuario->getSid(), time() + 24 * 60 * 60, '/');
    
            // Redirigimos a la página de inicio si hay una sesión activa
            if (Sesion::existeSesion()) {
                header('location: index.php?accion=inicio');
            } else {
                // Redirigimos a index.php si no hay sesión activa
                header('location: index.php');
            }
            die();
        }
    
        // Email o password incorrectos, redirigir a index.php
        guardarMensaje("Email o password incorrectos");
    
        // Redirigir a la página de login si no hay sesión activa
        if (!Sesion::existeSesion()) {
            header('location: index.php');
            die();
        }
    }
    

    public function logout() {
        Sesion::cerrarSesion();
        header('location: index.php');
    }
}
