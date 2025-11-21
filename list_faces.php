<?php
$dir = 'labeled_images';
$files = array_diff(scandir($dir), array('..', '.'));
$images = [];

foreach ($files as $file) {
    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
        $images[] = $file;
    }
}

header('Content-Type: application/json');
echo json_encode(array_values($images));
?>
