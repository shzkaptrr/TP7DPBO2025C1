<!-- user.php -->

<?php
require_once '../config/db_resto.php';

class User {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    public function getAllUsers($keyword = '') {
        if ($keyword) {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE nama LIKE ?");
            $stmt->execute(["%$keyword%"]);
        } else {
            $stmt = $this->db->query("SELECT * FROM users");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // add user
    public function addUser($nama, $email, $phone) {
        $stmt = $this->db->prepare("INSERT INTO users (nama, email, phone) VALUES (?, ?, ?)");
        return $stmt->execute([$nama, $email, $phone]);
    }

    // update user
    public function updateUser($id, $nama, $email, $phone) {
        $query = "UPDATE users SET nama = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nama, $email, $phone, $id]);
    }

    // delete user
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    
    
}
?>
