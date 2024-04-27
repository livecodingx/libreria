<?php
require '../database/database.php';

$db = new Database();
$con = $db->conectar();

if (!$con) {
    echo "Error al conectar a la base de datos";
} else {
    $correo = $_POST['txtcorreo'];
    $password = $_POST['txtpassword'];

    $query = $con->prepare("SELECT id, nombres, apellidos, rango, correo FROM usuario WHERE correo = :cor AND password = :pass");
    $query->execute(array('cor' => $correo, 'pass' => $password));

    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $sesion = session_start();
        if ($sesion) {
            $_SESSION['usuario'] = $usuario['id'];
            $_SESSION['nombres'] = $usuario['nombres'];
            $_SESSION['apellidos'] = $usuario['apellidos'];
            $_SESSION['rango'] = $usuario['rango'];
            $_SESSION['correo'] = $usuario['correo'];
            header('Location: ../libreria');
            exit;
        } else {
            echo "Error al iniciar la sesión";
        }
    } else {
        header('Location: ../login?error=1');
        exit;
    }
}
