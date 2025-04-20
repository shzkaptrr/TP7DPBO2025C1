<?php
require_once '../config/db_resto.php';

class OrderItem {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    public function getAllOrderItems($keyword = '') {
        if ($keyword) {
            $stmt = $this->db->prepare("SELECT oi.id, oi.order_id, oi.menu_id, oi.quantity, oi.harga, oi.subtotal, 
                                            o.order_code, m.nama_menu 
                                        FROM order_items oi
                                        JOIN orders o ON oi.order_id = o.id
                                        JOIN menus m ON oi.menu_id = m.id
                                        WHERE m.nama_menu LIKE ?");
            $stmt->execute(["%$keyword%"]);
        } else {
            $stmt = $this->db->query("SELECT oi.id, oi.order_id, oi.menu_id, oi.quantity, oi.harga, oi.subtotal, 
                                        o.order_code, m.nama_menu 
                                    FROM order_items oi
                                    JOIN orders o ON oi.order_id = o.id
                                    JOIN menus m ON oi.menu_id = m.id");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getOrderItemsByOrderId($order_id) {
        $stmt = $this->db->prepare("SELECT oi.id, oi.order_id, oi.menu_id, oi.quantity, oi.harga, oi.subtotal, 
                                    o.order_code, m.nama_menu 
                                FROM order_items oi
                                JOIN orders o ON oi.order_id = o.id
                                JOIN menus m ON oi.menu_id = m.id
                                WHERE oi.order_id = ?");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderItemById($id) {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Menambahkan item ke order
    public function addOrderItem($order_id, $menu_id, $quantity, $harga) {
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, menu_id, quantity, harga) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$order_id, $menu_id, $quantity, $harga]);
    }

    // Mengupdate item order
    public function updateOrderItem($id, $order_id, $menu_id, $quantity, $harga) {
        $stmt = $this->db->prepare("UPDATE order_items SET order_id = ?, menu_id = ?, quantity = ?, harga = ? WHERE id = ?");
        if ($stmt->execute([$order_id, $menu_id, $quantity, $harga, $id])) {
            return true;
        } else {
            print_r($stmt->errorInfo());  // Debugging error SQL
            return false;
        }
    }

    // Menghapus item order
    public function deleteOrderItem($id) {
        $stmt = $this->db->prepare("DELETE FROM order_items WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Menghitung ulang total harga untuk order tertentu
    public function recalculateOrderTotal($order_id) {
        $stmt = $this->db->prepare("SELECT SUM(subtotal) as total FROM order_items WHERE order_id = ?");
        $stmt->execute([$order_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $total = $result['total'] ?: 0;
        
        // Update total di tabel orders
        $updateStmt = $this->db->prepare("UPDATE orders SET total_harga = ? WHERE id = ?");
        return $updateStmt->execute([$total, $order_id]);
    }
}
?>