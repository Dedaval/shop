<?php
define('EMAIL', 'email');
define('PASSWORD', 'password');
define('TOKEN', 'token');
define('NAME', 'name');
define('DESCRIPTION', 'description');
define('AMOUNT', 'amount');
define('MIN_CHARACTERS_PASSWORD', 8);
define('MIN_CHARACTERS_NAME', 3);
define('MIN_CHARACTERS_DESCRIPTION', 10);
define('MIN_AMOUNT', 0);
define('FIELD', 'field');
define('MESSAGE', 'message');
define('ID_ARTICLE', 'article');
define('CODE', 'code');
define('ERRORS', 'errors');
define('SERVER', 'server');
function emailValidate(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function passwordValidate(string $password): bool
{
    return $password >= MIN_CHARACTERS_PASSWORD;
}
function generateToken($length = 32): array
{
    return [CODE => 201, TOKEN => md5(random_bytes($length))];
}
function nameValidate(string $name): bool
{
    return strlen($name) >= MIN_CHARACTERS_NAME;
}
function descriptionValidate(string $description): bool
{
    return strlen($description) >= MIN_CHARACTERS_DESCRIPTION;
}
function amountValidate($amount): bool
{
    return is_numeric($amount) && $amount >= MIN_AMOUNT;
}
function sendError(array $result): void
{
    if (empty($result))
        return;
    foreach ($result as $error) {
        http_response_code($error[CODE]);
        echo json_encode($error[ERRORS]);
    }
    exit;
}
function getElementInHeader(string $key): ?string
{
    $headers = apache_request_headers();
    if (isset($headers[$key]))
        return $headers[$key];
    return null;
}