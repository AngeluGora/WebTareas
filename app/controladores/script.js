let botonInsertar = document.getElementById('botonNuevaTarea');

botonInsertar.addEventListener('click', function () {

    // Obtengo el valor del input nuevaTarea
    const nuevaTarea = document.getElementById('nuevaTarea').value;

    // Envío datos mediante POST a insertar.php construyendo un FormData
    const datos = new FormData();
    datos.append('texto', nuevaTarea);

    const options = {
        method: "POST",
        body: datos
    };

    fetch('index.php?accion=insertarTarea', options)
    .then(respuesta => { 
        return respuesta.json();
    }).then(tarea => {
        // Crear elementos HTML para representar la nueva tarea, incluyendo el texto de la tarea y un ícono de papelera para borrar la tarea
        var capaTarea = document.createElement('div');
        var capaTexto = document.createElement('div');
        var papelera = document.createElement('i');
        var enlaceEditar = document.createElement('a'); // Crear un elemento 'a' para el enlace de edición
        var iconoEditar = document.createElement('i'); // Crear un elemento 'i' para el ícono de edición
        var tick = document.createElement('i');
        var hrLinea = document.createElement('hr');
        
        capaTarea.classList.add('tarea');
        capaTexto.classList.add('texto');
        capaTexto.innerHTML = tarea.texto;
        tick.classList.add('fa-regular', 'fa-circle-check', 'iconoCheckOff');
        tick.setAttribute("data-idTarea", tarea.id);
        papelera.classList.add('fa-solid', 'fa-trash', 'papelera');
        papelera.setAttribute("data-idTarea", tarea.id);
        enlaceEditar.setAttribute('href', 'index.php?accion=irAEditarTarea&idTarea=' + tarea.id); // Establecer el atributo 'href' del enlace
        enlaceEditar.classList.add('icono_editar'); // Agregar clase al enlace
        iconoEditar.classList.add('fa-solid', 'fa-pen-to-square', 'color_gris'); // Agregar clases al ícono de edición
        iconoEditar.setAttribute('data-idTarea', tarea.id);
        enlaceEditar.appendChild(iconoEditar); // Agregar el ícono de edición al enlace
        
            hrLinea.classList.add('linea2','oculto');
            hrLinea.setAttribute('data-idTarea', tarea.id);
        
        
        capaTarea.appendChild(capaTexto);
        capaTarea.appendChild(tick);
        capaTarea.appendChild(papelera);
        capaTarea.appendChild(enlaceEditar); 
        capaTarea.appendChild(hrLinea); 
        document.getElementById('tareas').appendChild(capaTarea);
        
        papelera.addEventListener('click', manejadorBorrar);
        tick.addEventListener('click', ponerTick);
        hrLinea.addEventListener('click', ponerTick);
    })
    .finally(function(){
    });

});


let tickOn = document.querySelectorAll('.iconoCheckOn');
tickOn.forEach(tickOn =>{
    tickOn.addEventListener('click',quitarTick);
});

let tickOff = document.querySelectorAll('.iconoCheckOff');
tickOff.forEach(tickOff =>{
    tickOff.addEventListener('click',ponerTick);
});

function ponerTick() {
    let idTarea = this.getAttribute('data-idTarea');
    fetch('index.php?accion=marcarComoCompletada&id=' + idTarea)
        .then(datos => datos.json())
        .then(respuesta => {
            this.classList.remove("iconoCheckOff");
            this.classList.remove("fa-regular");
            this.classList.add("iconoCheckOn");
            this.classList.add("fa-solid");
            this.removeEventListener('click', ponerTick);
            this.addEventListener('click', quitarTick);

            let hrElement = document.querySelector('hr[data-idTarea="'+idTarea+'"]');
            if (hrElement) {
                hrElement.classList.remove('oculto');
            }
            
        });
}

function quitarTick() {
    let idTarea = this.getAttribute('data-idTarea');
    fetch('index.php?accion=desmarcarComoCompletada&id=' + idTarea)
        .then(datos => datos.json())
        .then(respuesta => {
            console.log(respuesta);
            this.classList.remove("iconoTickOn");
            this.classList.remove("fa-solid");
            this.classList.add("iconoTickOff");
            this.classList.add("fa-regular");
            this.removeEventListener('click', quitarTick);
            this.addEventListener('click', ponerTick);

            let hrElement = document.querySelector('hr[data-idTarea="'+idTarea+'"]');
            if (hrElement) {
                hrElement.classList.add('oculto');
            }
        });
}

let papeleras = document.querySelectorAll('.papelera');
papeleras.forEach(papelera => {
    papelera.addEventListener('click',manejadorBorrar);
});

    
function manejadorBorrar() {
    // Guardar el contexto actual
    const self = this;

    // Obtener el idTarea
    const idTarea = self.getAttribute('data-idTarea');
    // Crear FormData y opciones para la solicitud fetch
    const datos2 = new FormData();
    datos2.append('idTarea', idTarea);

    const options2 = {
        method: "POST",
        body: datos2
    }

    
    // Llamada al script del servidor que borra la tarea pasándole el idTarea como parámetro
    fetch('index.php?accion=borrarTarea', options2)
        .then(datos => datos.json())
        .then(respuesta => {
            if (respuesta.respuesta == 'ok') {
                self.parentElement.remove();
            } else {
                alert("No se ha encontrado la tarea en el servidor");
                self.style.visibility = 'visible';
            }
        })
        .finally(function () {
        });
}


