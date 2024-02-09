<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS personalizado -->
    <style>
        body {
            background-color: #f8f9fa; /* Color de fondo */
            font-family: Arial, sans-serif; /* Familia de fuente */
            padding-top: 50px; /* Espacio superior */
        }
        header {
            text-align: center;
            margin-bottom: 20px; /* Espacio inferior */
        }
        main {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff; /* Color de fondo del formulario */
            padding: 20px;
            border-radius: 10px; /* Bordes redondeados */
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1); /* Sombra */
        }
        main label {
            color: #6c757d; /* Color de texto gris */
        }
        main input[type="email"],
        main input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ced4da; /* Borde */
            border-radius: 5px; /* Bordes redondeados */
            background-color: #f1f3f5; /* Color de fondo */
        }
        main input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745; /* Color de fondo verde */
            border: none; /* Sin borde */
            border-radius: 5px; /* Bordes redondeados */
            color: #fff; /* Color de texto blanco */
            cursor: pointer; /* Cambiar cursor al pasar por encima */
        }
        main input[type="submit"]:hover {
            background-color: #218838; /* Cambia el color de fondo al pasar el ratón */
        }
        main p {
            text-align: center;
            margin-top: 10px;
            color: #6c757d; /* Color de texto gris */
        }
        main p a {
            color: #007bff; /* Color de texto azul */
            text-decoration: none; /* Sin subrayado */
        }
        main p a:hover {
            color: #0056b3; /* Cambia el color de texto al pasar el ratón */
        }
    </style>
</head>
<body>
    <header>
        <h1 class="tituloPagina">Inicio de sesión</h1>
    </header>

    <main>
        <?php if(Sesion::existeSesion()): ?>
            <p>Ya has iniciado sesión. Redirigiendo...</p>
            <script>
                setTimeout(function() {
                    window.location.href = 'index.php?accion=inicio';
                }, 3000);
            </script>
        <?php else: ?>
            <!-- Formulario de login -->
            <form action="index.php?accion=login" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <input type="submit" class="btn btn-success" value="Iniciar sesión">
            </form>
            <p>No tienes una cuenta aún? <a href="index.php?accion=registrar">Regístrate</a></p>

            <?php
                // Aquí puedes mostrar mensajes de error o éxito si es necesario
                // Por ejemplo, si se ingresaron credenciales incorrectas
                if (isset($_SESSION['mensaje_error'])) {
                    echo '<p>' . $_SESSION['mensaje_error'] . '</p>';
                    unset($_SESSION['mensaje_error']);
                }
            ?>
        <?php endif; ?>
    </main>

</body>
</html>
