<?php
require '../database/database.php';
$idUsuario = $_GET['usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idLibro = $_POST['idLibro'];
    $nombreLibro = $_POST['nombre_libro'];
    $descripcion = $_POST['descripcion'];
    $nroPaginas = $_POST['nro_paginas'];
    $autorId = $_POST['autor_id'];
    $id = $_POST['idUsuario'];

    $db = new Database();
    $con = $db->conectar();

    $consulta = $con->prepare("UPDATE libro SET nombre = ?, descripcion = ?, nro_paginas = ?, Autor_idAutor = ? WHERE idLibro = ?");
    $consulta->execute([$nombreLibro, $descripcion, $nroPaginas, $autorId, $idLibro]);
    header("Location: ../libreria?id=".$id);
    exit();
}


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../libreria");
    exit();
}

$idLibro = $_GET['id'];

$db = new Database();
$con = $db->conectar();

$consulta = $con->prepare("SELECT * FROM libro WHERE idLibro = ?");
$consulta->execute([$idLibro]);
$libro = $consulta->fetch(PDO::FETCH_ASSOC);

$consultaAutores = $con->query("SELECT * FROM autor");
$autores = $consultaAutores->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="py-3">
    <main class="container">
        <h1>Editar Libro</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>">
            <input type="hidden" name="idLibro" value="<?php echo $libro['idLibro']; ?>">
            <div class="mb-3">
                <label for="nombre_libro" class="form-label">Nombre del Libro</label>
                <input type="text" class="form-control" id="nombre_libro" name="nombre_libro" value="<?php echo $libro['nombre']; ?>">
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion"><?php echo $libro['descripcion']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="nro_paginas" class="form-label">Número de Páginas</label>
                <input type="number" class="form-control" id="nro_paginas" name="nro_paginas" value="<?php echo $libro['nro_paginas']; ?>" min="1">
            </div>
            <div class="mb-3">
                <label for="autor_id" class="form-label">Autor</label>
                <select class="form-select" id="autor_id" name="autor_id">
                    <?php foreach ($autores as $autor) : ?>
                        <option value="<?php echo $autor['idAutor']; ?>" <?php if ($autor['idAutor'] == $libro['Autor_idAutor']) echo 'selected'; ?>><?php echo $autor['nombres'] . ' ' . $autor['apellidos']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="guardar_cambios">Guardar Cambios</button>
            <a href="../libreria?id=<?php echo $idUsuario ?>" class="btn btn-secondary">Cancelar</a>
        </form>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>