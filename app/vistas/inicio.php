<!DOCTYPE html>
<html lang="en">
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
            
                <div class="texto"><?= $tarea->getTexto() ?></div>
                <i class="fa-solid fa-trash papelera" data-idTarea="<?= $tarea->getId()?>"></i>
                <img src="preloader.gif" class="preloaderBorrar">

                <span class="icono_editar"><a href="index.php?accion=irAEditarTarea&idTarea=<?=$tarea->getId()?>"><i class="fa-solid fa-pen-to-square color_gris"></i></a></span>
            <div id="fotos">
                <?php foreach($fotos as $foto): ?>
                    <img src="web/images/<?=$foto->getNombreArchivo()?>" style="height: 100px; border: 1px solid black;">                
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
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