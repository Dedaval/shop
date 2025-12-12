<?php
final class User
{
    private $db = getDb();
    public function create_user(string $email, string $password, string $token): void
    {
        $sql = "INSERT INTO users (email, password, token) VALUES (:email, :password, :token)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function edit_user(string $email, string $password, string $token): void
    {
        $sql = "UPDATE users SET email = :email, password = :password, token = :token WHERE token = :token";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function remove_user(string $token){
        $sql = "DELETE users WHERE token = :token";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':token', $token, PDO::PARAM_STR);

        $stmt->execute();
    }
    public function logout_user(string $token){
        $sql = "UPDATE users SET token = NULL WHERE token = :token";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':token', $token, PDO::PARAM_STR);

        $stmt->execute();
    }
}
