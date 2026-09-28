<?php
/**
 * Admin CRUD Portfolio Items - RBK Studio
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
checkAuth();

$db = getDB();
$flash = getFlash();

$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);

// Handle POST actions (Create/Update/Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['post_action'] ?? '';

    if ($postAction === 'save') {
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? 'Residential');
        $location = trim($_POST['location'] ?? '');
        $scope_tags = trim($_POST['scope_tags'] ?? '');
        $sort_order = intval($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $image_url = trim($_POST['image_url'] ?? '');

        // Handle Image Upload
        if (!empty($_FILES['image_file']['name'])) {
            $uploadDir = __DIR__ . '/../assets/uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
            $filename = 'portfolio_' . time() . '_' . rand(100, 999) . '.' . strtolower($ext);
            $targetPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
                $image_url = '/assets/uploads/' . $filename;
            }
        }

        if ($id > 0) {
            // Update
            $stmt = $db->prepare("UPDATE portfolio SET title=?, category=?, location=?, scope_tags=?, image_url=?, sort_order=?, is_active=? WHERE id=?");
            $stmt->execute([$title, $category, $location, $scope_tags, $image_url, $sort_order, $is_active, $id]);
            setFlash('success', 'Portfolio berhasil diperbarui.');
        } else {
            // Insert
            $stmt = $db->prepare("INSERT INTO portfolio (title, category, location, scope_tags, image_url, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $category, $location, $scope_tags, $image_url, $sort_order, $is_active]);
            setFlash('success', 'Portfolio baru berhasil ditambahkan.');
        }
        header('Location: /admin/portfolio.php');
        exit;
    } elseif ($postAction === 'delete' && $id > 0) {
        $stmt = $db->prepare("DELETE FROM portfolio WHERE id = ?");
        $stmt->execute([$id]);
        setFlash('success', 'Portfolio berhasil dihapus.');
        header('Location: /admin/portfolio.php');
        exit;
    }
}

// Fetch single item for edit
$item = null;
if (($action === 'edit' || $action === 'create') && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM portfolio WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
}

$portfolios = $db->query("SELECT * FROM portfolio ORDER BY sort_order ASC, id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Portfolio | RBK Studio CMS</title>
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
  <?php include __DIR__ . '/sidebar.php'; ?>

  <div class="admin-main">
    <div class="admin-topbar">
      <div>Kelola Portfolio Selected Works</div>
      <a href="/admin/portfolio.php?action=create" class="btn-admin btn-admin-primary">+ Tambah Project Baru</a>
    </div>

    <div class="admin-content">
      <?php if ($flash): ?>
        <div class="alert-flash <?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
      <?php endif; ?>

      <?php if ($action === 'create' || $action === 'edit'): ?>
        <div class="card">
          <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;"><?= $id > 0 ? 'Edit Project Portfolio' : 'Tambah Project Portfolio Baru'; ?></h2>
          <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="post_action" value="save">
            
            <div class="form-grid">
              <div class="form-group-admin">
                <label class="form-label-admin">Nama Project / Judul *</label>
                <input type="text" name="title" class="form-control-admin" required value="<?= e($item['title'] ?? ''); ?>" placeholder="Contoh: Villa Minimalis Modern 2 Lantai">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Kategori *</label>
                <select name="category" class="form-control-admin">
                  <option value="Residential" <?= ($item['category'] ?? '') === 'Residential' ? 'selected' : ''; ?>>Residential</option>
                  <option value="Commercial" <?= ($item['category'] ?? '') === 'Commercial' ? 'selected' : ''; ?>>Commercial</option>
                  <option value="Ruko" <?= ($item['category'] ?? '') === 'Ruko' ? 'selected' : ''; ?>>Ruko</option>
                  <option value="Interior" <?= ($item['category'] ?? '') === 'Interior' ? 'selected' : ''; ?>>Interior</option>
                </select>
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Lokasi (Kota / Kawasan) *</label>
                <input type="text" name="location" class="form-control-admin" required value="<?= e($item['location'] ?? ''); ?>" placeholder="Contoh: Jakarta Selatan / BSD City">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Scope Tag (Dipisah pemisah •)</label>
                <input type="text" name="scope_tags" class="form-control-admin" value="<?= e($item['scope_tags'] ?? 'Arsitektur • Struktur • MEP • RAB'); ?>" placeholder="Arsitektur • Struktur • MEP • RAB">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Upload Foto / Render Visual (Opsional)</label>
                <input type="file" name="image_file" class="form-control-admin" accept="image/*">
                <?php if (!empty($item['image_url'])): ?>
                  <div style="margin-top: 0.5rem; font-size: 0.75rem;">File saat ini: <code><?= e($item['image_url']); ?></code></div>
                <?php endif; ?>
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Atau URL Gambar Eksternal</label>
                <input type="url" name="image_url" class="form-control-admin" value="<?= e($item['image_url'] ?? ''); ?>" placeholder="https://example.com/render.jpg">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Urutan Tampil (Sort Order)</label>
                <input type="number" name="sort_order" class="form-control-admin" value="<?= intval($item['sort_order'] ?? 1); ?>">
              </div>

              <div class="form-group-admin" style="justify-content: center;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; cursor: pointer;">
                  <input type="checkbox" name="is_active" value="1" <?= ($item['is_active'] ?? 1) == 1 ? 'checked' : ''; ?>>
                  Tampilkan di Website
                </label>
              </div>
            </div>

            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
              <button type="submit" class="btn-admin btn-admin-primary">Simpan Portfolio</button>
              <a href="/admin/portfolio.php" class="btn-admin btn-admin-secondary">Batal</a>
            </div>
          </form>
        </div>
      <?php endif; ?>

      <!-- Portfolio Table List -->
      <div class="card">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem;">Daftar Project Portfolio</h3>

        <table class="admin-table">
          <thead>
            <tr>
              <th>Urutan</th>
              <th>Visual</th>
              <th>Nama Project</th>
              <th>Kategori</th>
              <th>Lokasi</th>
              <th>Scope</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($portfolios as $p): ?>
              <tr>
                <td><?= $p['sort_order']; ?></td>
                <td>
                  <?php if (!empty($p['image_url'])): ?>
                    <img src="<?= e($p['image_url']); ?>" style="width: 60px; height: 40px; object-fit: cover; border-radius: 2px;">
                  <?php else: ?>
                    <span style="font-size: 0.75rem; color: #9CA3AF;">Placeholder</span>
                  <?php endif; ?>
                </td>
                <td><strong><?= e($p['title']); ?></strong></td>
                <td><?= e($p['category']); ?></td>
                <td><?= e($p['location']); ?></td>
                <td style="font-size: 0.75rem; color: #6B7280;"><?= e($p['scope_tags']); ?></td>
                <td>
                  <span style="padding: 0.2rem 0.5rem; border-radius: 2px; font-size: 0.75rem; font-weight: 700; background: <?= $p['is_active'] ? '#DEF7EC' : '#FDE8E8'; ?>; color: <?= $p['is_active'] ? '#03543F' : '#9B1C1C'; ?>;">
                    <?= $p['is_active'] ? 'TAYANG' : 'DRAFT'; ?>
                  </span>
                </td>
                <td>
                  <a href="/admin/portfolio.php?action=edit&id=<?= $p['id']; ?>" class="btn-admin btn-admin-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">Edit</a>
                  
                  <form method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus portfolio ini?');">
                    <input type="hidden" name="post_action" value="delete">
                    <input type="hidden" name="id" value="<?= $p['id']; ?>">
                    <button type="submit" class="btn-admin btn-admin-danger" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">Hapus</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
