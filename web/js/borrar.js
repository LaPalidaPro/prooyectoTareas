let papeleras = document.querySelectorAll('.papelera');
papeleras.forEach(papelera => {
    papelera.addEventListener('click', manejadorBorrar);
});


function manejadorBorrar() {
    // this referencia al elemento del DOM sobre el que hemos hecho click
    let idTarea = this.getAttribute('data-idTarea');
    // Mostramos preloader
    let preloader = this.parentElement.querySelector('img');
    preloader.style.visibility = "visible";
    this.style.visibility = 'hidden';
    // Guardamos la referencia a this para poder usarla dentro de la función finally
    let self = this;


    // Llamamos al script del servidor que borra la tarea pasándole el idTarea como parámetro
    fetch('index.php?accion=borrar&id=' + idTarea)
        .then(datos => datos.json())

        .then(respuesta => {
            console.log("Respuesta del servidor:", respuesta);
            if (respuesta.respuesta == 'ok') {
                
                self.closest('.card').remove();
               
                console.log("Tarea eliminada con ID:", idTarea);
            } else {
                console.log("Tarea no eliminada con ID:", idTarea);
                alert("No se ha encontrado la tarea en el servidor");
                self.style.visibility = 'visible';
            }
        })
        .finally(() => {
            // Ocultamos preloader
            preloader.style.visibility = "hidden";
            this.style.visibility = 'visible';
        });
}