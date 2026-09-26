<?php
/**
 * Admin Leads Log & Analytics - RBK Studio
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
checkAuth();

$db = getDB();
$flash = getFlash();

// Delete lead
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $stmt = $db->prepare("DELETE FROM leads WHERE id = ?");
    $stmt->execute([$deleteId]);
    setFlash('success', 'Log lead berhasil dihapus.');
    header('Location: /admin/leads.php');
    exit;
}

// Filter handling
$filter = $_GET['filter'] ?? 'all';
$sql = "SELECT * FROM leads";
$params = [];

if ($filter === 'brief') {
    $sql .= " WHERE source_cta = 'LENGKAP'";
} elseif ($filter === 'paket') {
    $sql .= " WHERE source_cta IN ('BASIC', 'STANDARD', 'PREMIUM', 'REKOMENDASI')";
} elseif ($filter === 'umum') {
    $sql .= " WHERE source_cta IN ('UMUM', 'SEBELUM-BANGUN', 'JADWAL', 'SLOT', 'TANYA')";
}
$sql .= " ORDER BY created_at DESC";

$leads = $db->prepare($sql);
$leads->execute($params);
$leadsList = $leads->fetchAll();

// Summary stats
$totalLeads = $db->query("SELECT COUNT(*) FROM leads")->fetchColumn();
$totalBriefs = $db->query("SELECT COUNT(*) FROM leads WHERE source_cta = 'LENGKAP'")->fetchColumn();
$totalPaket = $db->query("SELECT COUNT(*) FROM leads WHERE source_cta IN ('BASIC', 'STANDARD', 'PREMIUM', 'REKOMENDASI')")->fetchColumn();
$totalGeneral = $db->query("SELECT COUNT(*) FROM leads WHERE source_cta NOT IN ('LENGKAP', 'BASIC', 'STANDARD', 'PREMIUM', 'REKOMENDASI')")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WhatsApp Leads Log | RBK Studio CMS</title>
  <link rel="stylesheet" href="/assets/css/admin.css">
  <style>
    /* Modal styles for viewing complete brief message */
    .lead-modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(4px);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }
    .lead-modal-box {
      background: #FFFFFF;
      width: 100%;
      max-width: 600px;
      border-radius: 8px;
      padding: 2rem;
      box-shadow: 0 20px 40px rgba(0,0,0,0.3);
      position: relative;
      max-height: 90vh;
      overflow-y: auto;
    }
    .modal-close-btn {
      position: absolute;
      top: 1.25rem;
      right: 1.25rem;
      background: none;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      color: #6B7280;
    }
  </style>
</head>
<body class="admin-body">
  <?php include __DIR__ . '/sidebar.php'; ?>

  <div class="admin-main">
    <div class="admin-topbar">
      <div><strong>Log WhatsApp Leads & Analytics Konversi CTA</strong></div>
      <a href="/" target="_blank" class="btn-admin btn-admin-secondary">Lihat Website &rarr;</a>
    </div>

    <div class="admin-content">
      <?php if ($flash): ?>
        <div class="alert-flash <?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
      <?php endif; ?>

      <!-- Summary Stats Header -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-label">Total Leads WhatsApp</div>
          <div class="stat-value"><?= $totalLeads; ?></div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Form Brief (Lengkap)</div>
          <div class="stat-value" style="color: #10B981;"><?= $totalBriefs; ?></div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Klik CTA Paket</div>
          <div class="stat-value" style="color: #8B5CF6;"><?= $totalPaket; ?></div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Klik CTA Konsultasi</div>
          <div class="stat-value" style="color: #3B82F6;"><?= $totalGeneral; ?></div>
        </div>
      </div>

      <!-- Main Card Container -->
      <div class="card">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
          <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem;">Daftar Lead Konsultasi</h2>
            <p style="font-size: 0.875rem; color: #6B7280;">Log interaksi dari formulir brief terstruktur dan tombol CTA WhatsApp.</p>
          </div>

          <!-- Filter Pills -->
          <div class="filter-pills" style="margin-bottom: 0;">
            <a href="/admin/leads.php?filter=all" class="filter-pill <?= $filter === 'all' ? 'active' : ''; ?>">Semua Lead (<?= $totalLeads; ?>)</a>
            <a href="/admin/leads.php?filter=brief" class="filter-pill <?= $filter === 'brief' ? 'active' : ''; ?>">Brief Form (<?= $totalBriefs; ?>)</a>
            <a href="/admin/leads.php?filter=paket" class="filter-pill <?= $filter === 'paket' ? 'active' : ''; ?>">CTA Paket (<?= $totalPaket; ?>)</a>
            <a href="/admin/leads.php?filter=umum" class="filter-pill <?= $filter === 'umum' ? 'active' : ''; ?>">Konsultasi Umum (<?= $totalGeneral; ?>)</a>
          </div>
        </div>

        <?php if (empty($leadsList)): ?>
          <div style="text-align: center; padding: 4rem 2rem; color: #9CA3AF; background: #F9FAFB; border-radius: 6px; border: 1px dashed #E5E7EB;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 1rem; color: #D1D5DB;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <div style="font-size: 1rem; font-weight: 600; color: #374151;">Belum Ada Lead Tersimpan</div>
            <div style="font-size: 0.875rem; color: #6B7280; margin-top: 0.25rem;">Leads akan otomatis masuk ketika pengunjung mengklik CTA WhatsApp atau mengirim formulir brief.</div>
          </div>
        <?php else: ?>
          <!-- Scrollable Table Container -->
          <div class="table-responsive">
            <table class="admin-table">
              <thead>
                <tr>
                  <th style="width: 130px;">Waktu</th>
                  <th style="width: 140px;">Kode CTA</th>
                  <th style="width: 180px;">Lokasi Proyek</th>
                  <th style="width: 200px;">Spesifikasi Bangunan</th>
                  <th style="width: 180px;">Jenis & Style</th>
                  <th style="width: 160px;">Estimasi Budget</th>
                  <th style="width: 160px;">Detail Brief</th>
                  <th style="width: 90px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($leadsList as $lead): ?>
                  <?php 
                    $ctaCode = strtoupper($lead['source_cta'] ?? 'UMUM');
                    $badgeClass = 'umum';
                    if ($ctaCode === 'LENGKAP') {
                        $badgeClass = 'lengkap';
                    } elseif (in_array($ctaCode, ['BASIC', 'STANDARD', 'PREMIUM', 'REKOMENDASI'])) {
                        $badgeClass = 'paket';
                    } elseif (in_array($ctaCode, ['SEBELUM-BANGUN', 'RUMAH', 'KOMERSIAL'])) {
                        $badgeClass = 'sebelum';
                    }
                  ?>
                  <tr>
                    <td style="white-space: nowrap; font-size: 0.8125rem; color: #4B5563;">
                      <strong><?= date('d/m/Y', strtotime($lead['created_at'])); ?></strong><br>
                      <span style="color: #9CA3AF;"><?= date('H:i', strtotime($lead['created_at'])); ?> WIB</span>
                    </td>
                    <td>
                      <span class="badge-cta <?= $badgeClass; ?>"><?= e($ctaCode); ?></span>
                    </td>
                    <td>
                      <strong style="color: #111827; font-size: 0.9375rem;"><?= e($lead['location'] ?? 'Jabodetabek'); ?></strong>
                    </td>
                    <td style="font-size: 0.8125rem; color: #374151;">
                      <?php if (!empty($lead['land_area']) || !empty($lead['building_area'])): ?>
                        <div>Tanah: <strong><?= e($lead['land_area'] ?? '-'); ?> m²</strong></div>
                        <div>Bangunan: <strong><?= e($lead['building_area'] ?? '-'); ?> m²</strong> (<?= e($lead['floors'] ?? '-'); ?>)</div>
                      <?php else: ?>
                        <span style="color: #9CA3AF;">Belum diisi</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <div style="font-weight: 600; color: #1F2937;"><?= e($lead['building_type'] ?? 'Umum'); ?></div>
                      <div style="font-size: 0.75rem; color: #6B7280;"><?= e($lead['style'] ?? 'Custom'); ?></div>
                    </td>
                    <td style="font-size: 0.8125rem; font-weight: 600; color: var(--admin-primary);">
                      <?= e($lead['budget'] ?? '-'); ?>
                    </td>
                    <td>
                      <button type="button" class="btn-admin btn-admin-secondary btn-view-brief" style="padding: 0.35rem 0.75rem; font-size: 0.75rem;" data-message="<?= e($lead['full_message']); ?>" data-cta="<?= e($ctaCode); ?>" data-date="<?= date('d M Y H:i', strtotime($lead['created_at'])); ?>">
                        📄 Lihat Brief
                      </button>
                    </td>
                    <td style="text-align: center;">
                      <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log lead ini?');" style="display: inline;">
                        <input type="hidden" name="delete_id" value="<?= $lead['id']; ?>">
                        <button type="submit" class="btn-admin btn-admin-danger" style="padding: 0.35rem 0.6rem; font-size: 0.75rem;">Hapus</button>
                      </form>
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

  <!-- Lead Brief Viewer Modal -->
  <div class="lead-modal-overlay" id="leadModal">
    <div class="lead-modal-box">
      <button class="modal-close-btn" id="closeLeadModal">&times;</button>
      
      <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
        <span class="badge-cta lengkap" id="modalBadgeCTA">LENGKAP</span>
        <span style="font-size: 0.8125rem; color: #6B7280;" id="modalDate">24 Sep 2026</span>
      </div>

      <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;" class="font-serif">Rincian Brief Konsultasi WhatsApp</h3>

      <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 6px; padding: 1.25rem; font-family: monospace; font-size: 0.875rem; line-height: 1.6; white-space: pre-wrap; color: #1F2937;" id="modalMessageContent">
        <!-- Message payload will be inserted here -->
      </div>

      <div style="margin-top: 1.5rem; text-align: right;">
        <button type="button" class="btn-admin btn-admin-secondary" id="closeModalBtn">Tutup</button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const modal = document.getElementById('leadModal');
      const closeBtn = document.getElementById('closeLeadModal');
      const closeModalBtn = document.getElementById('closeModalBtn');
      const modalContent = document.getElementById('modalMessageContent');
      const modalBadge = document.getElementById('modalBadgeCTA');
      const modalDate = document.getElementById('modalDate');

      document.querySelectorAll('.btn-view-brief').forEach(btn => {
        btn.addEventListener('click', function () {
          const msg = this.getAttribute('data-message');
          const cta = this.getAttribute('data-cta');
          const date = this.getAttribute('data-date');

          modalContent.textContent = msg;
          modalBadge.textContent = cta;
          modalDate.textContent = date;
          modal.style.display = 'flex';
        });
      });

      function hideModal() {
        modal.style.display = 'none';
      }

      if (closeBtn) closeBtn.addEventListener('click', hideModal);
      if (closeModalBtn) closeModalBtn.addEventListener('click', hideModal);
      if (modal) {
        modal.addEventListener('click', function (e) {
          if (e.target === modal) hideModal();
        });
      }
    });
  </script>
</body>
</html>
