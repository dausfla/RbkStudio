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
        <a href="<?= e($waUrlHeader); ?>" target="_blank" class="btn btn-primary header-cta" data-cta-code="UMUM">
          <svg class="wa-icon" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.705 1.754zm6.097-4.901l.374.222c1.464.869 3.146 1.328 4.863 1.329 5.26 0 9.54-4.28 9.543-9.544.001-2.548-.991-4.943-2.793-6.746-1.801-1.802-4.195-2.794-6.744-2.795-5.263 0-9.544 4.28-9.546 9.545-.001 1.78.47 3.515 1.365 5.064l.244.423-1.002 3.662 3.696-.969z"/></svg>
          Konsultasi
        </a>

        <button class="mobile-nav-toggle" id="mobileNavToggle" aria-label="Toggle navigation menu">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
      </div>
    </div>
  </header>
