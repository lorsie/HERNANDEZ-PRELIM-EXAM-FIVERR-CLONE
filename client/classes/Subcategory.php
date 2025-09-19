<?php
/**
 * Class for handling Subcategory-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Subcategory extends Database {

    public function createSubcategory($category_id, $subcategory_name) {
        $sql = "INSERT INTO subcategories (category_id, subcategory_name) VALUES (?, ?)";
        return $this->executeNonQuery($sql, [$category_id, $subcategory_name]);
    }

    public function getSubcategories() {
        $sql = "SELECT s.*, c.category_name 
                FROM subcategories s
                JOIN categories c ON s.category_id = c.category_id
                ORDER BY c.category_name, s.subcategory_name ASC";
        return $this->executeQuery($sql);
    }

    public function getSubcategoriesByCategory($category_id) {
        $sql = "SELECT * FROM subcategories WHERE category_id = ? ORDER BY subcategory_name ASC";
        return $this->executeQuery($sql, [$category_id]);
    }

    public function updateSubcategory($subcategory_id, $subcategory_name, $category_id) {
        $sql = "UPDATE subcategories SET subcategory_name = ?, category_id = ? WHERE subcategory_id = ?";
        return $this->executeNonQuery($sql, [$subcategory_name, $category_id, $subcategory_id]);
    }

    public function deleteSubcategory($subcategory_id) {
        $sql = "DELETE FROM subcategories WHERE subcategory_id = ?";
        return $this->executeNonQuery($sql, [$subcategory_id]);
    }
}
?>