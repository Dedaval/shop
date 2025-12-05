<?php
require_once 'utils.php';
require_once 'database/dbFunction.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Methods: OPTIONS, DELETE');
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode([ERRORS => 'Method no allow']);
    exit;
}

$token = getElementInHeader('Authorization');
if (str_contains($token, 'Token '))
    $token = str_replace('Token ', '', $token);

$errors = [];

if (is_null($token))
    $errors[] = [
        CODE => 400,
        ERRORS => [
            [FIELD => TOKEN, MESSAGE => "invalid.format"]
        ]
    ];
sendError($errors);
var_dump($token);

$dbResult = logoutUser($token);
if ($dbResult[CODE] === 204) {
    http_response_code(204);
    exit;
}
http_response_code($dbResult[CODE]);
echo json_encode($dbResult[ERRORS]);