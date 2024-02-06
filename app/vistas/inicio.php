<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
<div id="tareas">
    <?php foreach ($tareas as $tarea): ?>
        <?php
        $idTarea = $tarea->getId(); // Mover la definición fuera del bloque condicional
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
            
                <div class="texto"><p id="texto"><?= $tarea->getTexto() ?></p></div>
                <div id="fotos">
                    <?php foreach($fotos as $foto): ?>
                        <img src="web/imagenes/<?=$foto->getNombreArchivo()?>" style="height: 100px; border: 1px solid black;">                
                    <?php endforeach; ?>
                </div>
                <?php if(Sesion::existeSesion()): ?>
                    <?php if($existeTick): ?>
                        <i class="fa-solid fa-cicle-check iconoCheckOn" data-idTarea="<?= $tarea->getId()?>"></i>
                    <?php else: ?>
                        <i class="fa-regular fa-circle-check iconoCheckOff" data-idTarea="<?= $tarea->getId()?>"></i>
                    <?php endif; ?>
                <?php endif; ?>
                <i class="fa-solid fa-trash papelera" data-idTarea="<?= $tarea->getId()?>"></i>
                <span class="icono_editar"><a href="index.php?accion=irAEditarTarea&idTarea=<?=$tarea->getId()?>"><i class="fa-solid fa-pen-to-square color_gris"></i></a></span>
            
            <?php endif; ?>
            
        </div>
    <?php endforeach; ?>
</div>

    <input type="text" id="nuevaTarea">
    <button id="botonNuevaTarea">Enviar</button>
    <script src="app/controladores/script.js" type="text/javascript"></script>


<script>

let tickOn = document.querySelectorAll('.iconoCheckOn');
tickOn.forEach(tickOn =>{
    tickOn.addEventListener('click',quitarTick);
});

let tickOff = document.querySelectorAll('.iconoCheckOff');
tickOff.forEach(tickOff =>{
    tickOff.addEventListener('click',ponerTick);
});

function ponerTick(){
        let idTarea = this.getAttribute('data-idTarea');
        fetch('index.php?accion=marcarComoCompletada&id='+idTarea)
        .then(datos => datos.json())
        .then(respuesta =>{
            console.log(respuesta);
            this.classList.remove("iconoCheckOff");
            this.classList.remove("fa-regular");
            this.classList.add("iconoCheckOn");
            this.classList.add("fa-solid");
            this.removeEventListener('click',ponerTick);
            this.addEventListener('click',quitarTick);
            let texto = document.getElementById('texto');
            texto.style.textDecoration = 'line-through';
        })
        
    }

function quitarTick(){
        let idTarea = this.getAttribute('data-idTarea');
        fetch('index.php?accion=desmarcarComoCompletada&id='+idTarea)
        .then(datos => datos.json())
        .then(respuesta =>{
            console.log(respuesta);
            this.classList.remove("iconoTickOn");
            this.classList.remove("fa-solid");
            this.classList.add("iconoTickOff");
            this.classList.add("fa-regular");
            this.removeEventListener('click',quitarTick);
            this.addEventListener('click',ponerTick);
        })
        
}


</script>
</body>
</html>