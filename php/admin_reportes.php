<?php
// Verificar si el usuario ha iniciado sesión y tiene el rol de administrador
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: /proyecto/acceso_denegado.php");
    exit();
}

// Conexión a la base de datos
$serverName = "DESKTOP-4HOSKP8";
$connectionInfo = array("Database"=>"Restaurante", "UID"=>"Kevin", "PWD"=>"123");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Obtener los datos para los reportes y gráficos
$sql = "SELECT COUNT(*) AS total_usuarios FROM usuarios";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$totalUsuarios = $row['total_usuarios'];

$sql = "SELECT COUNT(*) AS total_reservaciones FROM reservaciones";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$totalReservaciones = $row['total_reservaciones'];

$sql = "SELECT COUNT(*) AS total_productos FROM productos";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$totalProductos = $row['total_productos'];

$sql = "SELECT SUM(total) AS total_ventas FROM pagos";
$stmt = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$totalVentas = $row['total_ventas'];

// Generar el reporte en formato Excel
if (isset($_POST['generar_reporte'])) {
    require_once 'vendor/autoload.php';

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'Reporte de Estadísticas');
    $sheet->setCellValue('A2', 'Total de Usuarios');
    $sheet->setCellValue('B2', $totalUsuarios);
    $sheet->setCellValue('A3', 'Total de Reservaciones');
    $sheet->setCellValue('B3', $totalReservaciones);
    $sheet->setCellValue('A4', 'Total de Productos');
    $sheet->setCellValue('B4', $totalProductos);
    $sheet->setCellValue('A5', 'Total de Ventas');
    $sheet->setCellValue('B5', $totalVentas);

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $filename = 'reporte_estadisticas.xlsx';
    $writer->save($filename);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generar Reportes</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Generar Reportes</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="submit" name="generar_reporte" value="Generar Reporte en Excel">
    </form>

    <h2>Estadísticas</h2>
    <canvas id="grafico"></canvas>

    <script>
        var ctx = document.getElementById('grafico').getContext('2d');
        var grafico = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Usuarios', 'Reservaciones', 'Productos', 'Ventas'],
                datasets: [{
                    label: 'Estadísticas',
                    data: [<?php echo $totalUsuarios; ?>, <?php echo $totalReservaciones; ?>, <?php echo $totalProductos; ?>, <?php echo $totalVentas; ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <a href="admin_dashboard.php">Volver al Panel de Administración</a>
</body>
</html>

<?php
sqlsrv_close($conn);
?>