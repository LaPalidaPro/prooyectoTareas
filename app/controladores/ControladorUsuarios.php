<?php 
require_once 'app/modelos/UsuariosDAO.php';
require_once 'app/modelos/Usuario.php';
require_once 'app/utils/funciones.php';
require_once 'app/utils/Sesion.php';
session_start();

Class ControladorUsuarios{
    public function registrar() {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Limpiamos los datos
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);

            // Validación

            // Conectamos con la BD
            $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            // Compruebo que no haya un usuario registrado con el mismo email
            $usuariosDAO = new UsuariosDAO($conn);
            if ($usuariosDAO->getByEmail($email) !== null) {
                guardarMensajeLogin("* Ya hay un usuario con ese email");
            }

            if ($error === '') { // Si no hay error
                // Insertamos en la BD
                $usuario = new Usuario();
                $usuario->setEmail($email);
                $usuario->setNombre($nombre);
                // Encriptamos el password
                $passwordCifrado = password_hash($password, PASSWORD_DEFAULT);
                $usuario->setPassword($passwordCifrado);
                $usuario->setSid(sha1(rand() + time()), true);

                if ($usuariosDAO->insert($usuario)) {
                    header("location: index.php");
                    guardarMensajeLogin("* Registro realizado correctamente", "success");
                    die();
                } else {
                    guardarMensajeLogin("* Error al insertar el usuario en la base de datos", "danger");
                }
            }
        }

        header('location: index.php');
    }

    public function login() {
        // Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        // Limpiamos los datos que vienen del usuario
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        // Validamos el usuario
        $usuariosDAO = new UsuariosDAO($conn);
        $usuario = $usuariosDAO->getByEmail($email);
        //password_verify($password, $usuario->getPassword())
        if ($usuario && password_verify($password, $usuario->getPassword())) {
            // Email y password correctos. Iniciamos sesión
            Sesion::iniciarSesion($usuario);
            // Creamos la cookie para que nos recuerde 1 semana
            setcookie('sid', $usuario->getSid(), time() + 24 * 60 * 60, '/');

            // Redirigimos a index.php?accion=inicio
            header('location: index.php?accion=ver_tareas');
            die();
        } else {
            // Email o password incorrectos, redirigir a index.php
            if(Sesion::cerrarSesion()){
                guardarMensajeLogin("* La sesion se ha cerrado");
                guardarMensaje("* La sesion se ha cerrado");
            }
            guardarMensajeLogin("* Email o password incorrectos ");
            header('location: index.php');
        }
    }

    public function logout(){
        Sesion::cerrarSesion();
        setcookie('sid','',0,'/');
        header('location: index.php');
    }

}