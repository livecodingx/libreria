<?php
require '../database/database.php';

$id=$_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['nro_paginas'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $nro_paginas = $_POST['nro_paginas'];

        if (!empty($_POST['autor'])) {
            $autor = $_POST['autor'];
        } else {
            echo "Error: Selecciona un autor existente.";
            exit();
        }

        $db = new Database();
        $con = $db->conectar();

        $consulta = $con->prepare("INSERT INTO libro (nombre, descripcion, nro_paginas, Autor_idAutor) VALUES (?, ?, ?, ?)");
        $consulta->execute([$nombre, $descripcion, $nro_paginas, $autor]);

        header("Location: ../libreria?id=".$id);
        exit();
    }
}

$db = new Database();
$con = $db->conectar();

$consulta_autores = $con->query("SELECT * FROM autor");
$autores = $consulta_autores->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="py-3">
    <main class="container">
        <h1>Nuevo Libro</h1>
        <div class="row">
            <div class="col">
                <form action="nuevo_libro.php?id=<?php echo $id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="nro_paginas" class="form-label">Número de Páginas</label>
                        <input type="number" class="form-control" id="nro_paginas" name="nro_paginas" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="autor" class="form-label">Autor</label>
                        <select class="form-select" id="autor" name="autor" required>
                            <option value=""></option>
                            <?php foreach ($autores as $autor): ?>
                                <option value="<?php echo $autor['idAutor']; ?>"><?php echo $autor['nombres']." ".$autor['apellidos']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Agregar Libro</button>
                    <a href="../libreria?id=<?php echo $id ?>" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
