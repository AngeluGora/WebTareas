<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <!-- Incluir aquí tus estilos CSS si es necesario -->
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
                }, 3000); // Redirigir después de 3 segundos
            </script>
        <?php else: ?>
            <!-- Formulario de login -->
            <form action="index.php?accion=login" method="post">
                <label for="email">Email:</label>
                <input type="email" name="email" required>

                <label for="password">Contraseña:</label>
                <input type="password" name="password" required>

                <input type="submit" value="Iniciar sesión">
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
