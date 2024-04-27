<?php
require '../database/database.php';
$db = new Database();
$con = $db->conectar();
session_start();

$idUsuario = $_SESSION['usuario'];
$idAutorSeleccionado = isset($_GET['autor']) ? $_GET['autor'] : null;
$orden = isset($_GET['orden']) ? $_GET['orden'] : null;

if ($idUsuario) {
    $query = $con->prepare("SELECT nombres, apellidos, rango, correo FROM usuario WHERE id = ?");
    $query->execute([$idUsuario]);
    $usuario = $query->fetch(PDO::FETCH_ASSOC);
}else {
    header('Location: ../login?error=1');
    exit;
}

$consultaAutores = $con->query("SELECT * FROM autor");
$autores = $consultaAutores->fetchAll(PDO::FETCH_ASSOC);

if ($idAutorSeleccionado) {
    $consulta = $con->prepare("SELECT libro.idLibro, libro.nombre, libro.descripcion, libro.nro_paginas, autor.nombres, autor.apellidos 
                               FROM libro 
                               INNER JOIN autor ON libro.Autor_idAutor = autor.idAutor 
                               WHERE autor.idAutor = ?");
    $consulta->execute([$idAutorSeleccionado]);
} elseif ($orden) {
    if ($orden === 'mayor') {
        $consulta = $con->query("SELECT libro.idLibro, libro.nombre, libro.descripcion, libro.nro_paginas, autor.nombres, autor.apellidos 
                                 FROM libro 
                                 INNER JOIN autor ON libro.Autor_idAutor = autor.idAutor 
                                 ORDER BY libro.nro_paginas DESC");
    } elseif ($orden === 'menor') {
        $consulta = $con->query("SELECT libro.idLibro, libro.nombre, libro.descripcion, libro.nro_paginas, autor.nombres, autor.apellidos 
                                 FROM libro 
                                 INNER JOIN autor ON libro.Autor_idAutor = autor.idAutor 
                                 ORDER BY libro.nro_paginas ASC");
    }
} else {
    $consulta = $con->query("SELECT libro.idLibro, libro.nombre, libro.descripcion, libro.nro_paginas, autor.nombres, autor.apellidos 
                            FROM libro 
                            INNER JOIN autor ON libro.Autor_idAutor = autor.idAutor 
                            ORDER BY libro.idLibro");
}

$resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="../public/CSS/libreria.css">
</head>

<body class="py-3">
    <?php if (isset($usuario) && $usuario) { ?>
        <ul class="nav justify-content-end">
            <li class="nav-item">
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="usuario" src="../public/items/icono-usuario-libreria.avif" alt="">
                    </button>
                    <ul class="dropdown-menu">
                        <li class="dropdown-header">Información del Usuario</li>
                        <li>
                            <p class="dropdown-item"><?php echo $usuario['nombres'] . ' ' . $usuario['apellidos']; ?></p>
                        </li>
                        <li>
                            <p class="dropdown-item"><?php echo $usuario['rango']; ?></p>
                        </li>
                        <li>
                            <p class="dropdown-item"><?php echo $usuario['correo']; ?></p>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item"  href="../login/">Cerrar sesión</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    <?php } ?>

    <main class="container">
        <h1>Lista de Libros</h1>
        <div class="row">
            <div class="col">
                <h4>CRUD-Libreria</h4>
                <ul class="nav nav-tabs" name="barra de opciones">
                    <li class="nav-item">
                        <a href="nuevo_libro.php" class="btn btn-primary float-right">Nuevo Libro</a>
                    </li>
                    <li class="nav-item">
                        <a href="agregar_autor.php" class="btn btn-primary float-right">Agregar Autor</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="index_usuario.php" class="btn btn-primary float-right">Administrar Usuarios</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">Autores</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../libreria">Todos</a></li>
                            <?php foreach ($autores as $autor) : ?>
                                <li><a class="dropdown-item" href="?autor=<?php echo $autor['idAutor']; ?>"><?php echo $autor['nombres'] . ' ' . $autor['apellidos']; ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">Cantidad de páginas</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="?orden=mayor">Mayor a menor</a></li>
                            <li><a class="dropdown-item" href="?orden=menor">Menor a mayor</a></li>
                        </ul>
                    </li>
                    
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Nro. de Páginas</th>
                            <th>Autor</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultado as $row) : ?>
                            <tr>
                                <td><?php echo $row['idLibro']; ?></td>
                                <td><?php echo $row['nombre']; ?></td>
                                <td><?php echo $row['descripcion']; ?></td>
                                <td><?php echo $row['nro_paginas']; ?></td>
                                <td><?php echo $row['nombres'] . ' ' . $row['apellidos']; ?></td>
                                <td>
                                    <a href="editar_libro.php?id=<?php echo $row['idLibro']; ?>" class="btn btn-warning">Editar</a>
                                    <button type="button" class="btn btn-danger" onclick="eliminarLibro(<?php echo $row['idLibro']; ?>)">Eliminar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        function eliminarLibro(idLibro) {
            if (confirm("¿Quiere eliminar el libro?")) {
                window.location.href = "eliminar_libro.php?idLibro=" + idLibro;
            }
        }
    </script>
</body>

</html>