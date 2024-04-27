<?php
require '../database/database.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['idLibro'])) {
    $idLibro = $_GET['idLibro'];

    $db = new Database();
    $con = $db->conectar();

    $consulta = $con->prepare("DELETE FROM libro WHERE idLibro = ?");
    $consulta->execute([$idLibro]);

    header("Location: index.php");
    exit();
} else {
    echo "Error: No se ha proporcionado un ID de libro válido.";
}
?>