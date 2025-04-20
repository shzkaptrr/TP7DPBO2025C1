<?php
require_once '../config/db_resto.php';

class Menu {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    // Mendapatkan semua menu, dengan opsi pencarian berdasarkan nama_menu
    public function getAllMenus($keyword = '') {
        if ($keyword) {
            $stmt = $this->db->prepare("SELECT * FROM menus WHERE nama_menu LIKE ?");
            $stmt->execute(["%$keyword%"]);
        } else {
            $stmt = $this->db->query("SELECT * FROM menus");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMenu($nama_menu, $deskripsi, $harga) {
        $stmt = $this->db->prepare("INSERT INTO menus (nama_menu, deskripsi, harga) VALUES (?, ?, ?)");
        return $stmt->execute([$nama_menu, $deskripsi, $harga]);
    }

    public function updateMenu($id, $nama_menu, $deskripsi, $harga) {
        $stmt = $this->db->prepare("UPDATE menus SET nama_menu = ?, deskripsi = ?, harga = ? WHERE id = ?");
        return $stmt->execute([$nama_menu, $deskripsi, $harga, $id]);
    }

    public function deleteMenu($id) {
        $stmt = $this->db->prepare("DELETE FROM menus WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
