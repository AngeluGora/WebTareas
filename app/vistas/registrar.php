<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
</head>
<body>
    <h1>Registro</h1>
    <?= $error ?>
    <form action="index.php?accion=registrar" method="post" enctype="multipart/form-data">
        <label for="nombre">Nombre: </label><br>
        <input type="text" name="nombre"><br>
        <label for="email">Email: </label><br>
        <input type="email" name="email"><br>
        <label for="password">Contraseña: </label><br>
        <input type="password" name="password"><br>
        <input type="submit" value="registrar">
        <a href="index.php">volver</a>
    </form>
</body>
</html>