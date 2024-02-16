// Obtener el formulario y el ID de la tarea
let idTarea = document.getElementById('formularioEditar').getAttribute('data-idTarea');
let iconoLapiz = document.querySelector('.pencil');

// Obtener el elemento de entrada de archivo
let inputFileImage = document.getElementById('inputFileImage');

// Agregar un evento de clic al icono lápiz
iconoLapiz.addEventListener('click', function() {
    // Hacer clic en el elemento de entrada de archivo
    inputFileImage.click();
});
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
    let imagenTarea = document.getElementById('imagenTarea')
    if (imagenTarea==null) {
        imagenTarea = document.createElement('img');
        imagenTarea.id = 'imagenTarea';
        // Agregar clases y atributos necesarios
        imagenTarea.classList.add('imagenTarea');
        imagenTarea.alt = 'Imagen de tarea';
        // Agregar la imagen al contenedor
        document.querySelector('.imagen-container').appendChild(imagenTarea);
        imagenTarea.setAttribute("src", 'web/images/' + data.nombreArchivo);
        papelera.style.visibility = "visible";
    } else {
        imagenTarea.setAttribute("src", 'web/images/' + data.nombreArchivo);
        papelera.style.visibility = "visible";
        
    }
})
.catch(error => {
    console.error('Error al procesar la respuesta:', error);
});
 
});

let papelera = document.querySelector('.papeleraImg');

// Agrega un evento 'click' al elemento seleccionado
papelera.addEventListener("click", function () {
    // Obtén el ID de la tarea asociado con este elemento
    let idTarea = document.getElementById('formularioEditar').getAttribute('data-idTarea');

    // Realiza la petición fetch
    fetch('index.php?accion=borrarImagenTarea&id=' + idTarea, {
        method: 'POST',
        
    })
    .then(respuesta => respuesta.json())
    .then(data => {
        console.log(data); // Puedes imprimir la respuesta para asegurarte de que sea lo que esperas
        papelera.style.visibility = "hidden";
        let imagenTarea = document.getElementById('imagenTarea');
        imagenTarea.parentNode.removeChild(imagenTarea);

    })
    .catch(error => {
        console.error('Error al procesar la respuesta:', error);
    });
});
