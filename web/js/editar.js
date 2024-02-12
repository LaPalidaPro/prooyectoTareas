// Obtener el formulario y el ID de la tarea
let idTarea = document.getElementById('formularioEditar').getAttribute('data-idTarea');

// Escuchar el evento de cambio en el campo de archivo
document.getElementById('inputFileImage').addEventListener('change', function() {
    // Crear un objeto FormData para enviar los datos
    let formData = new FormData();
    formData.append('imagen', this.files[0]); // Agregar la imagen al formulario

    // Enviar la solicitud fetch al servidor
    fetch('index.php?accion=anadirImagenTarea&id=' + idTarea, {
        method: 'POST',
        body: formData // Adjuntar el formulario con la imagen al cuerpo de la solicitud
    })
    .then(respuesta => respuesta.json())
.then(data => {
    console.log(data); // Puedes imprimir la respuesta para asegurarte de que sea lo que esperas
    document.getElementById('imagenTarea').setAttribute("src", 'web/images/' + data.nombreArchivo);
})
.catch(error => {
    console.error('Error al procesar la respuesta:', error);
});
 
});
