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

$requiredField = [NAME, DESCRIPTION];
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

if (!nameValidate($data[NAME])) {
    $errors[] = [
        CODE => 400,
        ERRORS => [
            [FIELD => NAME, MESSAGE => "invalid.format"]
        ]
    ];
}
if (!descriptionValidate($data[DESCRIPTION])) {
    $errors[] = [
        CODE => 400,
        ERRORS => [
            [FIELD => PASSWORD, MESSAGE => "invalid.format"]
        ]
    ];
}
sendError($errors);

$dbResult = createSectionDb($data);
if ($dbResult[CODE] === 201) {
    http_response_code(201);
    echo json_encode([TOKEN => $dbResult[TOKEN][TOKEN]]);
    exit;
}
http_response_code($dbResult[CODE]);
echo json_encode([$dbResult[ERRORS]]);