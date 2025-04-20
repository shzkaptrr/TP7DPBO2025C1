<?php
require_once '../class/OrderItem.php';
require_once '../class/Order.php';
require_once '../class/Menu.php';
include 'header.php';

$orderItem = new OrderItem();
$order = new Order();
$menu = new Menu();

// Filter order items berdasarkan order_id jika ada
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

// Proses tambah order item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_order_item'])) {
    $order_id = $_POST['order_id'];
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];
    $harga = $_POST['harga'];
    
    if($orderItem->addOrderItem($order_id, $menu_id, $quantity, $harga)) {
        // Recalculate order total
        $orderItem->recalculateOrderTotal($order_id);
        header("Location: order_items.php" . ($order_id ? "?order_id=$order_id" : ""));
        exit;
    }
}

// Proses update order item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order_item'])) {
    $id = $_POST['id'];
    $order_id = $_POST['order_id'];
    $menu_id = $_POST['menu_id'];
    $quantity = $_POST['quantity'];
    $harga = $_POST['harga'];
    
    if($orderItem->updateOrderItem($id, $order_id, $menu_id, $quantity, $harga)) {
        // Recalculate order total
        $orderItem->recalculateOrderTotal($order_id);
        header("Location: order_items.php" . ($order_id ? "?order_id=$order_id" : ""));
        exit;
    }
}

// Proses delete order item
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $item = $orderItem->getOrderItemById($_GET['id']);
    if ($item && $orderItem->deleteOrderItem($_GET['id'])) {
        // Recalculate order total
        $orderItem->recalculateOrderTotal($item['order_id']);
        header("Location: order_items.php" . ($order_id ? "?order_id=$order_id" : ""));
        exit;
    }
}

// Ambil data untuk ditampilkan
if ($order_id) {
    $data = $orderItem->getOrderItemsByOrderId($order_id);
    $orderInfo = "untuk Order #" . $order_id;
} else {
    $keyword = isset($_GET['search']) ? $_GET['search'] : '';
    $data = $orderItem->getAllOrderItems($keyword);
    $orderInfo = "";
}


// Ambil semua order dan menu untuk dropdown
$orders = $order->getAllOrders();
$menus = $menu->getAllMenus();

// Jika diperlukan untuk mengambil harga menu berdasarkan ID
if (isset($_GET['get_menu_price']) && $_GET['menu_id']) {
    $menu_id = $_GET['menu_id'];
    $menuData = array_filter($menus, function($item) use ($menu_id) {
        return $item['id'] == $menu_id;
    });
    
    if (!empty($menuData)) {
        $menuItem = reset($menuData);
        echo $menuItem['harga'];
    } else {
        echo "0";
    }
    exit;
}
?>

<h2 class="text-2xl font-semibold mb-4">Daftar Item Order <?= $orderInfo ?></h2>
<form method="GET" class="mb-4 flex gap-2">
    <input type="text" name="search" placeholder="Cari berdasarkan nama menu..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" class="px-4 py-2 border rounded w-full">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
</form>



<!-- Tombol toggle -->
<button onclick="toggleOrderItemForm()"
    class="bg-green-600 text-white px-4 py-2 rounded-lg mb-4 hover:bg-green-700 transition">+ Tambah Item Order</button>

<!-- Form Tambah Order Item -->
<div id="orderItemFormContainer" class="mb-6 hidden">
    <form method="POST" class="bg-gray-50 p-4 rounded-lg shadow-md space-y-3">
        <input type="hidden" name="add_order_item" value="1">

        <div>
            <label class="block font-medium">Order:</label>
            <select name="order_id" required <?= $order_id ? 'disabled' : '' ?>
                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">-- Pilih Order --</option>
                <?php foreach ($orders as $o): ?>
                <option value="<?= $o['id'] ?>" <?= ($order_id == $o['id']) ? 'selected' : '' ?>>
                    <?= $o['order_code'] ?> - Meja <?= $o['nomor_meja'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($order_id): ?>
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <?php endif; ?>
        </div>

        <div>
            <label class="block font-medium">Menu:</label>
            <select name="menu_id" id="menuSelect" onchange="updateHarga()" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">-- Pilih Menu --</option>
                <?php foreach ($menus as $m): ?>
                <option value="<?= $m['id'] ?>" data-harga="<?= $m['harga'] ?>">
                    <?= $m['nama_menu'] ?> - Rp<?= number_format($m['harga'], 0, ',', '.') ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Harga:</label>
                <input type="number" name="harga" id="hargaInput" step="0.01"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <div>
                <label class="block font-medium">Jumlah:</label>
                <input type="number" name="quantity" value="1" min="1"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
        </div>

        <button type="submit"
            class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Simpan</button>
    </form>
</div>

<!-- Form Update Order Item -->
<div id="updateOrderItemFormContainer" class="mb-6 hidden">
    <form method="POST" class="bg-yellow-50 p-4 rounded-lg shadow-md space-y-3">
        <input type="hidden" name="update_order_item" value="1">
        <input type="hidden" name="id" id="updateId">

        <div>
            <label class="block font-medium">Order:</label>
            <select name="order_id" id="updateOrderId" required <?= $order_id ? 'disabled' : '' ?>
                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">-- Pilih Order --</option>
                <?php foreach ($orders as $o): ?>
                <option value="<?= $o['id'] ?>"><?= $o['order_code'] ?> - Meja <?= $o['nomor_meja'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($order_id): ?>
            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <?php endif; ?>
        </div>

        <div>
            <label class="block font-medium">Menu:</label>
            <select name="menu_id" id="updateMenuId" onchange="updateHargaEdit()" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2">
                <option value="">-- Pilih Menu --</option>
                <?php foreach ($menus as $m): ?>
                <option value="<?= $m['id'] ?>" data-harga="<?= $m['harga'] ?>">
                    <?= $m['nama_menu'] ?> - Rp<?= number_format($m['harga'], 0, ',', '.') ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Harga:</label>
                <input type="number" name="harga" id="updateHarga" step="0.01"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
            <div>
                <label class="block font-medium">Jumlah:</label>
                <input type="number" name="quantity" id="updateQuantity" value="1" min="1"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
            </div>
        </div>

        <button type="submit"
            class="mt-2 bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">Update</button>
    </form>
</div>

<!-- Tabel -->
<div class="overflow-x-auto mb-6">
    <table class="min-w-full text-sm border border-gray-200 shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Order</th>
                <th class="px-4 py-2 border">Menu</th>
                <th class="px-4 py-2 border">Harga Satuan</th>
                <th class="px-4 py-2 border">Jumlah</th>
                <th class="px-4 py-2 border">Subtotal</th>
                <th class="px-4 py-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($data)): ?>
            <tr>
                <td colspan="7" class="text-center py-4">Tidak ada data</td>
            </tr>
            <?php else: ?>
            <?php foreach ($data as $row): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 border"><?= $row['id'] ?></td>
                <td class="px-4 py-2 border"><?= $row['order_code'] ?></td>
                <td class="px-4 py-2 border"><?= $row['nama_menu'] ?></td>
                <td class="px-4 py-2 border">Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td class="px-4 py-2 border"><?= $row['quantity'] ?></td>
                <td class="px-4 py-2 border">Rp<?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                <td class="px-4 py-2 border space-x-2">
                    <button onclick="showUpdateOrderItemForm(<?= $row['id'] ?>, <?= $row['order_id'] ?>, <?= $row['menu_id'] ?>, <?= $row['harga'] ?>, <?= $row['quantity'] ?>)"
                        class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">Edit</button>
                    <a href="order_items.php?delete=1&id=<?= $row['id'] ?><?= $order_id ? "&order_id=$order_id" : "" ?>"
                        onclick="return confirm('Yakin ingin menghapus item ini?')"
                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($order_id): ?>
    <p><a href="orders.php" class="text-blue-600 hover:underline">← Kembali ke Daftar Order</a></p>
<?php endif; ?>


<script>
function toggleOrderItemForm() {
    const form = document.getElementById('orderItemFormContainer');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function updateHarga() {
    const menuSelect = document.getElementById('menuSelect');
    const hargaInput = document.getElementById('hargaInput');
    
    if (menuSelect.selectedIndex > 0) {
        const harga = menuSelect.options[menuSelect.selectedIndex].getAttribute('data-harga');
        hargaInput.value = harga;
    } else {
        hargaInput.value = '';
    }
}

function updateHargaEdit() {
    const menuSelect = document.getElementById('updateMenuId');
    const hargaInput = document.getElementById('updateHarga');
    
    if (menuSelect.selectedIndex > 0) {
        const harga = menuSelect.options[menuSelect.selectedIndex].getAttribute('data-harga');
        hargaInput.value = harga;
    } else {
        hargaInput.value = '';
    }
}

function showUpdateOrderItemForm(id, order_id, menu_id, harga, quantity) {
    document.getElementById('updateId').value = id;
    
    // Jika order_id ada di URL, form akan menggunakan hidden input
    const updateOrderIdElement = document.getElementById('updateOrderId');
    if (updateOrderIdElement) {
        updateOrderIdElement.value = order_id;
    }
    
    document.getElementById('updateMenuId').value = menu_id;
    document.getElementById('updateHarga').value = harga;
    document.getElementById('updateQuantity').value = quantity;
    
    document.getElementById('updateOrderItemFormContainer').style.display = 'block';
}
</script>

<?php include 'footer.php'; ?>