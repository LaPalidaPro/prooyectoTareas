<?php
require_once 'app/utils/ConnexionDB.php';

class TareasDAO {
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function obtenerTodasLasTareas() {
        $query = "SELECT * FROM tareas";
        $resultados = $this->conn->query($query);
        $tareas = array();

        if ($resultados->num_rows > 0) {
            while ($tarea = $resultados->fetch_object(Tarea::class)) {
                $tareas[] = $tarea;
            }
        }

        return $tareas;
    }
    public function obtenerTareasPorIdUsuario($usuarioId) {
        $query = "SELECT * FROM tareas WHERE idUsuario = ?";
        
        // Utilizar una sentencia preparada para prevenir SQL injection
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        
        $resultados = $stmt->get_result();
        $tareas = array();
    
        if ($resultados->num_rows > 0) {
            while ($tarea = $resultados->fetch_object(Tarea::class)) {
                $tareas[] = $tarea;
            }
        }
    
        $stmt->close();
    
        return $tareas;
    }
    

    public function insertarTarea2(Tarea $tarea) {
        // Preparar la consulta SQL con marcadores de posición
        $query = "INSERT INTO tareas (texto, estado, idUsuario) VALUES (?, ?, ?)";
        $texto = $tarea->getTexto();
        $estado = $tarea->getEstado();
        $usuarioId = $tarea->getIdUsuario();
        // Preparar la sentencia SQL
        $stmt = $this->conn->prepare($query);
    
        // Vincular los parámetros con los valores de la tarea
        $stmt->bind_param("ssi", $texto, $estado, $usuarioId);
    
        // Ejecutar la consulta preparada
        if ($stmt->execute()) {
            // Obtener el ID insertado
            $idInsertado = $stmt->insert_id;
    
            // Obtener la nueva tarea
            $nuevaTarea = $this->obtenerTareaPorID($idInsertado);
    
            // Cerrar la sentencia
            $stmt->close();
    
            return $nuevaTarea;
        } else {
            // Cerrar la sentencia en caso de error
            $stmt->close();
    
            return null;
        }
    }
    

    public function obtenerTareaPorID($id) {
        $query = "SELECT * FROM tareas WHERE id = $id";
        $resultado = $this->conn->query($query);

        if ($resultado->num_rows > 0) {
            $tarea = $resultado->fetch_object(Tarea::class);
            
            return $tarea;
        } else {
            return null;
        }
    }

    public function cerrarConexion() {
        $this->conn->close();
    }


    public function borrarTarea($id) {
        $id = filter_var($id,FILTER_SANITIZE_NUMBER_INT);
        $query = "delete from tareas where id=$id";
        
        $this->conn->query($query);
        if($this->conn->affected_rows==1){
            return true;
        } else {
            return false;
        }
    }
    function update($tarea){
        if (!$stmt = $this->conn->prepare("UPDATE tareas SET texto=?, estado=?,foto=? WHERE id=?")) {
            die("Error al preparar la consulta update: " . $this->conn->error);
        }
    
        $texto = $tarea->getTexto();
        $estado = $tarea->getEstado();
        $id = $tarea->getId();
        $foto = $tarea->getFoto();
    
        // Utiliza 'ssi' en lugar de 'iss'
        $stmt->bind_param('sssi', $texto, $estado,$foto, $id);
    
        return $stmt->execute();
    }
}
?>
