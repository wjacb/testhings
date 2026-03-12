<?php

$url = "http://emoticon.hansigan.com/detail.php?type=kakao&srl=786";
$destino = "C:/Temp/";

$html = file_get_contents($url);

preg_match_all('/https?:\/\/[^"]+\.gif/i', $html, $matches);

foreach ($matches[0] as $gif) {

    // Ignorar si la URL contiene thum o thumb
    if (stripos($gif, 'thum') !== false) {
        continue;
    }

    $nombreArchivo = basename(parse_url($gif, PHP_URL_PATH));
    $rutaLocal = $destino . $nombreArchivo;

    echo "Descargando: $gif\n";

    $contenido = file_get_contents($gif);

    if ($contenido !== false) {
        file_put_contents($rutaLocal, $contenido);
        echo "Guardado en: $rutaLocal\n\n";
    } else {
        echo "No se pudo descargar\n\n";
    }
}

echo "Proceso terminado\n";