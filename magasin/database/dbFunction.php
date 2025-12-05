<?php
require_once 'dbUtil.php';
require_once 'utils.php';
function createUserDb(array $data): array
{
    $email = $data[EMAIL];
    $password = password_hash($data[PASSWORD], PASSWORD_DEFAULT);
    $token = generateToken();
    $db = getDb();
    $sql = "INSERT INTO users(email, password, token) VALUES (:email, :password, :token) FOR UPDATE NOWAIT";

    $db->beginTransaction();
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':token', $token[TOKEN], PDO::PARAM_STR);
        $stmt->execute();
        $db->commit();
        return [CODE => 201, TOKEN => $token];
    } catch (PDOException $e) {
        $db->rollBack();
        if ($e->getCode() == '23000') {
            return [
                CODE => 409,
                ERRORS => [
                    [FIELD => EMAIL, MESSAGE => 'already.used']
                ]
            ];
        }
        if ($e->getCode() == '40001') {
            return [
                CODE => 409,
                ERRORS => [
                    [FIELD => EMAIL, MESSAGE => 'Please retry']
                ]
            ];
        }
        return [
            CODE => 500,
            ERRORS => [
                [FIELD => SERVER, MESSAGE => 'server.error']
            ]
        ];
    } catch (Exception $e) {
        $db->rollBack();
        return [
            CODE => 500,
            ERRORS => [
                [FIELD => SERVER, MESSAGE => 'server.error']
            ]
        ];
    }
}

function checkUser(array $data): array
{
    $email = $data[EMAIL];
    $password = $data[PASSWORD];
    $token = generateToken();
    $db = getDb();
    $sql = "SELECT password, token FROM users UPDATE users SET token = :token WHERE email = :email";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam('email', $email, PDO::PARAM_STR);
        $stmt->bindParam('token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user[PASSWORD]))
            return [CODE => 200, TOKEN => $token];
        return [
            CODE => 400,
            ERRORS => [
                MESSAGE => 'invalid.credentials'
            ]
        ];

    } catch (\Throwable $th) {
        return [
            CODE => 500,
            ERRORS => [
                [FIELD => SERVER, MESSAGE => 'server.error']
            ]
        ];
    }
}
function logoutUser(string $token): array
{
    $db = getDb();
    $sql = "UPDATE users SET token = NULL WHERE token = :token";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return [CODE => 204];
        }
        return [
            CODE => 404,
            ERRORS => [
                [FIELD => TOKEN, MESSAGE => 'token.invalid']
            ]
        ];

    } catch (PDOException $e) {
        return [
            CODE => 500,
            ERRORS => [
                [FIELD => SERVER, MESSAGE => 'server.error']
            ]
        ];
    } catch (\Throwable $th) {
        return [
            CODE => 500,
            ERRORS => [
                [FIELD => SERVER, MESSAGE => 'server.error']
            ]
        ];
    }
}