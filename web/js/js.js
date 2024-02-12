
//insertar tareaNueva
let botonInsertar = document.getElementById('botonNuevaTarea');

//document.querySelector('.papelera')

botonInsertar.addEventListener('click', function () {
    //Muestro el preloader
    document.getElementById('preloaderInsertar').style.visibility = 'visible';

    //Envío datos mediante POST a insertar.php construyendo un FormData
    const datos = new FormData();
    datos.append('texto', document.getElementById('nuevaTarea').value);

    const options = {
        method: "POST",
        body: datos
    };

    fetch('insertar.php', options)
        .then(respuesta => {
            return respuesta.json();
        })
        .then(tarea => {
            //console.log(tarea);
            //Añado la la tarea al div "tareas" modificando el DOM
            var capaTarea = document.createElement('div');
            var capaTexto = document.createElement('div');
            var papelera = document.createElement('i');
            var preloader = document.createElement('img');
            var  tick = document.createElement('i');
            var tickMarcado = document.createElement('i');
            capaTarea.classList.add('tarea');
            capaTexto.classList.add('texto');
            capaTexto.innerHTML = tarea.texto;
            papelera.classList.add('fa-solid', 'fa-trash', 'papelera');
            papelera.setAttribute("data-idTarea", tarea.id);
            preloader.setAttribute('src', 'app\vistas\preloader.gif');
            preloader.classList.add('preloaderBorrar');
            tick.classList.add('ocultar','fa-solid', 'fa-check', 'tick');
            tickMarcado.classList.add('mostrar','fa-solid', 'fa-check-double', 'tickMarcado');
            capaTarea.appendChild(capaTexto);
            capaTarea.appendChild(papelera);
            capaTarea.appendChild(preloader);
            capaTarea.appendChild(tick);
            capaTarea.appendChild(tickMarcado);
            document.getElementById('tareas').appendChild(capaTarea);

            //Añadir manejador de evento Borrar a la nueva papelera
            papelera.addEventListener('click', manejadorBorrar);
            //Borro el contenido del input
            document.getElementById('nuevaTarea').value = '';
        })
        .finally(function () {
            //Ocultamos el preloader
            document.getElementById('preloaderInsertar').style.visibility = 'hidden';
        });

});



// document.addEventListener('DOMContentLoaded', function () {
//     const checkboxes = document.querySelectorAll('.marcarTarea');

//     checkboxes.forEach(checkbox => {
//         checkbox.addEventListener('change', function () {
//             const idTarea = this.getAttribute('data-idTarea');
//             const nuevoEstado = this.checked ? 'realizada' : 'no realizada';

//             console.log('ID de la tarea:', idTarea);
//             console.log('Nuevo estado:', nuevoEstado);

//             fetch('index.php?accion=tareaEstado&idTarea=' + idTarea, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json'
//                 },
//                 body: JSON.stringify({ nuevoEstado: nuevoEstado })
//             })
//                 .then(response => response.json())
//                 .then(respuesta => {
//                     console.log('Respuesta del servidor:', respuesta);

//                     if (respuesta.respuesta === 'ok') {
//                         // Actualizar el estado del checkbox
//                         if (respuesta.nuevoEstado === 'Realizada') {

//                             this.checked = true;
//                         } else if (respuesta.nuevoEstado === 'No realizada') {
//                             this.checked = false;
//                         }

//                         // Puedes realizar más acciones aquí si es necesario
//                         console.log('Estado de la tarea actualizado:', respuesta.nuevoEstado);
//                     } else {
//                         console.error('Error al actualizar el estado de la tarea:', respuesta.mensaje);
//                         // Puedes manejar errores aquí
//                     }
//                 })
//                 .catch(error => {
//                     console.error('Error de red:', error);
//                     // Puedes manejar errores de red aquí
//                 });
//         });
//     });
// });



