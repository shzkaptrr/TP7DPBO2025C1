<?php
require_once '../config/db_resto.php';

class Order {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    public function getAllOrders($keyword = '') {
        if ($keyword) {
            $stmt = $this->db->prepare("SELECT orders.id, orders.order_code, orders.total_harga, orders.nomor_meja, orders.created_at, 
                                               users.id AS user_id, users.nama AS user_nama
                                        FROM orders
                                        JOIN users ON orders.user_id = users.id
                                        WHERE orders.order_code LIKE ?");
            $stmt->execute(["%$keyword%"]);
        } else {
            $stmt = $this->db->query("SELECT orders.id, orders.order_code, orders.total_harga, orders.nomor_meja, orders.created_at, 
                                             users.id AS user_id, users.nama AS user_nama
                                      FROM orders
                                      JOIN users ON orders.user_id = users.id");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function addOrder($order_code, $user_id, $total_harga, $nomor_meja) {
        $query = "
            INSERT INTO orders (order_code, user_id, total_harga, nomor_meja) 
            VALUES (?, ?, ?, ?)
        ";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$order_code, $user_id, $total_harga, $nomor_meja]);
    }

    public function updateOrder($id, $order_code, $user_id, $total_harga, $nomor_meja) {
        $query = "
            UPDATE orders 
            SET order_code = ?, user_id = ?, total_harga = ?, nomor_meja = ? 
            WHERE id = ?
        ";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$order_code, $user_id, $total_harga, $nomor_meja, $id]);
    }

    public function deleteOrder($id) {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
}
?>
