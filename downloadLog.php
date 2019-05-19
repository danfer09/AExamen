<?php
if(isset($_REQUEST["file"])){
    // Obtenemos parámetros
    $file = urldecode($_REQUEST["file"]); // Decodificación de la url del archivo
    $filepath = "log/" . $file;
    
    // Proceso de descarga del archivo de log
    if(file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush();
        readfile($filepath);
        exit;
    }
}
?>