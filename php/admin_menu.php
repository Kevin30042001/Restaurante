<?php
session_start();

// Check if the user is logged in and has admin role
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /proyecto/acceso_denegado.php");
    exit();
}

// Database connection
$serverName = "DESKTOP-4HOSKP8";
$connectionInfo = array("Database" => "Restaurante", "UID" => "Kevin", "PWD" => "123");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die("Error connecting to the database: " . print_r(sqlsrv_errors(), true));
}

// Handle menu item creation form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST["nombre"]);
    $descripcion = htmlspecialchars($_POST["descripcion"]);
    $precio = filter_var($_POST["precio"], FILTER_VALIDATE_FLOAT);
    $categoria = htmlspecialchars($_POST["categoria"]);

    if ($nombre && $descripcion && $precio && $categoria) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria) VALUES (?, ?, ?, ?)";
        $params = array($nombre, $descripcion, $precio, $categoria);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die("Error inserting the item: " . print_r(sqlsrv_errors(), true));
        } else {
            echo "Menu item created successfully.";
        }
    } else {
        echo "Invalid input data.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Menú</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="defaultstyle/admin_menu.css" type="text/css">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar bg-dark border-bottom border-body" data-bs-theme="dark">
        <div class="container-fluid">
           
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/proyecto/Informacion.html">ACERCA DE NOSOTROS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/proyecto/Platillos.html">PLATILLOS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/proyecto/pagos.html">REALIZAR PEDIDO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/proyecto/agregar_producto.html">AGREGAR PRODUCTO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/proyecto/reservacion.html">RESERVACIÓN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/proyecto/php/admin_menu.php">ADMINISTRADOR</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="contenedor2">
        <div class="wrapper">
            <h1 class="text-primary">Gestionar Menú</h1>

            <h2>Crear Elemento del Menú</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" name="descripcion" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio:</label>
                    <input type="number" step="0.01" class="form-control" name="precio" required>
                </div>
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría:</label>
                    <input type="text" class="form-control" name="categoria" required>
                </div>
                <button type="submit" class="btn btn-primary">Crear Elemento</button>
            </form>

            <h2 class="mt-5">Lista de Elementos del Menú</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="productos-seleccionados">
                    <?php
                    $sql = "SELECT * FROM productos";
                    $stmt = sqlsrv_query($conn, $sql);
                    if ($stmt === false) {
                        die("Error fetching the items: " . print_r(sqlsrv_errors(), true));
                    }
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                        echo "<td>$" . htmlspecialchars($row['precio']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
                        echo "<td class='actions'>";
                        echo "<a href='editar_menu.php?id=" . urlencode($row['id']) . "' class='btn btn-secondary btn-sm'>Editar</a> ";
                        echo "<a href='eliminar_menu.php?id=" . urlencode($row['id']) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de eliminar este elemento del menú?\")'>Eliminar</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="admin_dashboard.php" class="btn btn-outline-primary">Volver al Panel de Administración</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

<?php
sqlsrv_close($conn);
?>
