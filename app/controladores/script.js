let botonInsertar = document.getElementById('botonNuevaTarea');

botonInsertar.addEventListener('click', function () {
    // Muestro el preloader
    document.getElementById('preloaderInsertar').style.visibility = 'visible';

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
        //console.log(tarea);
        //Añado la la tarea al div "tareas" modificando el DOM
        var capaTarea = document.createElement('div');
        var capaTexto = document.createElement('div');
        var papelera = document.createElement('i');
        var preloader = document.createElement('img');
        capaTarea.classList.add('tarea');
        capaTexto.classList.add('texto');
        capaTexto.innerHTML=tarea.texto;
        papelera.classList.add('fa-solid', 'fa-trash', 'papelera');
        papelera.setAttribute("data-idTarea",tarea.id);
        preloader.setAttribute('src','preloader.gif');
        preloader.classList.add('preloaderBorrar');
        capaTarea.appendChild(capaTexto);
        capaTarea.appendChild(papelera);
        capaTarea.appendChild(preloader);
        document.getElementById('tareas').appendChild(capaTarea);

        //Añadir manejador de evento Borrar a la nueva papelera
        papelera.addEventListener('click',manejadorBorrar);

        //También se podría hacer así:
        //document.getElementById('tareas').innerHTML+='<div class="tarea"><div class="texto">'+tarea.texto+'</div><i class="fa-solid fa-trash papelera" data-idTarea="'+tarea.id+'"></i></div>';

        //Borro el contenido del input
        document.getElementById('nuevaTarea').value='';
    })
    .finally(function(){
        //Ocultamos el preloader
        document.getElementById('preloaderInsertar').style.visibility='hidden';
    });
    
});


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

    // Mostrar preloader
    let preloader = self.parentElement.querySelector('img');
    preloader.style.visibility = "visible";
    self.style.visibility = 'hidden';
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
            // Ocultar preloader
            preloader.style.visibility = "hidden";
            self.style.visibility = 'visible';
        });
}


