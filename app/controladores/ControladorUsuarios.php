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
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $usuario->setPassword($hashed_password);
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
    } 

    public function irALogin() {
        // Verificar si ya hay una sesión activa
        if (Sesion::existeSesion()) {
            // Si hay una sesión activa, redirige a la página de inicio
            header('location: index.php?accion=inicio');
            die();
        }

        // Si no hay una sesión activa, muestra la vista del formulario de login
        include('app/vistas/login.php');  
    }

    public function login() {
        // Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();
    
        // Verificar si se han enviado datos por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Limpiamos los datos que vienen del usuario
            $email = htmlspecialchars($_POST['email']);
            $password =htmlspecialchars($_POST['password']);
    
            // Validamos el usuario
            if ($email && $password) {
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
                        die();
                    }
                } else {
                    // Email o password incorrectos, mostrar mensaje de error en la página de login
                    $_SESSION['mensaje_error'] = "Email o password incorrectos";
                }
            } else {
                // Datos de POST incompletos, mostrar mensaje de error en la página de login
                $_SESSION['mensaje_error'] = "Por favor, ingrese tanto el email como la contraseña.";
            }
        }
    
        // Redirigir a la página de login en cualquier caso
        header('location: index.php');
        die();
    }
    

    public function logout() {
        Sesion::cerrarSesion();
        header('location: index.php');
    }
}
