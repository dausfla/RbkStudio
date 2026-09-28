<?php
/**
 * Header Component for RBK Studio
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

$settings = getSettings();
$waNumber = $settings['wa_number'] ?? '081234500441';

// Message prefill for general header consultation
$waMsgUmum = "Halo RBK Studio, saya ingin konsultasi desain untuk proyek saya. Lokasi proyek: ____. Jenis bangunan: ____.";
$waUrlHeader = buildWaUrl($waNumber, $waMsgUmum);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($settings['site_title'] ?? 'Jasa Desain Arsitektur Rumah, Ruko & Bangunan Jabodetabek | RBK Studio'); ?></title>
  <meta name="description" content="<?= e($settings['meta_description'] ?? 'RBK Studio menyediakan jasa desain arsitektur rumah, ruko dan bangunan di Jabodetabek.'); ?>">
  
  <!-- CSS Stylesheet -->
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

  <!-- Site Navigation Bar -->
  <header class="site-header">
    <div class="container header-container">
      <a href="/" class="brand-logo">
        <img src="/assets/images/logo-light.png" alt="Rancang Bangun Kreasi" class="site-logo-img">
      </a>

      <nav class="nav-menu" id="navMenu">
        <a href="#portfolio" class="nav-link">Portfolio</a>
        <a href="#layanan" class="nav-link">Layanan</a>
        <a href="#paket" class="nav-link">Paket & Investasi</a>
        <a href="#proses" class="nav-link">Proses</a>
        <a href="#tentang" class="nav-link">Tentang</a>
        <a href="#faq" class="nav-link">FAQ</a>
      </nav>

      <div style="display: flex; align-items: center; gap: 1rem;">
        <a href="<?= e($waUrlHeader); ?>" target="_blank" class="btn btn-primary header-cta" data-cta-code="UMUM" title="Konsultasi via WhatsApp">
          <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
          <span>Konsultasi</span>
        </a>

        <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle navigation menu">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
    </div>
  </header>
