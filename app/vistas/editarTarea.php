<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarea</title>
    <style>
        body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    background-image: url('web/imagenes/fondo.jpeg');
    background-size: auto;
    background-position: center;
    filter: blur(10px); /* Ajusta el valor para cambiar la intensidad del efecto de desenfoque */
}

        img{
            height: 100px; 
            border: 1px solid black;
        }
        h1 {
            text-align: center;
            font-size: 50px;
            margin-bottom: 20px;
            font-family: 'Times New Roman', Times, serif, Geneva, Tahoma, sans-serif;
            font-style: italic;
            color:  #EDFCDA;
        }


    form {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    textarea {
        width: calc(100% - 10px);
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    textarea {
        resize: vertical;
    }

    #fotos {
        margin-bottom: 20px;
    }

    #fotos2 {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    #fotos2 img {
        height: 100px;
        border: 1px solid black;
        border-radius: 5px;
    }

    #addImage {
        font-size: 24px;
        line-height: 100px;
        width: 100px;
        height: 100px;
        text-align: center;
        border: 1px dashed #ccc;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"] {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>

    </style>
</head>
<body>
    <h1>Editar Tarea</h1>
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