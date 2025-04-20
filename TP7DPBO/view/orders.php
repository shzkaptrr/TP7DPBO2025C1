<?php
require_once '../class/Order.php';
require_once '../class/User.php';
include 'header.php';

$order = new Order();
$user = new User();

// handle tambah order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_order'])) {
    $order->addOrder(
        $_POST['order_code'],
        $_POST['user_id'],
        $_POST['total_harga'],
        $_POST['nomor_meja']
    );
    header("Location: orders.php");
    exit;
}

// handle update order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    $order->updateOrder(
        $_POST['id'],
        $_POST['order_code'],
        $_POST['user_id'],
        $_POST['total_harga'],
        $_POST['nomor_meja']
    );
    header("Location: orders.php");
    exit;
}

// handle delete
if (isset($_GET['delete_id'])) {
    $order->deleteOrder($_GET['delete_id']);
    header("Location: orders.php");
    exit;
}

$keyword = isset($_GET['search']) ? $_GET['search'] : '';
$data = $order->getAllOrders($keyword);
$users = $user->getAllUsers();

?>

<h2 class="text-2xl font-bold mb-4">Daftar Order</h2>

<form method="GET" class="mb-4 flex gap-2">
    <input type="text" name="search" placeholder="Cari kode order..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="px-4 py-2 border rounded w-full">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
</form>

<button onclick="toggleOrderForm()" class="bg-green-500 text-white px-4 py-2 rounded mb-4">+ Tambah Order</button>

<!-- Tambah -->
<div id="orderFormContainer" class="mb-6 hidden bg-white p-4 border rounded shadow">
    <form method="POST" class="space-y-4">
        <input type="hidden" name="add_order" value="1">
        <div>
            <label class="block font-medium">Kode Order:</label>
            <input type="text" name="order_code" required class="w-full px-4 py-2 border rounded">
        </div>
        <div>
            <label class="block font-medium">User:</label>
            <select name="user_id" required class="w-full px-4 py-2 border rounded">
                <option value="">-- Pilih User --</option>
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u['id'] ?>"><?= $u['nama'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block font-medium">Total Harga:</label>
            <input type="number" name="total_harga" step="0.01" required class="w-full px-4 py-2 border rounded">
        </div>
        <div>
            <label class="block font-medium">Nomor Meja:</label>
            <input type="number" name="nomor_meja" class="w-full px-4 py-2 border rounded">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>

<!-- Update -->
<div id="updateOrderFormContainer" class="mb-6 hidden bg-white p-4 border rounded shadow">
    <form method="POST" class="space-y-4">
        <input type="hidden" name="update_order" value="1">
        <input type="hidden" name="id" id="updateId">
        <div>
            <label class="block font-medium">Kode Order:</label>
            <input type="text" name="order_code" id="updateOrderCode" required class="w-full px-4 py-2 border rounded">
        </div>
        <div>
            <label class="block font-medium">User:</label>
            <select name="user_id" id="updateUserId" required class="w-full px-4 py-2 border rounded">
                <option value="">-- Pilih User --</option>
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u['id'] ?>"><?= $u['nama'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block font-medium">Total Harga:</label>
            <input type="number" name="total_harga" id="updateTotalHarga" step="0.01" required class="w-full px-4 py-2 border rounded">
        </div>
        <div>
            <label class="block font-medium">Nomor Meja:</label>
            <input type="number" name="nomor_meja" id="updateNomorMeja" class="w-full px-4 py-2 border rounded">
        </div>
        <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>

<!-- Table -->
<div class="overflow-x-auto">
    <table class="w-full table-auto border border-gray-300 rounded-lg shadow">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Kode Order</th>
                <th class="px-4 py-2 border">Nama User</th>
                <th class="px-4 py-2 border">Total Harga</th>
                <th class="px-4 py-2 border">Nomor Meja</th>
                <th class="px-4 py-2 border">Waktu</th>
                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 border"><?= $row['id'] ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($row['order_code']) ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($row['user_nama']) ?></td>
                <td class="px-4 py-2 border">Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                <td class="px-4 py-2 border"><?= $row['nomor_meja'] ?></td>
                <td class="px-4 py-2 border"><?= $row['created_at'] ?></td>
                <td class="px-4 py-2 border space-x-2">
                    <button onclick="showUpdateOrderForm(
                        <?= $row['id'] ?>,
                        '<?= addslashes($row['order_code']) ?>',
                        <?= $row['user_id'] ?>,
                        <?= $row['total_harga'] ?>,
                        <?= $row['nomor_meja'] ?>
                    )" class="bg-yellow-400 text-white px-3 py-1 rounded">Edit</button>

                    <a href="orders.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus order ini?');" class="inline-block">
                        <button class="bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script>
function toggleOrderForm() {
    const form = document.getElementById('orderFormContainer');
    form.classList.toggle('hidden');
}

function showUpdateOrderForm(id, order_code, user_id, total_harga, nomor_meja) {
    document.getElementById('updateId').value = id;
    document.getElementById('updateOrderCode').value = order_code;
    document.getElementById('updateUserId').value = user_id;
    document.getElementById('updateTotalHarga').value = total_harga;
    document.getElementById('updateNomorMeja').value = nomor_meja;

    document.getElementById('updateOrderFormContainer').classList.remove('hidden');
}
</script>


<?php include 'footer.php'; ?>
