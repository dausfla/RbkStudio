<?php
/**
 * Admin CRUD Video Showcase Items - RBK Studio
 * Consistent UI/UX matching Portfolio, Pricing & FAQ modules
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
    $id = intval($_POST['id'] ?? $id);

    if ($postAction === 'save') {
        $title = trim($_POST['title'] ?? '');
        $category = trim($_POST['category'] ?? 'Walkthrough 3D');
        $youtube_url = trim($_POST['youtube_url'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $duration = trim($_POST['duration'] ?? '');
        $sort_order = intval($_POST['sort_order'] ?? 1);
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

// Fetch all video items
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
    .yt-live-box {
      margin-top: 0.75rem;
      background: #F9FAFB;
      border: 1px dashed #D1D5DB;
      border-radius: 6px;
      padding: 1rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .yt-live-thumb {
      width: 120px;
      height: 68px;
      background: #111827;
      border-radius: 4px;
      overflow: hidden;
      flex-shrink: 0;
      border: 1px solid var(--admin-border);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #9CA3AF;
      font-size: 0.75rem;
    }
    .yt-live-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .yt-live-info {
      font-size: 0.8125rem;
      color: #4B5563;
    }
    .yt-status-badge {
      font-weight: 700;
      color: #059669;
      display: flex;
      align-items: center;
      gap: 0.35rem;
      margin-bottom: 0.25rem;
    }
    .yt-status-badge.error {
      color: #DC2626;
    }
  </style>
</head>
<body class="admin-body">
  <?php include __DIR__ . '/sidebar.php'; ?>

  <div class="admin-main">
    <div class="admin-topbar">
      <div style="font-weight: 700; color: var(--admin-text);">Kelola Video Showcase & Walkthrough (YouTube)</div>
      <a href="/admin/videos.php?action=create" class="btn-admin btn-admin-primary">+ Tambah Video Baru</a>
    </div>

    <div class="admin-content">
      <?php if ($flash): ?>
        <div class="alert-flash <?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
      <?php endif; ?>

      <?php if ($action === 'create' || $action === 'edit'): ?>
        <!-- Form Section -->
        <div class="card">
          <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--admin-text);">
            <?= $id > 0 ? 'Edit Video Showcase' : 'Tambah Video Showcase Baru'; ?>
          </h2>

          <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="post_action" value="save">
            <input type="hidden" name="id" value="<?= $id; ?>">

            <div class="form-grid">
              <div class="form-group-admin">
                <label class="form-label-admin">Judul Video Showcase *</label>
                <input type="text" name="title" class="form-control-admin" required value="<?= e($item['title'] ?? ''); ?>" placeholder="Contoh: Tour Villa Minimalis Modern 2 Lantai">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Kategori / Tag Showcase *</label>
                <input type="text" name="category" class="form-control-admin" required value="<?= e($item['category'] ?? 'Walkthrough 3D'); ?>" placeholder="Contoh: Walkthrough 3D / Komersial / Residential">
              </div>

              <div class="form-group-admin full">
                <label class="form-label-admin">URL YouTube * (Target Redirect & Pratinjau Auto)</label>
                <input type="url" name="youtube_url" id="yt_url_field" class="form-control-admin" required value="<?= e($item['youtube_url'] ?? ''); ?>" placeholder="Contoh: https://www.youtube.com/watch?v=L_LUpnjgPso atau https://youtu.be/L_LUpnjgPso">
                
                <!-- Real-time YouTube Link Inspector -->
                <div class="yt-live-box">
                  <div class="yt-live-thumb" id="yt_live_thumb">No Link</div>
                  <div class="yt-live-info">
                    <div class="yt-status-badge" id="yt_status_badge">
                      <span>Pratinjau Otomatis YouTube</span>
                    </div>
                    <div style="font-size: 0.75rem; color: #6B7280;">
                      Sistem akan mendeteksi ID Video & menyajikan thumbnail High Quality otomatis jika thumbnail kustom dikosongkan.
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group-admin full">
                <label class="form-label-admin">Deskripsi Singkat Video</label>
                <textarea name="description" class="form-control-admin" rows="3" placeholder="Jelaskan ringkasan konsep arsitektur, fasad, atau keunggulan pada video ini..."><?= e($item['description'] ?? ''); ?></textarea>
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Upload Custom Thumbnail (Opsional)</label>
                <input type="file" name="thumbnail_file" class="form-control-admin" accept="image/*">
                <?php if (!empty($item['thumbnail_url'])): ?>
                  <div style="margin-top: 0.35rem; font-size: 0.75rem; color: #6B7280;">File saat ini: <code><?= e($item['thumbnail_url']); ?></code></div>
                <?php endif; ?>
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Atau URL Thumbnail Custom (Opsional)</label>
                <input type="text" name="thumbnail_url" class="form-control-admin" value="<?= e($item['thumbnail_url'] ?? ''); ?>" placeholder="/assets/uploads/... atau https://...">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Durasi Video (Opsional)</label>
                <input type="text" name="duration" class="form-control-admin" value="<?= e($item['duration'] ?? ''); ?>" placeholder="Contoh: 03:45">
              </div>

              <div class="form-group-admin">
                <label class="form-label-admin">Urutan Tampil (Sort Order)</label>
                <input type="number" name="sort_order" class="form-control-admin" value="<?= intval($item['sort_order'] ?? 1); ?>">
              </div>

              <div class="form-group-admin full" style="margin-top: 0.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; cursor: pointer; color: var(--admin-text); font-weight: 600;">
                  <input type="checkbox" name="is_active" value="1" <?= (!isset($item['is_active']) || $item['is_active'] == 1) ? 'checked' : ''; ?>>
                  Tampilkan Video Ini di Landing Page (Aktif)
                </label>
              </div>
            </div>

            <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
              <button type="submit" class="btn-admin btn-admin-primary">Simpan Video Showcase</button>
              <a href="/admin/videos.php" class="btn-admin btn-admin-secondary">Batal</a>
            </div>
          </form>
        </div>
      <?php endif; ?>

      <!-- Video Items Table List -->
      <div class="card">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem; color: var(--admin-text);">
          Daftar Video Portfolio & Walkthrough (<?= count($videos); ?> Items)
        </h3>

        <?php if (empty($videos)): ?>
          <p style="color: #6B7280; padding: 1.5rem 0; text-align: center;">Belum ada video showcase yang ditambahkan.</p>
        <?php else: ?>
          <div class="table-responsive">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Urutan</th>
                  <th>Thumbnail</th>
                  <th>Judul & Kategori</th>
                  <th>Target Link YouTube</th>
                  <th>Durasi</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($videos as $vid): 
                  $thumbSrc = getYouTubeThumbnail($vid['youtube_url'], $vid['thumbnail_url']);
                ?>
                  <tr>
                    <td><strong><?= intval($vid['sort_order']); ?></strong></td>
                    <td>
                      <img src="<?= e($thumbSrc); ?>" alt="Thumbnail" style="width: 80px; height: 45px; object-fit: cover; border-radius: 4px; border: 1px solid var(--admin-border);" onerror="this.src='/assets/images/placeholder_video.jpg';">
                    </td>
                    <td>
                      <div style="font-weight: 700; color: var(--admin-text); font-size: 0.9rem; margin-bottom: 0.2rem;">
                        <?= e($vid['title']); ?>
                      </div>
                      <span style="font-size: 0.75rem; background: #F3F4F6; color: #374151; padding: 0.15rem 0.4rem; border-radius: 3px; font-weight: 600;">
                        <?= e($vid['category']); ?>
                      </span>
                    </td>
                    <td>
                      <a href="<?= e($vid['youtube_url']); ?>" target="_blank" style="color: #2563EB; font-size: 0.8125rem; text-decoration: underline; word-break: break-all;">
                        <?= e($vid['youtube_url']); ?> ↗
                      </a>
                    </td>
                    <td style="font-size: 0.8125rem; color: #4B5563;"><?= e($vid['duration'] ?: '-'); ?></td>
                    <td>
                      <span style="padding: 0.2rem 0.5rem; border-radius: 2px; font-size: 0.75rem; font-weight: 700; background: <?= $vid['is_active'] ? '#DEF7EC' : '#FDE8E8'; ?>; color: <?= $vid['is_active'] ? '#03543F' : '#9B1C1C'; ?>;">
                        <?= $vid['is_active'] ? 'TAYANG' : 'DRAFT'; ?>
                      </span>
                    </td>
                    <td>
                      <div style="display: flex; gap: 0.5rem;">
                        <a href="/admin/videos.php?action=edit&id=<?= $vid['id']; ?>" class="btn-admin btn-admin-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">Edit</a>
                        
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus video ini?');">
                          <input type="hidden" name="post_action" value="delete">
                          <input type="hidden" name="id" value="<?= $vid['id']; ?>">
                          <button type="submit" class="btn-admin btn-admin-danger" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">Hapus</button>
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
    </div>
  </div>

  <!-- Real-time YouTube Link Inspector Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const urlInput = document.getElementById('yt_url_field');
      const liveThumb = document.getElementById('yt_live_thumb');
      const statusBadge = document.getElementById('yt_status_badge');

      function extractYtId(url) {
        if (!url) return '';
        const pattern = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^"&?\/\s]{11})/;
        const match = url.match(pattern);
        return match ? match[1] : '';
      }

      function updatePreview() {
        if (!urlInput || !liveThumb) return;
        const url = urlInput.value.trim();
        const ytId = extractYtId(url);

        if (ytId) {
          liveThumb.innerHTML = `<img src="https://img.youtube.com/vi/${ytId}/hqdefault.jpg" alt="Preview">`;
          statusBadge.className = 'yt-status-badge';
          statusBadge.innerHTML = `<span>✓ Valid YouTube Video (ID: ${ytId})</span>`;
        } else if (url.length > 0) {
          liveThumb.innerHTML = `<span style="color:#DC2626;font-size:0.7rem;text-align:center;padding:0.2rem;">Link Invalid</span>`;
          statusBadge.className = 'yt-status-badge error';
          statusBadge.innerHTML = `<span>✕ Format URL YouTube tidak valid</span>`;
        } else {
          liveThumb.innerHTML = `No Link`;
          statusBadge.className = 'yt-status-badge';
          statusBadge.innerHTML = `<span>Pratinjau Otomatis YouTube</span>`;
        }
      }

      if (urlInput) {
        urlInput.addEventListener('input', updatePreview);
        urlInput.addEventListener('paste', function() {
          setTimeout(updatePreview, 100);
        });
        updatePreview();
      }
    });
  </script>
</body>
</html>
