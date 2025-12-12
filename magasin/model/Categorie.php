<?php
final class Categorie
{
    private static $db = getDb();
    private const TABLE_CATEGORIE = 'categories';
    private const COLUMN_ID = 'id';
    private const COLUMN_TITLE = 'title';
    private const COLUMN_DESCRIPTION = 'description';

    public static function create_categorie($title, $description): void
    {
        $sql = "INSERT INTO :categories (:column_title, :column_description) VALUES (:title, :description)";

        $stmt = Categorie::$db->prepare($sql);

        $stmt->bindParam(':categories', Categorie::TABLE_CATEGORIE, PDO::PARAM_STR);
        $stmt->bindParam(':column_title', Categorie::COLUMN_TITLE, PDO::PARAM_STR);
        $stmt->bindParam(':column_description', Categorie::COLUMN_DESCRIPTION, PDO::PARAM_STR);

        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);

        $stmt->execute();
    }
    public static function edit_categorie($id, $title, $description, ): void
    {
        $sql = "UPDATE :categories SET :column_title = :title,  :column_description = :description) WHERE :column_id = :id";

        $stmt = Categorie::$db->prepare($sql);

        $stmt->bindParam(':categories', Categorie::TABLE_CATEGORIE, PDO::PARAM_STR);
        $stmt->bindParam(':column_title', Categorie::COLUMN_TITLE, PDO::PARAM_STR);
        $stmt->bindParam(':column_description', Categorie::COLUMN_DESCRIPTION, PDO::PARAM_STR);
        $stmt->bindParam(':column_id', Categorie::COLUMN_ID, PDO::PARAM_STR);

        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }
    public static function remove_categorie($id): void
    {
        $sql = "DELETE :categories WHERE id = :id";

        $stmt = Categorie::$db->prepare($sql);

        $stmt->bindParam(':categories', Categorie::TABLE_CATEGORIE, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        $stmt->execute();
    }
}