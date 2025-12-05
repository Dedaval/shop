<?php
require_once 'utils.php';
require_once 'database/dbFunction.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Methods: OPTIONS, POST');
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([ERRORS => 'Method no allow']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$errors = [];

$requiredField = [EMAIL, PASSWORD];
foreach ($requiredField as $field) {
    if (empty($data[$field])) {
        $errors[] = [
            CODE => 400,
            ERRORS => [
                [FIELD => $field, MESSAGE => "invalid.format"]
            ]
        ];
    }
}
sendError($errors);

$dbResult = checkUser($data);
if ($dbResult[CODE] === 200) {
    http_response_code(200);
    echo json_encode([TOKEN => $dbResult[TOKEN][TOKEN]]);
    exit;
}
http_response_code($dbResult[CODE]);
echo json_encode($dbResult[ERRORS]);