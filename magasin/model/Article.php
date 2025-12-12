<?php
final class Article{
    private $db = getDb();
    public function create_article($title, $description, $amount, $currency){
        $sql = "INSERT INTO articles (title, description, amount, currency) VALUES (:title, :description, :amount, :currency)";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':currency', $currency, PDO::PARAM_STR);

        $stmt->execute();
    }
    public function edit_article($id, $title, $description, $amount, $currency){
        $sql = "UPDATE articles SET title = :title,  description = :description, amount = :amount, currency = :currency) WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
        $stmt->bindParam(':currency', $currency, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }
    public function remove_article($id){
        $sql = "DELETE articles WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }
}