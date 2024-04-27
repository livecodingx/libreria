<?php
session_start();
$error_message = '';
if (isset($_GET['error'])) {
    $error_message = "Usuario no encontrado. Por favor verifica tus credenciales.";
}elseif (isset($_SESSION['usuario'])) {
    session_destroy();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: blueviolet;">
    <?php if (!empty($error_message)) : ?>
        <script>
            alert("<?php echo $error_message; ?>");
        </script>
    <?php endif; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Inicio de Sesión</h3>
                        <form action="autentificacion.php" method="post">
                            <div class="form-group">
                                <label for="inputEmail">Correo Electrónico</label>
                                <input type="email" class="form-control" id="inputEmail"
                                    placeholder="Ingrese su correo electrónico" name="txtcorreo" required>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword">Contraseña</label>
                                <input type="password" class="form-control" id="inputPassword"
                                    placeholder="Ingrese su contraseña" name="txtpassword" autocomplete="off" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" name="autentificar">Iniciar
                                Sesión</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <a href="../" class="btn btn-primary mt-3">volver</a>
    </div>

</body>

</html>
