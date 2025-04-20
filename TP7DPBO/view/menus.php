<?php
require_once '../class/Menu.php';
include 'header.php';

$menu = new Menu();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_menu'])) {
    $menu->addMenu($_POST['nama_menu'], $_POST['deskripsi'], $_POST['harga']);
    header("Location: menus.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_menu'])) {
    $menu->updateMenu($_POST['id'], $_POST['nama_menu'], $_POST['deskripsi'], $_POST['harga']);
    header("Location: menus.php");
    exit;
}

if (isset($_GET['delete_menu'])) {
    $menu->deleteMenu($_GET['delete_menu']);
    header("Location: menus.php");
    exit;
}

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$data = $menu->getAllMenus($keyword);
?>

<div class="max-w-5xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold mb-4">📋 Daftar Menu</h2>

    <!-- Tombol Tambah -->
    <button onclick="toggleForm()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4">
        + Tambah Menu
    </button>

    <!-- Form Cari -->
    <form method="GET" class="mb-6 flex gap-2">
        <input type="text" name="keyword" placeholder="Cari nama menu..." value="<?= htmlspecialchars($keyword) ?>"
            class="border border-gray-300 rounded px-3 py-2 w-full">
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Cari</button>
    </form>

    <!-- Form Tambah Menu -->
    <div id="formContainer" class="mb-6 hidden">
        <form method="POST" class="bg-white shadow-md rounded px-6 py-4 space-y-4">
            <input type="hidden" name="add_menu" value="1">
            <div>
                <label class="block font-semibold mb-1">Nama Menu:</label>
                <input type="text" name="nama_menu" required class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block font-semibold mb-1">Deskripsi:</label>
                <textarea name="deskripsi" required class="w-full border px-3 py-2 rounded"></textarea>
            </div>
            <div>
                <label class="block font-semibold mb-1">Harga:</label>
                <input type="number" name="harga" step="0.01" required class="w-full border px-3 py-2 rounded">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>

    <!-- Form Update Menu -->
    <div id="updateFormContainer" class="mb-6 hidden">
        <form method="POST" class="bg-yellow-50 shadow-md rounded px-6 py-4 space-y-4">
            <input type="hidden" name="update_menu" value="1">
            <input type="hidden" name="id" id="updateId">
            <div>
                <label class="block font-semibold mb-1">Nama Menu:</label>
                <input type="text" name="nama_menu" id="updateNamaMenu" required class="w-full border px-3 py-2 rounded">
            </div>
            <div>
                <label class="block font-semibold mb-1">Deskripsi:</label>
                <textarea name="deskripsi" id="updateDeskripsi" required class="w-full border px-3 py-2 rounded"></textarea>
            </div>
            <div>
                <label class="block font-semibold mb-1">Harga:</label>
                <input type="number" name="harga" id="updateHarga" step="0.01" required class="w-full border px-3 py-2 rounded">
            </div>
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>

    <!-- Tabel Menu -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 shadow-md rounded">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Nama Menu</th>
                    <th class="px-4 py-2 border">Deskripsi</th>
                    <th class="px-4 py-2 border">Harga</th>
                    <th class="px-4 py-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border text-center"><?= $row['id'] ?></td>
                    <td class="px-4 py-2 border"><?= $row['nama_menu'] ?></td>
                    <td class="px-4 py-2 border"><?= $row['deskripsi'] ?></td>
                    <td class="px-4 py-2 border">Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td class="px-4 py-2 border space-x-2">
                        <button onclick="showUpdateForm(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nama_menu'], ENT_QUOTES) ?>', '<?= htmlspecialchars($row['deskripsi'], ENT_QUOTES) ?>', <?= $row['harga'] ?>)" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Edit</button>
                        <a href="?delete_menu=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus menu ini?')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (count($data) === 0): ?>
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">Tidak ada data menu.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleForm() {
    const form = document.getElementById('formContainer');
    form.classList.toggle('hidden');
}

function showUpdateForm(id, nama_menu, deskripsi, harga) {
    document.getElementById('updateId').value = id;
    document.getElementById('updateNamaMenu').value = nama_menu;
    document.getElementById('updateDeskripsi').value = deskripsi;
    document.getElementById('updateHarga').value = harga;
    document.getElementById('updateFormContainer').classList.remove('hidden');
}
</script>

<?php include 'footer.php'; ?>
