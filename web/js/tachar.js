let ticks = document.querySelectorAll('.tick');

ticks.forEach(tick => {
    tick.addEventListener('click', manejadorTachar);
});

function manejadorTachar() {
    let idTarea = this.getAttribute('data-idTarea');
    console.log("Has entrado en el manejador tachar");
    console.log("El id de la tarea es: " + idTarea);
    
    let preloader = this.parentElement.querySelector('img');
    preloader.style.visibility = "visible";
    this.style.visibility = 'hidden';

    let tickElement = this;

    fetch('index.php?accion=tareaEstado&id=' + idTarea)
        .then(respuesta => respuesta.json())
        .then(data => {
            console.log("Respuesta del servidor:", data);
            if (data.respuesta === 'ok') {
                actualizarAparienciaTick(idTarea, data.nuevoEstado);
                console.log("Tarea marcada con ID:", idTarea);
            } else {
                console.log("Tarea no marcada con ID:", idTarea);
                alert("No se ha encontrado la tarea en el servidor");
                self.style.visibility = 'visible';
            }
        })
        .catch(error => {
            console.error('Error al procesar la solicitud:', error);
            alert("Error al procesar la solicitud");
            self.style.visibility = 'visible';
        });
}

function actualizarAparienciaTick(idTarea, nuevoEstado) {
    let tick = document.querySelector('.tick[data-idTarea="' + idTarea + '"]');
    if (!tick) return;

    if (nuevoEstado === "Realizada") {
        tick.classList.remove("fa-regular");
        tick.classList.add("fa-solid");
        tick.style.visibility = 'visible';
        tick.parentElement.querySelector('img').style.visibility = "hidden";
        let textoTarea = tick.parentElement.querySelector('.card-text');
        textoTarea.style.opacity = 0.3;
        textoTarea.style.textDecoration = "line-through"; // Tachar el texto
    } else {
        tick.classList.remove("fa-solid");
        tick.classList.add("fa-regular");
        tick.style.visibility = 'visible';
        tick.parentElement.querySelector('img').style.visibility = "hidden";
        let textoTarea = tick.parentElement.querySelector('.card-text');
        textoTarea.style.opacity = 1; // Restaurar opacidad
        textoTarea.style.textDecoration = "none"; // Eliminar el tachado
    }
}

