<?php
/**
 * Admin CRUD Video Showcase Items - RBK Studio
 * Enhanced UI/UX with Live YouTube Preview & Grid View
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
checkAuth();

$db = getDB();
$flash = getFlash();

$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);
$search = trim($_GET['q'] ?? '');

// Handle POST actions (Create/Update/Delete/Toggle)
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
    } elseif ($postAction === 'toggle' && $id > 0) {
        $stmt = $db->prepare("UPDATE portfolio_videos SET is_active = CASE WHEN is_active = 1 THEN 0 ELSE 1 END WHERE id = ?");
        $stmt->execute([$id]);
        setFlash('success', 'Status visibilitas video berhasil diubah.');
        header('Location: /admin/videos.php');
        exit;
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

// Fetch all videos with search filter
if (!empty($search)) {
    $stmt = $db->prepare("SELECT * FROM portfolio_videos WHERE title LIKE ? OR category LIKE ? OR description LIKE ? ORDER BY sort_order ASC, id DESC");
    $searchTerm = "%{$search}%";
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    $videos = $stmt->fetchAll();
} else {
    $videos = $db->query("SELECT * FROM portfolio_videos ORDER BY sort_order ASC, id DESC")->fetchAll();
}

// Stats count
$totalVideos = count($videos);
$activeVideos = 0;
foreach ($videos as $v) {
    if ($v['is_active']) $activeVideos++;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Video Portfolio | RBK Studio CMS</title>
  <link rel="stylesheet" href="/assets/css/admin.css">
  <style>
    /* Enhanced UI Custom Styles */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1.25rem;
      margin-bottom: 2rem;
    }
    .stat-card {
      background: #1F2937;
      border: 1px solid #374151;
      border-radius: 8px;
      padding: 1.25rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 8px;
      background: rgba(245, 80, 45, 0.15);
      color: #F5502D;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .stat-val {
      font-size: 1.5rem;
      font-weight: 700;
      color: #F9FAFB;
      line-height: 1.2;
    }
    .stat-lbl {
      font-size: 0.8rem;
      color: #9CA3AF;
      margin-top: 0.2rem;
    }

    .toolbar-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }
    .search-box {
      position: relative;
      flex-grow: 1;
      max-width: 380px;
    }
    .search-input {
      width: 100%;
      padding: 0.6rem 1rem 0.6rem 2.5rem;
      background: #1F2937;
      border: 1px solid #374151;
      border-radius: 6px;
      color: #F9FAFB;
      font-size: 0.875rem;
    }
    .search-icon {
      position: absolute;
      left: 0.8rem;
      top: 50%;
      transform: translateY(-50%);
      color: #9CA3AF;
      pointer-events: none;
    }

    /* Video Card Grid in Admin */
    .admin-video-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 1.5rem;
    }
    .admin-vcard {
      background: #1F2937;
      border: 1px solid #374151;
      border-radius: 8px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .admin-vcard:hover {
      border-color: #4B5563;
      transform: translateY(-2px);
    }
    .admin-vcard-thumb {
      position: relative;
      width: 100%;
      aspect-ratio: 16 / 9;
      background: #111827;
      overflow: hidden;
    }
    .admin-vcard-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .admin-vcard-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(0, 0, 0, 0.75);
      backdrop-filter: blur(4px);
      color: #EF4444;
      font-size: 0.7rem;
      font-weight: 700;
      padding: 0.25rem 0.6rem;
      border-radius: 4px;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
    }
    .admin-vcard-duration {
      position: absolute;
      bottom: 10px;
      right: 10px;
      background: rgba(0, 0, 0, 0.8);
      color: #FFF;
      font-size: 0.75rem;
      font-weight: 600;
      padding: 0.2rem 0.5rem;
      border-radius: 4px;
    }
    .admin-vcard-body {
      padding: 1.25rem;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }
    .admin-vcard-title {
      font-size: 1rem;
      font-weight: 700;
      color: #F9FAFB;
      margin-bottom: 0.5rem;
      line-height: 1.4;
    }
    .admin-vcard-desc {
      font-size: 0.825rem;
      color: #9CA3AF;
      line-height: 1.5;
      margin-bottom: 1rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .admin-vcard-footer {
      margin-top: auto;
      padding-top: 1rem;
      border-top: 1px solid #374151;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.5rem;
    }
    .yt-link-btn {
      color: #3B82F6;
      font-size: 0.8rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
    }
    .yt-link-btn:hover {
      text-decoration: underline;
    }

    /* Live YouTube Preview Box in Form */
    .yt-preview-box {
      margin-top: 0.75rem;
      background: #111827;
      border: 1px dashed #374151;
      border-radius: 8px;
      padding: 1rem;
      display: flex;
      align-items: center;
      gap: 1.25rem;
    }
    .yt-preview-frame {
      width: 160px;
      aspect-ratio: 16 / 9;
      background: #000;
      border-radius: 6px;
      overflow: hidden;
      flex-shrink: 0;
    }
    .yt-preview-frame img, .yt-preview-frame iframe {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border: 0;
    }
    .yt-preview-info {
      flex-grow: 1;
    }
    .yt-preview-status {
      font-size: 0.8rem;
      font-weight: 600;
      color: #10B981;
      display: flex;
      align-items: center;
      gap: 0.35rem;
      margin-bottom: 0.25rem;
    }
    .yt-preview-status.invalid {
      color: #EF4444;
    }

    /* Form Split Layout */
    .form-layout-split {
      display: grid;
      grid-template-columns: 1.5fr 1fr;
      gap: 2rem;
    }
    @media (max-width: 992px) {
      .form-layout-split {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body class="admin-body">
  <?php include __DIR__ . '/sidebar.php'; ?>

  <div class="admin-main">
    <div class="admin-topbar">
      <div>Kelola Video Showcase & Walkthrough (YouTube)</div>
      <div style="display: flex; gap: 0.75rem;">
        <?php if ($action !== 'create'): ?>
          <a href="/admin/videos.php?action=create" class="btn-admin btn-admin-primary">+ Tambah Video Baru</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="admin-content">
      <?php if ($flash): ?>
        <div class="alert-flash <?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
      <?php endif; ?>

      <!-- Statistics Bar -->
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
          </div>
          <div>
            <div class="stat-val"><?= $totalVideos; ?></div>
            <div class="stat-lbl">Total Video Showcase</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #10B981;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          </div>
          <div>
            <div class="stat-val"><?= $activeVideos; ?></div>
            <div class="stat-lbl">Video Tampil di Website</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon" style="background: rgba(59, 130, 246, 0.15); color: #3B82F6;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
          </div>
          <div>
            <div class="stat-val">Autoplay</div>
            <div class="stat-lbl">Hover & Viewport Preview</div>
          </div>
        </div>
      </div>

      <?php if ($action === 'create' || $action === 'edit'): ?>
        <!-- Form Section -->
        <div class="card">
          <div class="card-header">
            <h3><?= $action === 'edit' ? 'Edit Video Showcase' : 'Tambah Video Showcase Baru'; ?></h3>
            <a href="/admin/videos.php" style="color: #9CA3AF; text-decoration: none; font-size: 0.875rem;">&larr; Batal & Kembali</a>
          </div>

          <form action="/admin/videos.php<?= $id ? '?id=' . $id : ''; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="post_action" value="save">

            <div class="form-layout-split">
              <!-- Left Column: Video Meta -->
              <div>
                <div class="form-group" style="margin-bottom: 1.25rem;">
                  <label class="form-label">Judul Video Showcase *</label>
                  <input type="text" name="title" class="form-control" required value="<?= e($item['title'] ?? ''); ?>" placeholder="Contoh: Tour Villa Minimalis Modern 2 Lantai">
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                  <label class="form-label">URL YouTube * (Target Redirect & Preview Auto)</label>
                  <input type="url" name="youtube_url" id="yt_url_input" class="form-control" required value="<?= e($item['youtube_url'] ?? ''); ?>" placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/...">
                  
                  <!-- Live Preview Box -->
                  <div class="yt-preview-box" id="yt_preview_box">
                    <div class="yt-preview-frame" id="yt_preview_frame">
                      <div style="display:flex; align-items:center; justify-content:center; height:100%; color:#6B7280; font-size:0.75rem;">No Link</div>
                    </div>
                    <div class="yt-preview-info">
                      <div class="yt-preview-status" id="yt_preview_status">
                        <span>Pratinjau Otomatis YouTube</span>
                      </div>
                      <div style="font-size: 0.75rem; color: #9CA3AF; line-height: 1.4;">
                        Ketik / paste link YouTube di atas. Thumbnail & ID video akan dideteksi secara real-time.
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                  <label class="form-label">Deskripsi Singkat Video</label>
                  <textarea name="description" class="form-control" rows="4" placeholder="Ringkasan konsep arsitektur, fasad, atau penjelasan yang disajikan dalam video..."><?= e($item['description'] ?? ''); ?></textarea>
                </div>
              </div>

              <!-- Right Column: Settings & Media -->
              <div>
                <div class="form-group" style="margin-bottom: 1.25rem;">
                  <label class="form-label">Kategori / Tag Showcase</label>
                  <input type="text" name="category" class="form-control" value="<?= e($item['category'] ?? 'Walkthrough 3D'); ?>" placeholder="Contoh: Walkthrough 3D, Komersial, Residential">
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                  <label class="form-label">Durasi Video (Opsional)</label>
                  <input type="text" name="duration" class="form-control" value="<?= e($item['duration'] ?? ''); ?>" placeholder="Contoh: 03:45">
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                  <label class="form-label">Upload Custom Thumbnail (Opsional)</label>
                  <input type="file" name="thumbnail_file" class="form-control" accept="image/*">
                  <div style="font-size: 0.75rem; color: #9CA3AF; margin-top: 0.35rem;">Jika dikosongkan, sistem mengambil High Quality Thumbnail dari YouTube.</div>
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                  <label class="form-label">URL Thumbnail Custom (Opsional)</label>
                  <input type="text" name="thumbnail_url" class="form-control" value="<?= e($item['thumbnail_url'] ?? ''); ?>" placeholder="/assets/uploads/... atau https://...">
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                  <label class="form-label">Urutan Tampil (Sort Order)</label>
                  <input type="number" name="sort_order" class="form-control" value="<?= intval($item['sort_order'] ?? 0); ?>">
                </div>

                <div class="form-group" style="background: #111827; padding: 1rem; border-radius: 6px; border: 1px solid #374151; margin-top: 1rem;">
                  <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; color: #F3F4F6; font-weight: 600; margin: 0;">
                    <input type="checkbox" name="is_active" value="1" style="width: 18px; height: 18px; accent-color: var(--admin-primary);" <?= (!isset($item['is_active']) || $item['is_active'] == 1) ? 'checked' : ''; ?>>
                    Tampilkan Video Ini di Landing Page
                  </label>
                </div>
              </div>
            </div>

            <div class="form-actions" style="margin-top: 2rem; border-top: 1px solid #374151; padding-top: 1.25rem;">
              <button type="submit" class="btn-admin btn-admin-primary" style="padding: 0.75rem 1.5rem; font-size: 0.9rem;">
                💾 Simpan Video Showcase
              </button>
              <a href="/admin/videos.php" class="btn-admin btn-admin-secondary">Batal</a>
            </div>
          </form>
        </div>

      <?php else: ?>
        <!-- Search & Filter Toolbar -->
        <div class="toolbar-bar">
          <form action="/admin/videos.php" method="GET" class="search-box">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="q" class="search-input" placeholder="Cari judul, kategori, atau deskripsi..." value="<?= e($search); ?>">
          </form>

          <?php if (!empty($search)): ?>
            <a href="/admin/videos.php" style="color: #9CA3AF; font-size: 0.85rem; text-decoration: underline;">Reset Pencarian</a>
          <?php endif; ?>
        </div>

        <!-- Video Card Grid -->
        <?php if (empty($videos)): ?>
          <div class="card" style="text-align: center; padding: 3rem 1.5rem;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#6B7280" stroke-width="1.5" style="margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><path d="M10 15l5-3-5-3v6z"/></svg>
            <h4 style="color: #F9FAFB; margin-bottom: 0.5rem;">Belum ada video showcase yang ditemukan</h4>
            <p style="color: #9CA3AF; font-size: 0.875rem; margin-bottom: 1.5rem;">Klik tombol di bawah untuk menambahkan video tur arsitektur & walkthrough 3D pertama Anda.</p>
            <a href="/admin/videos.php?action=create" class="btn-admin btn-admin-primary" style="display: inline-block;">+ Tambah Video Baru</a>
          </div>
        <?php else: ?>
          <div class="admin-video-grid">
            <?php foreach ($videos as $vid): 
              $thumbSrc = getYouTubeThumbnail($vid['youtube_url'], $vid['thumbnail_url']);
              $ytId = getYouTubeVideoId($vid['youtube_url']);
            ?>
              <div class="admin-vcard">
                <div class="admin-vcard-thumb">
                  <img src="<?= e($thumbSrc); ?>" alt="Thumbnail" class="admin-vcard-img" onerror="this.src='/assets/images/placeholder_video.jpg';">
                  
                  <span class="admin-vcard-badge">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    <?= e($vid['category']); ?>
                  </span>

                  <?php if (!empty($vid['duration'])): ?>
                    <span class="admin-vcard-duration"><?= e($vid['duration']); ?></span>
                  <?php endif; ?>
                </div>

                <div class="admin-vcard-body">
                  <div class="admin-vcard-title"><?= e($vid['title']); ?></div>
                  <?php if (!empty($vid['description'])): ?>
                    <div class="admin-vcard-desc"><?= e($vid['description']); ?></div>
                  <?php endif; ?>

                  <div style="margin-bottom: 1rem;">
                    <a href="<?= e($vid['youtube_url']); ?>" target="_blank" class="yt-link-btn">
                      <span><?= e($vid['youtube_url']); ?></span>
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                    </a>
                  </div>

                  <div class="admin-vcard-footer">
                    <!-- Status Toggle Form -->
                    <form action="/admin/videos.php?id=<?= $vid['id']; ?>" method="POST" style="margin:0;">
                      <input type="hidden" name="post_action" value="toggle">
                      <?php if ($vid['is_active']): ?>
                        <button type="submit" class="badge badge-success" style="border:none; cursor:pointer;" title="Klik untuk non-aktifkan">✓ Aktif</button>
                      <?php else: ?>
                        <button type="submit" class="badge badge-danger" style="border:none; cursor:pointer;" title="Klik untuk aktifkan">✕ Draft / Off</button>
                      <?php endif; ?>
                    </form>

                    <!-- Edit & Delete Buttons -->
                    <div style="display: flex; gap: 0.35rem;">
                      <a href="/admin/videos.php?action=edit&id=<?= $vid['id']; ?>" class="btn-admin btn-admin-secondary" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">Edit</a>
                      <form action="/admin/videos.php?id=<?= $vid['id']; ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus video ini?');" style="margin: 0;">
                        <input type="hidden" name="post_action" value="delete">
                        <button type="submit" class="btn-admin btn-admin-danger" style="padding: 0.3rem 0.6rem; font-size: 0.75rem;">Hapus</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- Real-time YouTube Link Inspector Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const urlInput = document.getElementById('yt_url_input');
      const previewFrame = document.getElementById('yt_preview_frame');
      const previewStatus = document.getElementById('yt_preview_status');

      function extractYtId(url) {
        if (!url) return '';
        const pattern = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^"&?\/\s]{11})/;
        const match = url.match(pattern);
        return match ? match[1] : '';
      }

      function updatePreview() {
        if (!urlInput || !previewFrame) return;
        const url = urlInput.value.trim();
        const ytId = extractYtId(url);

        if (ytId) {
          previewFrame.innerHTML = `<img src="https://img.youtube.com/vi/${ytId}/hqdefault.jpg" alt="Preview">`;
          previewStatus.className = 'yt-preview-status';
          previewStatus.innerHTML = `<span>✓ Valid YouTube Video (ID: ${ytId})</span>`;
        } else if (url.length > 0) {
          previewFrame.innerHTML = `<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#EF4444;font-size:0.75rem;padding:0.5rem;text-align:center;">Link Tidak Valid</div>`;
          previewStatus.className = 'yt-preview-status invalid';
          previewStatus.innerHTML = `<span>✕ Format URL YouTube tidak dikenali</span>`;
        } else {
          previewFrame.innerHTML = `<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#6B7280;font-size:0.75rem;">No Link</div>`;
          previewStatus.className = 'yt-preview-status';
          previewStatus.innerHTML = `<span>Pratinjau Otomatis YouTube</span>`;
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
