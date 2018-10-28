<?php
$error = null;
$filename = null;

if (!isset($_FILES['upload'])) {
    $error = 'A file must be uploaded';
} elseif ($_FILES['upload']['error'] !== UPLOAD_ERR_OK) {
    switch ($_FILES['upload']['error']) {
        case UPLOAD_ERR_INI_SIZE:
            $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
            break;
        case UPLOAD_ERR_PARTIAL:
            $error = 'The uploaded file was only partially uploaded.';
            break;
        case UPLOAD_ERR_NO_FILE:
            $error = 'No file was uploaded.';
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $error = 'Missing a temporary folder.';
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $error = 'Failed to write file to disk.';
            break;
        case UPLOAD_ERR_EXTENSION:
            $error = 'A PHP extension stopped the file upload.';
            break;
        default:
            $error = 'Unknown error with code: ' . $_FILES['upload']['error'];
            break;
    }
} else {
    $directory = __DIR__ . DIRECTORY_SEPARATOR . 'files';
    if (!is_dir($directory)) {
        mkdir($directory);
    }
    $filename = basename($_FILES['upload']['name']);
    move_uploaded_file($_FILES['upload']['tmp_name'], $directory . DIRECTORY_SEPARATOR . $filename);
}

if ($error) {
    $result = [
        'uploaded' => false,
        'error' => [
            'message' => $error,
        ],
    ];
} else {
    $result = [
        'uploaded' => true,
        'url' => '/upload/files/' . $filename,
    ];
}
header('Content-type: application/json');
echo json_encode($result);