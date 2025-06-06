<?php
include "../assets/config.php";
require_once('../assets/tcpdf/tcpdf.php');
require_once('../assets/phpspreadsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Configurar headers para respuestas JSON por defecto
header('Content-Type: application/json; charset=utf-8');

$indicador = isset($_POST['ind']) ? $_POST['ind'] : (isset($_GET['ind']) ? $_GET['ind'] : "");

try {
    switch ($indicador) {
        case "reporte_reservas":
            generarReporteReservas();
            break;
            
        case "reporte_areas_utilizadas":
            generarReporteAreasUtilizadas();
            break;
            
        case "obtener_areas":
            obtenerAreas();
            break;
            
        case "obtener_estados":
            obtenerEstados();
            break;
            
        case "obtener_usuarios":
            obtenerUsuarios();
            break;
            
        case "estadisticas_rapidas":
            obtenerEstadisticasRapidas();
            break;
            
        case "reportes_recientes":
            obtenerReportesRecientes();
            break;
            
        default:
            throw new Exception("Indicador no válido");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}

function generarReporteReservas() {
    global $datappi;
    
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $area_id = $_POST['area_id'] ?? '';
    $usuario_id = $_POST['usuario_id'] ?? '';
    $estado_id = $_POST['estado_id'] ?? '';
    $formato = $_POST['formato'] ?? 'pdf';

    // Construir consulta dinámica
    $query = "SELECT r.reserva_id, r.fecha_reserva, r.hora_inicio, r.hora_fin, r.comentario,
                     u.nombre_completo, u.documento, u.telefono, u.email,
                     a.nombre as area_nombre, a.ubicacion, a.capacidad,
                     e.nombre as estado_nombre,
                     CONCAT(COALESCE(t.nombre_torre, ''), 
                            CASE WHEN t.nombre_torre IS NOT NULL AND res.numero_residencia IS NOT NULL 
                                 THEN CONCAT(' - Apto ', res.numero_residencia) 
                                 ELSE COALESCE(res.numero_residencia, '') 
                            END) as residencia
              FROM reservas r 
              INNER JOIN usuarios u ON r.usuario_id = u.id
              INNER JOIN Areas_Comunes a ON r.area_id = a.area_id
              LEFT JOIN estados e ON r.estado_id = e.id
              LEFT JOIN residencias res ON u.id_residencia = res.id_residencia
              LEFT JOIN torres t ON res.id_torre_residencia = t.id_torre
              WHERE 1=1";

    $params = [];

    // Aplicar filtros
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $query .= " AND r.fecha_reserva BETWEEN :fecha_inicio AND :fecha_fin";
        $params[':fecha_inicio'] = $fecha_inicio;
        $params[':fecha_fin'] = $fecha_fin;
    }

    if (!empty($area_id)) {
        $query .= " AND r.area_id = :area_id";
        $params[':area_id'] = $area_id;
    }

    if (!empty($usuario_id)) {
        $query .= " AND r.usuario_id = :usuario_id";
        $params[':usuario_id'] = $usuario_id;
    }

    if (!empty($estado_id)) {
        $query .= " AND r.estado_id = :estado_id";
        $params[':estado_id'] = $estado_id;
    }

    $query .= " ORDER BY r.fecha_reserva DESC, r.hora_inicio ASC";

    $stmt = $datappi->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($formato == 'excel') {
        generarExcelReservas($reservas, $fecha_inicio, $fecha_fin);
    } else {
        generarPDFReservas($reservas, $fecha_inicio, $fecha_fin);
    }
}

function generarReporteAreasUtilizadas() {
    global $datappi;
    
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $formato = $_POST['formato'] ?? 'pdf';

    $query = "SELECT a.nombre as area_nombre, a.ubicacion, a.capacidad,
                     COUNT(r.reserva_id) as total_reservas,
                     COUNT(DISTINCT r.usuario_id) as usuarios_unicos,
                     COALESCE(SUM(TIMESTAMPDIFF(HOUR, r.hora_inicio, r.hora_fin)), 0) as horas_reservadas,
                     COALESCE(AVG(TIMESTAMPDIFF(HOUR, r.hora_inicio, r.hora_fin)), 0) as promedio_horas_por_reserva
              FROM Areas_Comunes a
              LEFT JOIN reservas r ON a.area_id = r.area_id";

    $params = [];
    if (!empty($fecha_inicio) && !empty($fecha_fin)) {
        $query .= " AND r.fecha_reserva BETWEEN :fecha_inicio AND :fecha_fin";
        $params[':fecha_inicio'] = $fecha_inicio;
        $params[':fecha_fin'] = $fecha_fin;
    }

    $query .= " GROUP BY a.area_id, a.nombre, a.ubicacion, a.capacidad
               ORDER BY total_reservas DESC";

    $stmt = $datappi->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->execute();
    $estadisticas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($formato == 'excel') {
        generarExcelEstadisticas($estadisticas, $fecha_inicio, $fecha_fin);
    } else {
        generarPDFEstadisticas($estadisticas, $fecha_inicio, $fecha_fin);
    }
}

function generarPDFReservas($reservas, $fecha_inicio, $fecha_fin) {
    // Cambiar header para PDF
    header('Content-Type: application/pdf');
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    $pdf->SetCreator('Sistema de Reservas');
    $pdf->SetAuthor('Administración');
    $pdf->SetTitle('Reporte de Reservas');
    
    $periodo = (!empty($fecha_inicio) && !empty($fecha_fin)) 
        ? "Período: $fecha_inicio al $fecha_fin" 
        : 'Todos los registros';
    
    $pdf->SetHeaderData('', 0, 'REPORTE DE RESERVAS', $periodo);
    
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    $pdf->AddPage();
    
    $html = '<style>
                table { border-collapse: collapse; width: 100%; font-size: 8px; }
                th { background-color: #4472C4; color: white; font-weight: bold; padding: 5px; text-align: center; }
                td { padding: 4px; border: 1px solid #ddd; }
                .total { background-color: #E7E6E6; font-weight: bold; }
             </style>';
    
    $html .= '<h3>Resumen del Reporte</h3>';
    $html .= '<p><strong>Total de reservas:</strong> ' . count($reservas) . '</p>';
    $html .= '<p><strong>Fecha de generación:</strong> ' . date('d/m/Y H:i:s') . '</p><br>';
    
    if (count($reservas) > 0) {
        $html .= '<table>
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Documento</th>
                            <th>Área</th>
                            <th>Horario</th>
                            <th>Estado</th>
                            <th>Residencia</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($reservas as $reserva) {
            $html .= '<tr>
                        <td>' . date('d/m/Y', strtotime($reserva['fecha_reserva'])) . '</td>
                        <td>' . htmlspecialchars($reserva['nombre_completo'] ?? '') . '</td>
                        <td>' . htmlspecialchars($reserva['documento'] ?? '') . '</td>
                        <td>' . htmlspecialchars($reserva['area_nombre'] ?? '') . '</td>
                        <td>' . date('H:i', strtotime($reserva['hora_inicio'])) . ' - ' . date('H:i', strtotime($reserva['hora_fin'])) . '</td>
                        <td>' . htmlspecialchars($reserva['estado_nombre'] ?? 'Sin estado') . '</td>
                        <td>' . htmlspecialchars($reserva['residencia'] ?? 'No especificada') . '</td>
                      </tr>';
        }
        
        $html .= '</tbody></table>';
    } else {
        $html .= '<p>No se encontraron reservas con los filtros aplicados.</p>';
    }
    
    $pdf->writeHTML($html, true, false, true, false, '');
    
    $pdf->Output('reporte_reservas_' . date('Y-m-d') . '.pdf', 'D');
    exit;
}

function generarExcelReservas($reservas, $fecha_inicio, $fecha_fin) {
    // Cambiar header para Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_reservas_' . date('Y-m-d') . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    $sheet->setTitle('Reporte Reservas');
    
    // Encabezados
    $headers = ['Fecha', 'Usuario', 'Documento', 'Teléfono', 'Email', 'Área', 'Ubicación', 'Hora Inicio', 'Hora Fin', 'Estado', 'Residencia', 'Comentario'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $sheet->getStyle($col . '1')->getFont()->setBold(true);
        $sheet->getStyle($col . '1')->getFill()
               ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
               ->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle($col . '1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $col++;
    }
    
    // Datos
    $row = 2;
    foreach ($reservas as $reserva) {
        $sheet->setCellValue('A' . $row, date('d/m/Y', strtotime($reserva['fecha_reserva'])));
        $sheet->setCellValue('B' . $row, $reserva['nombre_completo'] ?? '');
        $sheet->setCellValue('C' . $row, $reserva['documento'] ?? '');
        $sheet->setCellValue('D' . $row, $reserva['telefono'] ?? '');
        $sheet->setCellValue('E' . $row, $reserva['email'] ?? '');
        $sheet->setCellValue('F' . $row, $reserva['area_nombre'] ?? '');
        $sheet->setCellValue('G' . $row, $reserva['ubicacion'] ?? '');
        $sheet->setCellValue('H' . $row, date('H:i', strtotime($reserva['hora_inicio'])));
        $sheet->setCellValue('I' . $row, date('H:i', strtotime($reserva['hora_fin'])));
        $sheet->setCellValue('J' . $row, $reserva['estado_nombre'] ?? 'Sin estado');
        $sheet->setCellValue('K' . $row, $reserva['residencia'] ?? 'No especificada');
        $sheet->setCellValue('L' . $row, $reserva['comentario'] ?? '');
        $row++;
    }
    
    // Ajustar ancho de columnas
    foreach (range('A', 'L') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

function generarPDFEstadisticas($estadisticas, $fecha_inicio, $fecha_fin) {
    // Similar implementación para estadísticas PDF
    header('Content-Type: application/pdf');
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    $pdf->SetCreator('Sistema de Reservas');
    $pdf->SetTitle('Reporte de Áreas Más Utilizadas');
    
    $periodo = (!empty($fecha_inicio) && !empty($fecha_fin)) 
        ? "Período: $fecha_inicio al $fecha_fin" 
        : 'Todos los registros';
    
    $pdf->SetHeaderData('', 0, 'REPORTE DE ÁREAS MÁS UTILIZADAS', $periodo);
    
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    
    $pdf->AddPage();
    
    $html = '<style>
                table { border-collapse: collapse; width: 100%; font-size: 9px; }
                th { background-color: #4472C4; color: white; font-weight: bold; padding: 6px; text-align: center; }
                td { padding: 5px; border: 1px solid #ddd; }
             </style>';
    
    $html .= '<h3>Estadísticas de Áreas</h3>';
    $html .= '<p><strong>Total de áreas analizadas:</strong> ' . count($estadisticas) . '</p>';
    $html .= '<p><strong>Fecha de generación:</strong> ' . date('d/m/Y H:i:s') . '</p><br>';
    
    if (count($estadisticas) > 0) {
        $html .= '<table>
                    <thead>
                        <tr>
                            <th>Área</th>
                            <th>Ubicación</th>
                            <th>Capacidad</th>
                            <th>Total Reservas</th>
                            <th>Usuarios únicos</th>
                            <th>Horas Reservadas</th>
                            <th>Promedio Horas</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($estadisticas as $stat) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($stat['area_nombre']) . '</td>
                        <td>' . htmlspecialchars($stat['ubicacion'] ?? '') . '</td>
                        <td>' . $stat['capacidad'] . '</td>
                        <td>' . $stat['total_reservas'] . '</td>
                        <td>' . $stat['usuarios_unicos'] . '</td>
                        <td>' . number_format($stat['horas_reservadas'], 1) . '</td>
                        <td>' . number_format($stat['promedio_horas_por_reserva'], 1) . '</td>
                      </tr>';
        }
        
        $html .= '</tbody></table>';
    }
    
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('reporte_areas_' . date('Y-m-d') . '.pdf', 'D');
    exit;
}

function generarExcelEstadisticas($estadisticas, $fecha_inicio, $fecha_fin) {
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_areas_' . date('Y-m-d') . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    $sheet->setTitle('Estadísticas Áreas');
    
    // Encabezados
    $headers = ['Área', 'Ubicación', 'Capacidad', 'Total Reservas', 'Usuarios Únicos', 'Horas Reservadas', 'Promedio Horas'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $sheet->getStyle($col . '1')->getFont()->setBold(true);
        $sheet->getStyle($col . '1')->getFill()
               ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
               ->getStartColor()->setARGB('FF4472C4');
        $sheet->getStyle($col . '1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $col++;
    }
    
    // Datos
    $row = 2;
    foreach ($estadisticas as $stat) {
        $sheet->setCellValue('A' . $row, $stat['area_nombre']);
        $sheet->setCellValue('B' . $row, $stat['ubicacion'] ?? '');
        $sheet->setCellValue('C' . $row, $stat['capacidad']);
        $sheet->setCellValue('D' . $row, $stat['total_reservas']);
        $sheet->setCellValue('E' . $row, $stat['usuarios_unicos']);
        $sheet->setCellValue('F' . $row, number_format($stat['horas_reservadas'], 1));
        $sheet->setCellValue('G' . $row, number_format($stat['promedio_horas_por_reserva'], 1));
        $row++;
    }
    
    // Ajustar ancho de columnas
    foreach (range('A', 'G') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

function obtenerAreas() {
    global $datappi;
    
    $query = "SELECT area_id, nombre, ubicacion FROM Areas_Comunes WHERE estado = 0 ORDER BY nombre";
    $stmt = $datappi->prepare($query);
    $stmt->execute();
    $areas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'areas' => $areas
    ]);
}

function obtenerEstados() {
    global $datappi;
    
    $query = "SELECT id, nombre FROM estados ORDER BY nombre";
    $stmt = $datappi->prepare($query);
    $stmt->execute();
    $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'estados' => $estados
    ]);
}

function obtenerUsuarios() {
    global $datappi;
    
    $query = "SELECT id, nombre_completo, documento FROM usuarios WHERE estado = 1 ORDER BY nombre_completo LIMIT 100";
    $stmt = $datappi->prepare($query);
    $stmt->execute();
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'usuarios' => $usuarios
    ]);
}

function obtenerEstadisticasRapidas() {
    global $datappi;
    
    try {
        // Total reservas este mes
        $query1 = "SELECT COUNT(*) as total FROM reservas WHERE MONTH(fecha_reserva) = MONTH(CURDATE()) AND YEAR(fecha_reserva) = YEAR(CURDATE())";
        $stmt1 = $datappi->prepare($query1);
        $stmt1->execute();
        $totalReservas = $stmt1->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total áreas activas
        $query2 = "SELECT COUNT(*) as total FROM Areas_Comunes WHERE estado = 0";
        $stmt2 = $datappi->prepare($query2);
        $stmt2->execute();
        $totalAreas = $stmt2->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total usuarios
        $query3 = "SELECT COUNT(*) as total FROM usuarios WHERE estado = 1";
        $stmt3 = $datappi->prepare($query3);
        $stmt3->execute();
        $totalUsuarios = $stmt3->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Tasa de ocupación (simplificada)
        $tasaOcupacion = ($totalAreas > 0) ? round(($totalReservas / ($totalAreas * 30)) * 100, 1) : 0;
        
        echo json_encode([
            'success' => true,
            'total_reservas' => $totalReservas,
            'total_areas' => $totalAreas,
            'total_usuarios' => $totalUsuarios,
            'tasa_ocupacion' => $tasaOcupacion
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}

function obtenerReportesRecientes() {
    // Simulación de reportes recientes (podrías implementar una tabla de logs)
    echo json_encode([
        'success' => true,
        'reportes' => []
    ]);
}
?>