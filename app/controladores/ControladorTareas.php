<?php
require_once 'app/utils/ConnexionDB.php';
require 'app/config/config.php';
require_once 'app/utils/Sesion.php';



class ControladorTareas

{

    public function ver()
    {
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //Creamos el objeto TareasDAO para acceder a BBDD a través de este objeto
        $tareaDAO = new TareasDAO($conn);

        try {
            //Obtener la tarea
            $idTarea = htmlspecialchars($_GET['id']);
            $tarea = $tareaDAO->obtenerTareaPorID($idTarea);

            require 'app/vistas/ver_mensaje.php';
        } catch (Exception) {
        }
    }

    public function inicio()
    {

        //Incluyo la vista
        require 'app/vistas/login.php';
    }
    public function ver_tareas()
    {

        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //Creamos el objeto tareasDAO para acceder a BBDD a través de este objeto
        $usuario = Sesion::getUsuario();
        $idUsuario = $usuario->getId();
        $tareasDAO = new TareasDAO($conn);
        $tareas = $tareasDAO->obtenerTareasPorIdUsuario($idUsuario);

        //Incluyo la vista
        require 'app/vistas/tareas.php';
    }

    public function borrar()
    {
        // Conectamos con la BD
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();
        $idTarea = htmlentities($_GET['id']);

        $tareasDAO = new TareasDAO($conn);
        if ($tarea = $tareasDAO->borrarTarea($idTarea)) {
            print json_encode(['respuesta' => 'ok']);
        } else {
            print json_encode(['respuesta' => 'error', 'mensaje' => 'Tarea no encontrada']);
        }

        // Detenemos la ejecución 1 segundo para simular que el servidor tarda 1 segundo en responder
        sleep(1);
        // Enviamos la redirección antes de enviar la respuesta JSON

        die();
    }
    public function borrarImagenTarea()
    {
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        if ($idTarea = $_GET['id']) {
            $tareasDAO = new TareasDAO($conn);
            $tarea = $tareasDAO->obtenerTareaPorID($idTarea);
            $nombreImagen = $tarea->getFoto();
            $rutaImagen = "web/images/" . $nombreImagen;

            if (unlink($rutaImagen)) {
                guardarMensaje('La imagen se ha eliminado correctamente');
                print json_encode(['respuesta' => 'ok']);
            }else{
                guardarMensaje('La imagen no se ha eliminado correctamente');
            }
        }else{
            guardarMensaje( 'No se proporcionó un ID válido');
        }
    }
    public function verEditar()
    {
        require 'app/vistas/editarTarea.php';
    }

    public function editar()
    {
        $error = '';


        //Conectamos con la bD
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //Obtengo el id del tarea que viene por GET
        $idTarea = htmlspecialchars($_GET['idTarea']);
        //Obtengo el tarea de la BD
        $tareasDAO = new TareasDAO($conn);
        $tarea = $tareasDAO->obtenerTareaPorID($idTarea);

        //Obtengo la foto de la BD
        $tareasDAO = new TareasDAO($conn);
        $tarea = $tareasDAO->obtenerTareaPorID($idTarea);
        $fotos = $tarea->getFoto();

        //Cuando se envíe el formulario actualizo el tarea en la BD
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            //Limpiamos los datos que vienen del usuario

            $texto = htmlspecialchars($_POST['texto']);
            $idUsuario = Sesion::getUsuario()->getId();

            //Validamos los datos
            if (empty($texto)) {
                $error = "El texto no se ha cambiado";
            } else {
            }
        } //if($_SERVER['REQUEST_METHOD']=='POST'){

        require 'app/vistas/editarTarea.php';
    }


    public function insertar()
    {
        $error = '';

        try {
            // Creamos la conexión utilizando la clase que hemos creado
            $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Limpiamos los datos que vienen del usuario
                $texto = htmlspecialchars($_POST['texto']);

                // Validamos los datos
                if (empty($texto)) {
                    $error = "El campo de texto es obligatorio.";
                } else {

                    // Creamos el objeto TareasDAO para acceder a la BBDD a través de este objeto
                    $tareasDAO = new TareasDAO($conn);

                    // Creamos el objeto Tarea
                    $tarea = new Tarea();
                    $tarea->setTexto($texto);
                    $tarea->setIdUsuario(Sesion::getUsuario()->getId()); // El id del usuario conectado (en la sesión)


                    // Insertamos la tarea
                    $tareasDAO->insertarTarea2($tarea);

                    // Redirigimos a la página de inicio
                    header('location: index.php?accion=ver_tareas');
                    exit; // Terminamos el script después de la redirección
                }
            }
        } catch (PDOException $e) {
            // Manejar la excepción (mostrar mensaje, registrar, etc.)
            $error = "Error en la conexión a la base de datos: " . $e->getMessage();
        } finally {
            // Cerrar la conexión aquí, independientemente de si hay una excepción o no
            if ($conn !== null) {
                $conn = null;
            }
        }

        // Incluimos la vista con la variable $error
        require 'app/vistas/tareas.php';
    }
    function anadirImagenTarea()
    {
        // Validar la existencia de la variable $_FILES['imagen']
        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            // Manejar el error relacionado con la carga del archivo
            exit();
        }

        // Obtener el ID de la tarea de la solicitud
        $idTarea = htmlentities($_GET['id']);

        // Validar la existencia del ID de la tarea
        if (empty($idTarea)) {
            // Manejar el error si el ID de la tarea no está presente
            exit();
        }

        // Obtener el nombre del archivo de la imagen cargada
        $nombreArchivo = htmlentities($_FILES['imagen']['name']);

        // Validar la existencia del nombre de archivo
        if (empty($nombreArchivo)) {
            // Manejar el error si el nombre de archivo está vacío
            exit();
        }

        // Obtener la extensión del archivo
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        // Validar el tipo de archivo permitido
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($extension, $extensionesPermitidas)) {
            guardarMensaje("El tipo de archivo no esta permitido");
            exit();
        }
        // Generar un nombre de archivo único para evitar conflictos
        $nombreArchivoUnico = md5(uniqid() . time()) . '.' . $extension;
        // Mover el archivo cargado a la carpeta de destino
        $carpetaDestino = "web/images/";
        move_uploaded_file($_FILES['imagen']['tmp_name'], $carpetaDestino . $nombreArchivoUnico);
        // Conectar a la base de datos
        $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
        $conn = $connexionDB->getConnexion();
        // Obtener la tarea por su ID
        $tareasDAO = new TareasDAO($conn);
        $tarea = $tareasDAO->obtenerTareaPorID($idTarea);
        // Actualizar la tarea con el nombre del archivo de la imagen
        $tarea->setFoto($nombreArchivoUnico);
        $tareasDAO->update($tarea);
        // Devolver una respuesta JSON con el nombre del archivo subido
        header('Content-Type: application/json');
        print json_encode(['respuesta' => 'ok', 'nombreArchivo' => $nombreArchivoUnico]);
        exit();
    }




    function tareaEstado()
    {
        try {
            $connexionDB = new ConnexionDB(MYSQL_USER, MYSQL_PASS, MYSQL_HOST, MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            $idTarea = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;

            if (!$idTarea) {
                print json_encode(['respuesta' => 'error', 'mensaje' => 'ID de tarea no proporcionado']);
                return;
            }

            $tareasDAO = new TareasDAO($conn);
            $tarea = $tareasDAO->obtenerTareaPorID($idTarea);

            if (empty($tarea)) {
                print json_encode(['respuesta' => 'error', 'mensaje' => 'No se ha encontrado la tarea']);
            } else {
                $nuevoEstado = $tarea->getEstado() === "No realizada" ? "Realizada" : "No realizada";
                $tarea->setEstado($nuevoEstado);
                $tareasDAO->update($tarea);

                print json_encode(['respuesta' => 'ok', 'nuevoEstado' => $nuevoEstado]);

                exit;
            }
        } catch (Exception $e) {
            print json_encode(['respuesta' => 'error', 'mensaje' => $e->getMessage()]);
        }
    }
}
