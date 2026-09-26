<?php
/**
 * Admin FAQ CRUD - RBK Studio
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
checkAuth();

$db = getDB();
$flash = getFlash();

$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['post_action'] ?? '';

    if ($postAction === 'save') {
        $question = trim($_POST['question'] ?? '');
        $answer = trim($_POST['answer'] ?? '');
        $sort_order = intval($_POST['sort_order'] ?? 1);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if ($id > 0) {
            $stmt = $db->prepare("UPDATE faqs SET question=?, answer=?, sort_order=?, is_active=? WHERE id=?");
            $stmt->execute([$question, $answer, $sort_order, $is_active, $id]);
            setFlash('success', 'Pertanyaan FAQ berhasil diperbarui.');
        } else {
            $stmt = $db->prepare("INSERT INTO faqs (question, answer, sort_order, is_active) VALUES (?, ?, ?, ?)");
            $stmt->execute([$question, $answer, $sort_order, $is_active]);
            setFlash('success', 'Pertanyaan FAQ baru berhasil ditambahkan.');
        }
        header('Location: /admin/faq.php');
        exit;
    } elseif ($postAction === 'delete' && $id > 0) {
        $stmt = $db->prepare("DELETE FROM faqs WHERE id = ?");
        $stmt->execute([$id]);
        setFlash('success', 'Pertanyaan FAQ berhasil dihapus.');
        header('Location: /admin/faq.php');
        exit;
    }
}

$faqItem = null;
if (($action === 'create' || $action === 'edit') && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM faqs WHERE id = ?");
    $stmt->execute([$id]);
    $faqItem = $stmt->fetch();
}

$faqs = $db->query("SELECT * FROM faqs ORDER BY sort_order ASC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola FAQ | RBK Studio CMS</title>
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
  <?php include __DIR__ . '/sidebar.php'; ?>

  <div class="admin-main">
    <div class="admin-topbar">
      <div>Kelola Blok Pertanyaan FAQ</div>
      <a href="/admin/faq.php?action=create" class="btn-admin btn-admin-primary">+ Tambah FAQ Baru</a>
    </div>

    <div class="admin-content">
      <?php if ($flash): ?>
        <div class="alert-flash <?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
      <?php endif; ?>

      <?php if ($action === 'create' || $action === 'edit'): ?>
        <div class="card">
          <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;"><?= $id > 0 ? 'Edit Pertanyaan FAQ' : 'Tambah Pertanyaan FAQ Baru'; ?></h2>
          <form method="POST">
            <input type="hidden" name="post_action" value="save">

            <div class="form-group-admin">
              <label class="form-label-admin">Pertanyaan (Question) *</label>
              <input type="text" name="question" class="form-control-admin" required value="<?= e($faqItem['question'] ?? ''); ?>" placeholder="Contoh: Berapa lama proses desainnya?">
            </div>

            <div class="form-group-admin">
              <label class="form-label-admin">Jawaban (Answer) *</label>
              <textarea name="answer" class="form-control-admin" rows="4" required placeholder="Tuliskan jawaban yang transparan dan jelas..."><?= e($faqItem['answer'] ?? ''); ?></textarea>
            </div>

            <div class="form-grid">
              <div class="form-group-admin">
                <label class="form-label-admin">Urutan Tampil (Sort Order)</label>
                <input type="number" name="sort_order" class="form-control-admin" value="<?= intval($faqItem['sort_order'] ?? 1); ?>">
              </div>

              <div class="form-group-admin" style="justify-content: center;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; cursor: pointer;">
                  <input type="checkbox" name="is_active" value="1" <?= ($faqItem['is_active'] ?? 1) == 1 ? 'checked' : ''; ?>>
                  Tampilkan di Website
                </label>
              </div>
            </div>

            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
              <button type="submit" class="btn-admin btn-admin-primary">Simpan Pertanyaan FAQ</button>
              <a href="/admin/faq.php" class="btn-admin btn-admin-secondary">Batal</a>
            </div>
          </form>
        </div>
      <?php endif; ?>

      <!-- FAQ Table List -->
      <div class="card">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem;">Daftar Accordion FAQ</h3>

        <table class="admin-table">
          <thead>
            <tr>
              <th style="width: 60px;">Urutan</th>
              <th>Pertanyaan</th>
              <th>Jawaban</th>
              <th style="width: 100px;">Status</th>
              <th style="width: 140px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($faqs as $f): ?>
              <tr>
                <td><?= $f['sort_order']; ?></td>
                <td><strong><?= e($f['question']); ?></strong></td>
                <td style="font-size: 0.8125rem; color: #4B5563; max-width: 400px;"><?= e(mb_strimwidth($f['answer'], 0, 120, "...")); ?></td>
                <td>
                  <span style="padding: 0.2rem 0.5rem; border-radius: 2px; font-size: 0.75rem; font-weight: 700; background: <?= $f['is_active'] ? '#DEF7EC' : '#FDE8E8'; ?>; color: <?= $f['is_active'] ? '#03543F' : '#9B1C1C'; ?>;">
                    <?= $f['is_active'] ? 'TAYANG' : 'DRAFT'; ?>
                  </span>
                </td>
                <td>
                  <a href="/admin/faq.php?action=edit&id=<?= $f['id']; ?>" class="btn-admin btn-admin-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">Edit</a>
                  
                  <form method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus FAQ ini?');">
                    <input type="hidden" name="post_action" value="delete">
                    <input type="hidden" name="id" value="<?= $f['id']; ?>">
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
