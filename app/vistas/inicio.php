<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas</title>
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
                <span class="icono_borrar"><a href="index.php?accion=borrarTarea&id=<?=$tarea->getId()?>"><i class="fa-solid fa-trash color_gris"></i></a></span>
                <span class="icono_editar"><a href="index.php?accion=editarTarea&id=<?=$tarea->getId()?>"><i class="fa-solid fa-pen-to-square color_gris"></i></a></span>
            <?php endif; ?>
            <p class="texto"><?= $tarea->getTexto() ?></p>
            <div id="fotos">
                <?php foreach($fotos as $foto): ?>
                    <img src="web/images/<?=$foto->getNombreArchivo()?>" style="height: 100px; border: 1px solid black;">                
                <?php endforeach; ?>
            </div>
            <?php if(Sesion::existeSesion()): ?>
                <?php if($existeTick): ?>
                    <i class="fa-solid fa-heart iconoFavoritoOn" data-idTarea="<?= $tarea->getId()?>"></i>
                <?php else: ?>
                    <i class="fa-regular fa-heart iconoFavoritoOff" data-idTarea="<?= $tarea->getId()?>"></i>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

    <input type="text" id="nuevaTarea">
    <button id="botonNuevaTarea">Enviar</button><img src="preloader.gif" id="preloaderInsertar">
    <script src="app/controladores/script.js" type="text/javascript"></script>

</body>
</html>