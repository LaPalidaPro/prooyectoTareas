<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Utiliza solo Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="web/css/estilos.css">
</head>

<body>

    <div class="container mt-4">
        
        <?php 

        if (Sesion::existeSesion()) {
            echo "<h3 id='mensajeBienvenida'>¡Bienvenid@" . " " . $usuario->getNombre() . "!</h3>";
        $usuario = Sesion::getUsuario();
        echo '<a id="btnCerrarSesion" href="?cerrar_sesion=true">Cerrar Sesión</a>';
            // Imprimir información del usuario
            
        ?>
            <h1>Tu lista de Tareas</h1>

            <!-- Lista de tareas -->
            <div id="tareas">
                <?php foreach ($tareas as $tarea) : ?>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="container">
                            <img id="imagenT" src="web/images/<?= $tarea->getFoto() ?>" alt="">
                            <p class="card-text" style="<?= $tarea->getEstado() === 'Realizada' ? 'text-decoration: line-through; opacity: 0.3;' : '' ?>">
                                <?= $tarea->getTexto() ?>
                            </p>
                            </div>
                         
                            <i class="fa-solid fa-trash papelera" data-idTarea="<?= $tarea->getId() ?>"></i>
                            <img src="app/vistas/preloader.gif" class="preloaderBorrar" alt="prelo">
                            <a href="index.php?accion=editarTarea&idTarea=<?php echo $tarea->getId(); ?>" id="anadirImagenTarea">
                                <i class="fa-solid fa-pen-to-square editar"></i>
                            </a>
                            <?php if ($tarea->getEstado() === 'Realizada') : ?>
                                <i class="fa-solid fa-circle-check tick" data-idTarea="<?= $tarea->getId() ?>"></i>
                            <?php else : ?>
                                <i class="fa-regular fa-circle-check tick" data-idTarea="<?= $tarea->getId() ?>"></i>

                            <?php endif; ?>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>

            <!-- Formulario para agregar nueva tarea -->
            <div class="mt-3">
                <form action="index.php?accion=insertar_tarea" method="post" id="agregarTareaForm">
                    <input name="texto" type="text" class="form-control" id="nuevaTarea" placeholder="Nueva Tarea" required>
                    <button type="submit" class="btn btn-primary mt-2" id="botonNuevaTarea">Agregar</button>
                    <img src="app/vistas/preloader.gif" id="preloaderInsertar" alt="prelo">


                </form>
            </div>

        <?php
        } else {
            echo "No hay usuario en la sesión";
        }
        ?>
    </div>

    <script src="web/js/js.js" type="text/javascript"></script>
    <script src="web/js/borrar.js"></script>
    <script src="web/js/tachar.js"></script>
</body>

</html>