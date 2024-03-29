<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="web/css/estilos.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .tituloPagina {
        margin-right: 10px;
        }
        .emailUsuario {
        margin-right: 10px;
        }
        body {
            background-image: url('web/imagenes/background.png');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom">
        <div style="max-width: 100px;">
            <img src="web/imagenes/logo.png" class="d-inline-block align-top img-fluid" alt="Logo">
        </div>

        <h1 class="tituloPagina" style="">Tus tareas con TaskOverFlow</h1>
        <div class="ml-auto"> 
            <?php if(Sesion::getUsuario()): ?>
                <span class="emailUsuario"><?= Sesion::getUsuario()->getEmail() ?></span>
                <a href="index.php?accion=logout" class="btn btn-danger">Cerrar sesión</a> 
            <?php endif; ?>
        </div>
    </nav>

    <div id="tareas">
    <?php foreach ($tareas as $tarea): ?>
        <?php
        $idTarea = $tarea->getId(); 
        if(Sesion::existeSesion()){
            $TickDAO = new TickDAO($conn);
            $idUsuario = Sesion::getUsuario()->getId();
            $existeTick = $TickDAO->existByIdUsuarioIdTarea($idUsuario, $idTarea);
            
        }
        $fotosDAO = new FotosDAO($conn);
        $fotos = $fotosDAO->getAllByIdTarea($idTarea);
        ?>
        <div class="tarea">
            <?php if(Sesion::getUsuario() && Sesion::getUsuario()->getId() == $tarea->getIdUsuario()): ?>
                
                <div class="texto">
                <?php if ($existeTick): ?>
                    <hr id="linea" data-idTarea="<?= $tarea->getId() ?>">
                <?php else: ?>
                    <hr id="linea" class="oculto" data-idTarea="<?= $tarea->getId() ?>">
                <?php endif; ?>
                    <p id="texto"><?= $tarea->getTexto() ?></p>
                </div>
                <?php if(Sesion::existeSesion()): ?>
                    <?php if($existeTick): ?>
                        <i class="fa-solid fa-circle-check iconoCheckOn" data-idTarea="<?= $tarea->getId()?>"></i>

                    <?php else: ?>
                        <i class="fa-regular fa-circle-check iconoCheckOff" data-idTarea="<?= $tarea->getId()?>"></i>
                    <?php endif; ?>

                <?php endif; ?>
                <div id="fotos">
                    <div id="fotos2">
                        <?php if($fotos!=false){ foreach($fotos as $foto): ?>
                            <img src="web/imagenes/<?=$foto->getNombreArchivo()?>" style="height: 100px; border: 1px solid black";>                
                        <?php endforeach; }?>
                    </div>
        </div>
                
                <i class="fa-solid fa-trash papelera" data-idTarea="<?= $tarea->getId()?>"></i>
                <span class="icono_editar"><a href="index.php?accion=irAEditarTarea&idTarea=<?=$tarea->getId()?>"><i class="fa-solid fa-pen-to-square color_gris"></i></a></span>
                
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
    <div class="contenedor">
        <input type="text" id="nuevaTarea"  placeholder="Escribe tu tarea..."/>
        <button id="botonNuevaTarea">Enviar</button>
    </div>
    
    <script src="app/controladores/script.js" type="text/javascript"></script>


<script>
    let idTarea = this.getAttribute('data-idTarea');
        let botonAddImage = document.getElementById('addImage');
        botonAddImage.addEventListener('click',function(){
            document.getElementById('inputFileImage').click();
        });

        let inputFileImage = document.getElementById('inputFileImage');
        inputFileImage.addEventListener('change',function(){
            let formData = new FormData();
            formData.append('foto',inputFileImage.files[0]);
            fetch('index.php?accion=addImageTarea&idTarea='+idTarea,{
                method: 'POST',
                body: formData
            })
            .then(datos => datos.json())
            .then(respuesta => {
                let nuevaFoto = document.createElement("img");
                nuevaFoto.classList.add('imagenMensaje');
                nuevaFoto.setAttribute("src",'web/imagenes/'+respuesta.nombreArchivo);
                document.getElementById('fotos2').append(nuevaFoto);
            })
        });

let ticksOn = document.querySelectorAll('.iconoCheckOn');
ticksOn.forEach(tickOn =>{
    tickOn.addEventListener('click',quitarTick);
});

let ticksOff = document.querySelectorAll('.iconoCheckOff');
ticksOff.forEach(tickOff =>{
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


</script>
</body>
</html>