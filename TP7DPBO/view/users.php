<?php
require_once '../class/User.php';
include 'header.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $user->addUser($_POST['nama'], $_POST['email'], $_POST['phone']);
    header("Location: users.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user->updateUser($_POST['id'], $_POST['nama'], $_POST['email'], $_POST['phone']);
    header("Location: users.php");
    exit;
}

if (isset($_GET['delete_id'])) {
    $user->deleteUser($_GET['delete_id']);
    header("Location: users.php");
    exit;
}

$keyword = isset($_GET['search']) ? $_GET['search'] : '';
$data = $user->getAllUsers($keyword);
?>

<div class="max-w-4xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">👤 Daftar User</h2>

    <!-- Form Pencarian -->
    <form method="GET" class="mb-6 flex gap-2">
        <input
            type="text"
            name="search"
            placeholder="Cari nama user..."
            value="<?= htmlspecialchars($keyword) ?>"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Cari</button>
    </form>

    <!-- Tombol Toggle -->
    <button onclick="toggleUserForm()" class="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
        + Tambah User
    </button>

    <!-- Form Tambah User -->
    <div id="formContainer" class="mb-6 hidden">
        <form method="POST" class="space-y-4">
            <input type="hidden" name="add_user" value="1">
            <div>
                <label class="block">Nama:</label>
                <input type="text" name="nama" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block">Email:</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block">Telepon:</label>
                <input type="text" name="phone" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </form>
    </div>

    <!-- Form Update -->
    <div id="updateFormContainer" class="mb-6 hidden">
        <form method="POST" class="space-y-4">
            <input type="hidden" name="update_user" value="1">
            <input type="hidden" name="id" id="updateId">
            <div>
                <label class="block">Nama:</label>
                <input type="text" name="nama" id="updateNama" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block">Email:</label>
                <input type="email" name="email" id="updateEmail" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block">Telepon:</label>
                <input type="text" name="phone" id="updatePhone" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Update</button>
                <button type="button" onclick="cancelUpdate()" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">Batal</button>
            </div>
        </form>
    </div>

    <!-- Tabel User -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border">Email</th>
                    <th class="p-2 border">Telepon</th>
                    <th class="p-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                <tr class="text-center hover:bg-gray-50">
                    <td class="p-2 border"><?= $row['id'] ?></td>
                    <td class="p-2 border"><?= htmlspecialchars($row['nama']) ?></td>
                    <td class="p-2 border"><?= htmlspecialchars($row['email']) ?></td>
                    <td class="p-2 border"><?= htmlspecialchars($row['phone']) ?></td>
                    <td class="p-2 border space-x-2">
                        <button
                            onclick="showUpdateForm(
                                <?= $row['id'] ?>,
                                '<?= addslashes($row['nama']) ?>',
                                '<?= addslashes($row['email']) ?>',
                                '<?= addslashes($row['phone']) ?>'
                            )"
                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600"
                        >Edit</button>
                        <a
                            href="?delete_id=<?= $row['id'] ?>"
                            onclick="return confirm('Yakin ingin hapus user ini?')"
                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700"
                        >Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleUserForm() {
    const form = document.getElementById('formContainer');
    form.classList.toggle('hidden');
    document.getElementById('updateFormContainer').classList.add('hidden');
}

function showUpdateForm(id, nama, email, phone) {
    document.getElementById('updateId').value = id;
    document.getElementById('updateNama').value = nama;
    document.getElementById('updateEmail').value = email;
    document.getElementById('updatePhone').value = phone;

    document.getElementById('updateFormContainer').classList.remove('hidden');
    document.getElementById('formContainer').classList.add('hidden');
}

function cancelUpdate() {
    document.getElementById('updateFormContainer').classList.add('hidden');
}
</script>

<?php include 'footer.php'; ?>
