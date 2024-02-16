<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edita tu tarea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Agregar Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="web/css/estilos.css">
    <style>
        /* Estilos personalizados */
        .imagen-container {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .imagen-container img {
            max-width: 200px; /* Tamaño máximo ajustable */
            height: auto;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h1 class="text-center">Edita tu tarea</h1>
                <a id="volver" href="index.php?accion=ver_tareas">Volver</a>
                <?php imprimirMensaje(); ?>
                <form action="index.php?accion=editarTarea&idTarea=<?= $idTarea ?>" method="post" data-idTarea="<?= $idTarea ?>" id="formularioEditar" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="texto" class="form-label"><h5>Texto del mensaje:</h5></label>
                        <textarea id="texto" name="texto" class="form-control" placeholder="Texto"><?= $tarea->getTexto() ?></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="imagen-container">
                            <!-- Aquí se mostrará la imagen -->
                            <img id="imagenTarea" src="web/images/<?= $tarea->getFoto() ?>" class="imagenTarea" alt="no hay imagen">
                        </div>
                        
                        
                        <div class="botones">
                           
                            <i class="fas fa-pencil-alt pencil"></i> 
                            <input type="file" id="inputFileImage" name="imagen" style="display: none;">
                                <?php 
                        if($tarea->getFoto()){
                            echo '<i class="fa-solid fa-trash papeleraImg"></i>';
                        }
                        ?>
                          
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btnRegistro"> <a id="volver" href="index.php?accion=ver_tareas"></a></a>Editar</button>
                       
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="web\js\editar.js"></script>

    <!-- Agregar Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
