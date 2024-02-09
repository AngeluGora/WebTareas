<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS personalizado -->
    <style>
        body {
            background-color: #f8f9fa; /* Color de fondo */
            font-family: Arial, sans-serif; /* Familia de fuente */
            padding-top: 50px; /* Espacio superior */
        }
        h1 {
            text-align: center;
            color: #28a745; /* Color de texto verde */
        }
        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff; /* Color de fondo del formulario */
            padding: 20px;
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1); /* Sombra */
        }
        form label {
            color: #6c757d; /* Color de texto gris */
        }
        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ced4da; /* Borde */
            border-radius: 5px; /* Bordes redondeados */
            background-color: #f1f3f5; /* Color de fondo */
        }
        form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745; /* Color de fondo verde */
            border: none; /* Sin borde */
            border-radius: 5px; /* Bordes redondeados */
            color: #fff; /* Color de texto blanco */
            cursor: pointer; /* Cambiar cursor al pasar por encima */
        }
        form input[type="submit"]:hover {
            background-color: #218838; /* Cambia el color de fondo al pasar el ratón */
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff; /* Color de texto azul */
            text-decoration: none; /* Sin subrayado */
        }
        a:hover {
            color: #0056b3; /* Cambia el color de texto al pasar el ratón */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registro</h1>
        <?= $error ?>
        <form action="index.php?accion=registrar" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" name="nombre">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email">
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" name="password">
            </div>
            <input type="submit" class="btn btn-success" value="Registrar">
            <a href="index.php" class="btn btn-link">Volver</a>
        </form>
    </div>
</body>
</html>
