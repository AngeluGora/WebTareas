<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <style>
        img{
            height: 100px; 
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <h1>EDITAR</h1>
    <form action="index.php?accion=editarTarea&id=<?= $idTarea ?>" method="post" data-idTarea="<?=$idTarea?>" id="formularioEditar">
        
        <input type="text" name="idTarea" value="<?=$tarea->getId()?>" readonly>
        <label for="fecha">Fecha:</label>
        <input type="text" name="fecha" value="<?=$tarea->getFecha()?>" readonly><br>
        <label for="texto">Texto:</label>
        <textarea name="texto" placeholder="Texto"><?=$tarea->getTexto()?></textarea><br>
        <label for="idUsuario">ID de Usuario:</label>
        <input type="text" name="idUsuario" value="<?=$tarea->getIdUsuario()?>" readonly><br> 

        <div id="fotos">
            <div id="fotos2">
                <?php if($fotos!=false){ foreach($fotos as $foto): ?>
                    <img src="web/imagenes/<?=$foto->getNombreArchivo()?>" style="height: 100px; border: 1px solid black";>                
                <?php endforeach; }?>
            </div>
            <div id="addImage">+</div>
            <input type="file" style="display: none;" id="inputFileImage">
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>

    <script type="text/javascript">
        let idTarea = document.getElementById('formularioEditar').getAttribute('data-idTarea');
        //Para que se abra la ventana de seleccionar archivo al hacer click en el botón
        let botonAddImage = document.getElementById('addImage');
        botonAddImage.addEventListener('click',function(){
            document.getElementById('inputFileImage').click();
        });

        //Enviamos el archivo por AJAX cuando se modifique el campo input (cuando se seleccione un archivo)
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
    </script>
</body>
</html>