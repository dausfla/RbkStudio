<?php
/**
 * Admin CRUD Video Showcase Items - RBK Studio
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
        $category = trim($_POST['category'] ?? 'Walkthrough 3D');
        $youtube_url = trim($_POST['youtube_url'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $duration = trim($_POST['duration'] ?? '');
        $sort_order = intval($_POST['sort_order'] ?? 0);
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $thumbnail_url = trim($_POST['thumbnail_url'] ?? '');

        // Handle Custom Thumbnail Image Upload
        if (!empty($_FILES['thumbnail_file']['name'])) {
            $uploadDir = __DIR__ . '/../assets/uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $ext = pathinfo($_FILES['thumbnail_file']['name'], PATHINFO_EXTENSION);
            $filename = 'video_thumb_' . time() . '_' . rand(100, 999) . '.' . strtolower($ext);
            $targetPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['thumbnail_file']['tmp_name'], $targetPath)) {
                $thumbnail_url = '/assets/uploads/' . $filename;
            }
        }

        if (empty($title) || empty($youtube_url)) {
            setFlash('danger', 'Judul Video dan URL YouTube wajib diisi.');
        } else {
            if ($id > 0) {
                // Update
                $stmt = $db->prepare("UPDATE portfolio_videos SET title=?, category=?, youtube_url=?, thumbnail_url=?, description=?, duration=?, sort_order=?, is_active=? WHERE id=?");
                $stmt->execute([$title, $category, $youtube_url, $thumbnail_url, $description, $duration, $sort_order, $is_active, $id]);
                setFlash('success', 'Video showcase berhasil diperbarui.');
            } else {
                // Insert
                $stmt = $db->prepare("INSERT INTO portfolio_videos (title, category, youtube_url, thumbnail_url, description, duration, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $category, $youtube_url, $thumbnail_url, $description, $duration, $sort_order, $is_active]);
                setFlash('success', 'Video showcase baru berhasil ditambahkan.');
            }
            header('Location: /admin/videos.php');
            exit;
        }
    } elseif ($postAction === 'delete' && $id > 0) {
        $stmt = $db->prepare("DELETE FROM portfolio_videos WHERE id = ?");
        $stmt->execute([$id]);
        setFlash('success', 'Video showcase berhasil dihapus.');
        header('Location: /admin/videos.php');
        exit;
    }
}

// Fetch single item for edit
$item = null;
if (($action === 'edit' || $action === 'create') && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM portfolio_videos WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
}

$videos = $db->query("SELECT * FROM portfolio_videos ORDER BY sort_order ASC, id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Video Portfolio | RBK Studio CMS</title>
  <link rel="stylesheet" href="/assets/css/admin.css">
  <style>
    .video-preview-thumb {
      width: 120px;
      height: 70px;
      object-fit: cover;
      border-radius: 4px;
      border: 1px solid #374151;
      background: #111827;
    }
    .yt-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      background: rgba(239, 68, 68, 0.15);
      color: #EF4444;
      padding: 0.2rem 0.5rem;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 600;
    }
  </style>
</head>
<body class="admin-body">
  <?php include __DIR__ . '/sidebar.php'; ?>

  <div class="admin-main">
    <div class="admin-topbar">
      <div>Kelola Video Showcase & Walkthrough (YouTube)</div>
      <a href="/admin/videos.php?action=create" class="btn-admin btn-admin-primary">+ Tambah Video Baru</a>
    </div>

    <div class="admin-content">
      <?php if ($flash): ?>
        <div class="alert-flash <?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
      <?php endif; ?>

      <?php if ($action === 'create' || $action === 'edit'): ?>
        <div class="card">
          <div class="card-header">
            <h3><?= $action === 'edit' ? 'Edit Video Showcase' : 'Tambah Video Showcase Baru'; ?></h3>
            <a href="/admin/videos.php" style="color: #9CA3AF; text-decoration: none; font-size: 0.875rem;">&larr; Kembali ke List</a>
          </div>

          <form action="/admin/videos.php<?= $id ? '?id=' . $id : ''; ?>" method="POST" enctype="multipart/form-data" class="form-grid">
            <input type="hidden" name="post_action" value="save">

            <div class="form-group full-width">
              <label class="form-label">Judul Video Showcase *</label>
              <input type="text" name="title" class="form-control" required value="<?= e($item['title'] ?? ''); ?>" placeholder="Contoh: Tour Villa Minimalis Modern 2 Lantai">
            </div>

            <div class="form-group">
              <label class="form-label">Kategori / Tag</label>
              <input type="text" name="category" class="form-control" value="<?= e($item['category'] ?? 'Walkthrough 3D'); ?>" placeholder="Contoh: Walkthrough 3D, Komersial, Residential">
            </div>

            <div class="form-group">
              <label class="form-label">Durasi Video (Opsional)</label>
              <input type="text" name="duration" class="form-control" value="<?= e($item['duration'] ?? ''); ?>" placeholder="Contoh: 03:45">
            </div>

            <div class="form-group full-width">
              <label class="form-label">URL YouTube * (Target Redirect)</label>
              <input type="url" name="youtube_url" class="form-control" required value="<?= e($item['youtube_url'] ?? ''); ?>" placeholder="Contoh: https://www.youtube.com/watch?v=ScMzIvxBSi4 atau https://youtu.be/ScMzIvxBSi4">
              <div style="font-size: 0.75rem; color: #9CA3AF; margin-top: 0.35rem;">
                💡 Pengunjung akan di-redirect ke link YouTube ini saat menekan card video. Thumbnail dari YouTube akan diambil secara otomatis jika thumbnail kustom dikosongkan.
              </div>
            </div>

            <div class="form-group full-width">
              <label class="form-label">Deskripsi Singkat Video</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Ringkasan konsep atau penjelasan video..."><?= e($item['description'] ?? ''); ?></textarea>
            </div>

            <div class="form-group">
              <label class="form-label">Upload Custom Thumbnail (Opsional)</label>
              <input type="file" name="thumbnail_file" class="form-control" accept="image/*">
              <div style="font-size: 0.75rem; color: #9CA3AF; margin-top: 0.35rem;">Jika dikosongkan, sistem akan otomatis menggunakan High Quality Thumbnail dari YouTube.</div>
            </div>

            <div class="form-group">
              <label class="form-label">URL Thumbnail Kustom (Opsional)</label>
              <input type="text" name="thumbnail_url" class="form-control" value="<?= e($item['thumbnail_url'] ?? ''); ?>" placeholder="https://... atau /assets/uploads/...">
            </div>

            <div class="form-group">
              <label class="form-label">Urutan Tampil (Sort Order)</label>
              <input type="number" name="sort_order" class="form-control" value="<?= intval($item['sort_order'] ?? 0); ?>">
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 1.5rem;">
              <input type="checkbox" name="is_active" id="is_active" value="1" <?= (!isset($item['is_active']) || $item['is_active'] == 1) ? 'checked' : ''; ?>>
              <label for="is_active" style="cursor: pointer; color: #F3F4F6;">Aktifkan Video (Tampilkan di Landing Page)</label>
            </div>

            <div class="form-actions full-width">
              <button type="submit" class="btn-admin btn-admin-primary">Simpan Video Showcase</button>
              <a href="/admin/videos.php" class="btn-admin btn-admin-secondary">Batal</a>
            </div>
          </form>
        </div>
      <?php else: ?>
        <div class="card">
          <div class="card-header">
            <h3>Daftar Video Portfolio & Walkthrough (<?= count($videos); ?> Items)</h3>
          </div>

          <?php if (empty($videos)): ?>
            <p style="color: #9CA3AF; padding: 1.5rem; text-align: center;">Belum ada video showcase yang ditambahkan.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table-admin">
                <thead>
                  <tr>
                    <th>Thumbnail</th>
                    <th>Judul & Kategori</th>
                    <th>Target Link YouTube</th>
                    <th>Durasi</th>
                    <th>Urutan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($videos as $vid): 
                    $thumbSrc = getYouTubeThumbnail($vid['youtube_url'], $vid['thumbnail_url']);
                  ?>
                    <tr>
                      <td>
                        <img src="<?= e($thumbSrc); ?>" alt="Thumbnail" class="video-preview-thumb" onerror="this.src='/assets/images/placeholder_video.jpg';">
                      </td>
                      <td>
                        <div style="font-weight: 600; color: #F9FAFB; font-size: 0.95rem; margin-bottom: 0.25rem;">
                          <?= e($vid['title']); ?>
                        </div>
                        <span class="yt-badge"><?= e($vid['category']); ?></span>
                      </td>
                      <td>
                        <a href="<?= e($vid['youtube_url']); ?>" target="_blank" style="color: #3B82F6; font-size: 0.85rem; text-decoration: underline; word-break: break-all;">
                          <?= e($vid['youtube_url']); ?> ↗
                        </a>
                      </td>
                      <td><?= e($vid['duration'] ?: '-'); ?></td>
                      <td><?= intval($vid['sort_order']); ?></td>
                      <td>
                        <?php if ($vid['is_active']): ?>
                          <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                          <span class="badge badge-danger">Non-Aktif</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <div style="display: flex; gap: 0.5rem;">
                          <a href="/admin/videos.php?action=edit&id=<?= $vid['id']; ?>" class="btn-admin btn-admin-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Edit</a>
                          <form action="/admin/videos.php?id=<?= $vid['id']; ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus video ini?');" style="display: inline;">
                            <input type="hidden" name="post_action" value="delete">
                            <button type="submit" class="btn-admin btn-admin-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">Hapus</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
