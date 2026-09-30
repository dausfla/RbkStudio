<?php
/**
 * Admin Dashboard Main Overview
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
checkAuth();

$db = getDB();

// Counts
$totalPortfolio = $db->query("SELECT COUNT(*) FROM portfolio")->fetchColumn();
$totalPackages = $db->query("SELECT COUNT(*) FROM pricing_packages")->fetchColumn();
$totalFaqs = $db->query("SELECT COUNT(*) FROM faqs")->fetchColumn();
$totalLeads = $db->query("SELECT COUNT(*) FROM leads")->fetchColumn();

// Recent Leads
$recentLeads = $db->query("SELECT * FROM leads ORDER BY created_at DESC LIMIT 5")->fetchAll();
$settings = getSettings();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Overview | RBK Studio CMS</title>
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
  <?php include __DIR__ . '/sidebar.php'; ?>

  <div class="admin-main">
    <div class="admin-topbar">
      <div>Selamat Datang, <strong><?= e($_SESSION['admin_user']['name']); ?></strong></div>
      <div>
        <a href="/" target="_blank" class="btn-admin btn-admin-secondary">Lihat Website &rarr;</a>
      </div>
    </div>

    <div class="admin-content">
      <div class="page-header">
        <h1 class="page-title">Overview Dashboard</h1>
      </div>

      <!-- Stats Cards -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-label">Total Portfolio</div>
          <div class="stat-value"><?= $totalPortfolio; ?></div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Paket Desain</div>
          <div class="stat-value"><?= $totalPackages; ?></div>
        </div>

        <div class="stat-card">
          <div class="stat-label">Total FAQ</div>
          <div class="stat-value"><?= $totalFaqs; ?></div>
        </div>

        <div class="stat-card">
          <div class="stat-label">WhatsApp Leads</div>
          <div class="stat-value" style="color: var(--admin-primary);"><?= $totalLeads; ?></div>
        </div>
      </div>

      <!-- Quick Actions & Status -->
      <div class="card">
        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem;">Pengaturan Utama</h3>
        <p style="font-size: 0.875rem; color: #4B5563; margin-bottom: 1rem;">
          Nomor WhatsApp Aktif: <strong><?= e($settings['wa_number'] ?? '081234500441'); ?></strong>
        </p>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
          <a href="/admin/portfolio.php?action=create" class="btn-admin btn-admin-primary">+ Tambah Portfolio Baru</a>
          <a href="/admin/content.php" class="btn-admin btn-admin-secondary">Ubah Copy & Nomor WA</a>
          <a href="/admin/faq.php" class="btn-admin btn-admin-secondary">Kelola Pertanyaan FAQ</a>
        </div>
      </div>

      <!-- Recent Leads -->
      <div class="card">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
          <h3 style="font-size: 1.125rem; font-weight: 700;">Leads WhatsApp Terbaru</h3>
          <a href="/admin/leads.php" style="font-size: 0.875rem; color: var(--admin-primary); font-weight: 600;">Lihat Semua &rarr;</a>
        </div>

        <?php if (empty($recentLeads)): ?>
          <p style="color: var(--admin-muted); font-size: 0.875rem;">Belum ada leads tersimpan. Leads akan muncul otomatis ketika pengunjung mengisi form brief atau mengklik CTA WhatsApp.</p>
        <?php else: ?>
          <table class="admin-table">
            <thead>
              <tr>
                <th>Waktu</th>
                <th>Kode CTA</th>
                <th>Lokasi</th>
                <th>Jenis</th>
                <th>Pesan Brief</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentLeads as $lead): ?>
                <tr>
                  <td><?= date('d M Y H:i', strtotime($lead['created_at'])); ?></td>
                  <td><span style="background: #E5E7EB; padding: 0.2rem 0.5rem; font-size: 0.75rem; font-weight: 700; border-radius: 2px;"><?= e($lead['source_cta'] ?? 'UMUM'); ?></span></td>
                  <td><?= e($lead['location'] ?? '-'); ?></td>
                  <td><?= e($lead['building_type'] ?? '-'); ?></td>
                  <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= e($lead['full_message'] ?? '-'); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
