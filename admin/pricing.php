<?php
/**
 * Admin Pricing Packages CRUD - RBK Studio
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
checkAuth();

$db = getDB();
$flash = getFlash();

$id = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? 'list';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['post_action'] ?? '';

    if ($postAction === 'save') {
        $name = trim($_POST['name'] ?? '');
        $subname = trim($_POST['subname'] ?? '');
        $badge_label = trim($_POST['badge_label'] ?? '');
        $price_per_m2 = intval($_POST['price_per_m2'] ?? 0);
        $price_formatted = trim($_POST['price_formatted'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $ideal_for = trim($_POST['ideal_for'] ?? '');
        $included_items = trim($_POST['included_items'] ?? '');
        $positioning_line = trim($_POST['positioning_line'] ?? '');
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        $cta_text = trim($_POST['cta_text'] ?? '');

        if ($id > 0) {
            $stmt = $db->prepare("UPDATE pricing_packages SET name=?, subname=?, badge_label=?, price_per_m2=?, price_formatted=?, description=?, ideal_for=?, included_items=?, positioning_line=?, is_featured=?, cta_text=? WHERE id=?");
            $stmt->execute([$name, $subname, $badge_label, $price_per_m2, $price_formatted, $description, $ideal_for, $included_items, $positioning_line, $is_featured, $cta_text, $id]);
            setFlash('success', 'Paket desain berhasil diperbarui.');
        }
        header('Location: /admin/pricing.php');
        exit;
    }
}

// Fetch single package for editing
$package = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM pricing_packages WHERE id = ?");
    $stmt->execute([$id]);
    $package = $stmt->fetch();
}

$packages = $db->query("SELECT * FROM pricing_packages ORDER BY sort_order ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Paket Desain | RBK Studio CMS</title>
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
  <?php include __DIR__ . '/sidebar.php'; ?>

  <div class="admin-main">
    <div class="admin-topbar">
      <div>Kelola Paket & Harga Desain (Basic, Standard, Premium)</div>
    </div>

    <div class="admin-content">
      <?php if ($flash): ?>
        <div class="alert-flash <?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
      <?php endif; ?>

      <?php if ($action === 'edit' && $package): ?>
        <div class="card">
          <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">Edit Paket Desain: <?= e($package['name']); ?></h2>
          
          <form method="POST">
            <input type="hidden" name="post_action" value="save">

            <div class="form-grid">
              <div class="form-group-admin">
                <label class="form-label-admin">Nama Paket *</label>
                <input type="text" name="name" class="form-control-admin" required value="<?= e($package['name']); ?>">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Sub-Nama Layanan *</label>
                <input type="text" name="subname" class="form-control-admin" required value="<?= e($package['subname']); ?>">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Badge Label (Contoh: RECOMMENDED / SIGNATURE SERVICE)</label>
                <input type="text" name="badge_label" class="form-control-admin" value="<?= e($package['badge_label']); ?>">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Harga per m² (Angka) *</label>
                <input type="number" name="price_per_m2" class="form-control-admin" required value="<?= $package['price_per_m2']; ?>">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Format Tampilan Harga *</label>
                <input type="text" name="price_formatted" class="form-control-admin" required value="<?= e($package['price_formatted']); ?>" placeholder="Rp80.000/m²">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Teks Tombol CTA *</label>
                <input type="text" name="cta_text" class="form-control-admin" required value="<?= e($package['cta_text']); ?>">
              </div>

              <div class="form-group-admin full">
                <label class="form-label-admin">Deskripsi Singkat Paket</label>
                <textarea name="description" class="form-control-admin" rows="2"><?= e($package['description']); ?></textarea>
              </div>

              <div class="form-group-admin full">
                <label class="form-label-admin">Ideal Untuk (Dipisah •)</label>
                <input type="text" name="ideal_for" class="form-control-admin" value="<?= e($package['ideal_for']); ?>">
              </div>

              <div class="form-group-admin full">
                <label class="form-label-admin">Termasuk Layanan (Satu item per baris baru)</label>
                <textarea name="included_items" class="form-control-admin" rows="4"><?= e($package['included_items']); ?></textarea>
              </div>

              <div class="form-group-admin full">
                <label class="form-label-admin">Positioning Line Signature</label>
                <input type="text" name="positioning_line" class="form-control-admin" value="<?= e($package['positioning_line']); ?>">
              </div>

              <div class="form-group-admin">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; cursor: pointer;">
                  <input type="checkbox" name="is_featured" value="1" <?= $package['is_featured'] == 1 ? 'checked' : ''; ?>>
                  Tandai sebagai Paket Recommended (Pop-out Dark Card)
                </label>
              </div>
            </div>

            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
              <button type="submit" class="btn-admin btn-admin-primary">Simpan Perubahan Paket</button>
              <a href="/admin/pricing.php" class="btn-admin btn-admin-secondary">Batal</a>
            </div>
          </form>
        </div>
      <?php endif; ?>

      <!-- Package List -->
      <div class="card">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem;">Tiga Paket Desain RBK Studio</h3>
        
        <table class="admin-table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama Paket</th>
              <th>Harga</th>
              <th>Badge</th>
              <th>Status Featured</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($packages as $p): ?>
              <tr>
                <td><strong><?= e($p['code']); ?></strong></td>
                <td>
                  <div><strong><?= e($p['name']); ?></strong></div>
                  <div style="font-size: 0.75rem; color: #6B7280;"><?= e($p['subname']); ?></div>
                </td>
                <td><strong style="color: var(--admin-primary);"><?= e($p['price_formatted']); ?></strong></td>
                <td><?= !empty($p['badge_label']) ? '<span class="sidebar-badge">' . e($p['badge_label']) . '</span>' : '-'; ?></td>
                <td><?= $p['is_featured'] == 1 ? '<span style="color: #03543F; font-weight:700;">RECOMMENDED</span>' : 'Standard'; ?></td>
                <td>
                  <a href="/admin/pricing.php?action=edit&id=<?= $p['id']; ?>" class="btn-admin btn-admin-primary" style="padding: 0.3rem 0.75rem; font-size: 0.75rem;">Edit Paket</a>
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
