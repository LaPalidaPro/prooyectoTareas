<?php
require_once 'app/modelos/Tarea.php';
require_once 'app/modelos/TareasDAO.php';
require_once 'app/utils/Sesion.php';
require_once 'app/controladores/ControladorTareas.php';
require_once 'app/controladores/ControladorUsuarios.php';
//Uso de variables de sesión


//Mapa de enrutamiento
$mapa = array(
    'inicio' => array(
        "controlador" => 'ControladorTareas',
        'metodo' => 'inicio',
        'privada' => false
    ),
    'login' => array(
        'controlador' => 'ControladorUsuarios',
        'metodo' => 'login',
        'privada' => false
    ),
    'logout' => array(
        'controlador' => 'ControladorUsuarios',
        'metodo' => 'logout',
        'privada' => true
    ),
    'registrar' => array(
        'controlador' => 'ControladorUsuarios',
        'metodo' => 'registrar',
        'privada' => false
    ),
    'insertar_tarea' => array(
        'controlador' => 'ControladorTareas',
        'metodo' => 'insertar',
        'privada' => true
    ),
    'borrar' => array(
        'controlador' => 'ControladorTareas',
        'metodo' => 'borrar',
        'privada' => true
    ),
    'ver_tareas' => array(
        'controlador' => 'ControladorTareas',
        'metodo' => 'ver_tareas',
        'privada' => true
    ),
    'tareaEstado' => array(
        'controlador' => 'ControladorTareas',
        'metodo' => 'tareaEstado',
        'privada' => false,
    ),
    'editarTarea' => array(
        'controlador' => 'ControladorTareas',
        'metodo' => 'editar',
        'privada' => true
    ),
    'anadirImagenTarea' => array(
        'controlador' => 'ControladorTareas',
        'metodo' => 'anadirImagenTarea',
        'privada' => true,
    ),
    'borrarImagenTarea' => array(
        'controlador' => 'ControladorTareas',
        'metodo' => 'borrarImagenTarea',
        'privada' => true,
    ),

);

//Parseo de la ruta
if (isset($_GET['accion'])) { //Compruebo si me han pasado una acción concreta, sino pongo la accción por defecto inicio
    if (isset($mapa[$_GET['accion']])) {  //Compruebo si la accción existe en el mapa, sino muestro error 404
        if (Sesion::existeSesion() == true) {
            $usuario = Sesion::getUsuario();
        } else {
            print ' La sesion no ha sido iniciada correctamente';
        }

        $accion = $_GET['accion'];
    } else {
        print $_GET['accion'];
        //La acción no existe
        header('Status: 404 Not found');
        echo 'Página no encontrada';
        die();
    }
} else {
    $accion = 'inicio';   //Acción por defecto
}

//Si existe la cookie y no ha iniciado sesión, le iniciamos sesión de forma automática
//if( !isset($_SESSION['email']) && isset($_COOKIE['sid'])){
if (!Sesion::existeSesion() && isset($_COOKIE['sid'])) {
    //Conectamos con la bD
    $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
    $conn = $connexionDB->getConnexion();

    //Nos conectamos para obtener el id y la foto del usuario
    $usuariosDAO = new UsuariosDAO($conn);
    if ($usuario = $usuariosDAO->getBySid($_COOKIE['sid'])) {
        //$_SESSION['email']=$usuario->getEmail();
        //$_SESSION['id']=$usuario->getId();
        //$_SESSION['foto']=$usuario->getFoto();
        Sesion::iniciarSesion($usuario);
    }
}

//Si la acción es privada compruebo que ha iniciado sesión, sino, lo echamos a index
// if(!isset($_SESSION['email']) && $mapa[$accion]['privada']){
if (!Sesion::existeSesion() && $mapa[$accion]['privada']) {
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
