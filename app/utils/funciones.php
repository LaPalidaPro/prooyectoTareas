<?php 
/**
 * Genera un hash aleatorio para un nombre de arhivo manteniendo la extensión original
 */
function generarNombreArchivo(string $nombreOriginal):string {
    $nuevoNombre = md5(time()+rand());
    $partes = explode('.',$nombreOriginal);
    $extension = $partes[count($partes)-1];
    return $nuevoNombre.'.'.$extension;
}

function guardarMensaje($mensaje){
    $_SESSION['error']=$mensaje;
}
function guardarMensajeLogin($mensaje){
    $_SESSION['login-validaciones']=$mensaje;
}
function imprimirMensajeLogin(){
    if(isset($_SESSION['login-validaciones'])){
        echo '<div class="login-validaciones">'.$_SESSION['login-validaciones'].'</div>';
        unset($_SESSION['login-error']);
    } 
}
function imprimirMensaje(){
    if(isset($_SESSION['error'])){
        echo '<div class="error" id="mensajeError">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    } 
}

