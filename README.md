# Janji
Saya Shizuka Maulia Putri dengan NIM 2308744 mengerjakan Tugas Praktikum 7 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

# Struktur Database

![image](https://github.com/user-attachments/assets/58dd0d4a-188e-4dc3-858b-517ca1fff11e)

Struktur database ini dibuat menggunakan Laragon sebagai local server dan HeidiSQL sebagai database management tool-nya.
Struktur database ini terdiri dari empat tabel utama yang saling terhubung, yaitu menus, users, orders, dan order_items. 
Tabel menus digunakan untuk menyimpan informasi tentang daftar menu yang tersedia, dengan kolom seperti id sebagai identitas unik, nama_menu, deskripsi, dan harga. 
Tabel users menyimpan data pelanggan yang melakukan pemesanan, seperti id, nama, email, dan phone.
Tabel orders menyimpan informasi pemesanan yang dilakukan oleh pelanggan, termasuk order_code sebagai kode unik tiap pesanan, user_id sebagai fk dari tabel users, total_harga, nomor_meja, dan waktu pembuatan pesanan melalui kolom created_at. Tabel ini memiliki relasi dengan users, sehingga setiap pesanan terhubung dengan satu pengguna. 
Tabel order_items berisi detail dari setiap item yang dipesan dalam satu pesanan, seperti menu_id yang mengacu ke menu yang dipesan, order_id yang mengacu ke pesanan, quantity atau jumlah item, harga satuan saat dipesan, dan subtotal yang otomatis dihitung dari harga dikali jumlah. 
Penghapusan dalam tabel ini menggunakan (ON DELETE CASCADE), sehingga saat data utamanya dihapus, data yang terkaitnya juga ikut terhapus secara otomatis. 

# Fungsi dalam kelas
1. Kelas Menu

getAllMenus($keyword = '') : mengambil semua data menu dari database. Jika diberikan keyword, maka data yang diambil hanya yang nama_menu-nya mengandung kata kunci tersebut (fitur pencarian). Kalau tidak ada keyword, maka semua data dari tabel menus akan ditampilkan.
addMenu($nama_menu, $deskripsi, $harga)
Fungsi ini digunakan untuk menambahkan data menu baru ke tabel menus. Data yang dimasukkan berupa nama_menu, deskripsi, dan harga.
updateMenu($id, $nama_menu, $deskripsi, $harga)
Fungsi ini digunakan untuk memperbarui data dari menu yang sudah ada berdasarkan id-nya. Informasi yang bisa diperbarui adalah nama_menu, deskripsi, dan harga.
deleteMenu($id)
Fungsi ini digunakan untuk menghapus menu berdasarkan id-nya. Menu dengan id yang sesuai akan dihapus dari tabel menus.

2. Kelas User
getAllUsers($keyword = '')
 mengambil semua data user dari database. kalau ada keyword, akan mencari user berdasarkan nama yang mengandung keyword tersebut.
addUser($nama, $email, $phone)
 menambahkan data user baru ke tabel users, lengkap dengan nama, email, dan nomor telepon.
updateUser($id, $nama, $email, $phone)
 memperbarui data user berdasarkan id. kolom yang bisa diubah adalah nama, email, dan phone.
deleteUser($id)
 menghapus user dari database berdasarkan id yang dipilih.

3. Kelas order
getAllOrders($keyword = '')
 mengambil semua data pesanan dari tabel orders, lalu digabung (JOIN) dengan tabel users untuk menampilkan nama pelanggan. kalau ada keyword, pencarian dilakukan berdasarkan kode pesanan (order_code).
addOrder($order_code, $user_id, $total_harga, $nomor_meja)
 menambahkan data pesanan baru ke tabel orders, termasuk kode pesanan, ID user, total harga, dan nomor meja.
updateOrder($id, $order_code, $user_id, $total_harga, $nomor_meja)
 memperbarui data pesanan berdasarkan id. field yang bisa diubah yaitu kode pesanan, ID user, total harga, dan nomor meja.
deleteOrder($id)
 menghapus data pesanan dari database berdasarkan id.

4. Kelas order_items
getAllOrderItems($keyword = '')
Mengambil semua data item order. Jika $keyword diisi, hanya menampilkan item dengan nama menu yang mengandung keyword.
getOrderItemsByOrderId($order_id)
Mengambil semua item berdasarkan ID order tertentu.
getOrderItemById($id)
Mengambil satu item order berdasarkan ID item.
addOrderItem($order_id, $menu_id, $quantity, $harga)
Menambahkan item ke dalam order.
updateOrderItem($id, $order_id, $menu_id, $quantity, $harga)
Mengupdate data item order berdasarkan ID item.
deleteOrderItem($id)
Menghapus item order berdasarkan ID item.
recalculateOrderTotal($order_id)
Menghitung ulang total harga order dari semua item dan memperbarui kolom total_harga di tabel orders.





