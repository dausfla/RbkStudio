<?php
/**
 * RBK Studio - Landing Page Main Entry Point
 * Luxury Hard Selling Landing Page based on Brief Final
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

$db = getDB();
$settings = getSettings();
$waNumber = $settings['wa_number'] ?? '081234500441';

// Fetch active portfolios from database
$portfolios = $db->query("SELECT * FROM portfolio WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();

// Fetch active video showcases from database
$portfolioVideos = $db->query("SELECT * FROM portfolio_videos WHERE is_active = 1 ORDER BY sort_order ASC, id DESC")->fetchAll();

// Fetch active pricing packages from database
$packages = $db->query("SELECT * FROM pricing_packages ORDER BY sort_order ASC")->fetchAll();

// Fetch active FAQs from database
$faqs = $db->query("SELECT * FROM faqs WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<!-- ==========================================================================
     SECTION 01: HERO (TAHAP ATTENTION)
     ========================================================================== -->
<section class="hero-section" id="hero">
  <div class="hero-overlay"></div>
  <div class="container hero-content">
    <div class="eyebrow"><?= e($settings['hero_eyebrow'] ?? 'RBK Studio · Architecture & Planning'); ?></div>
    <h1 class="hero-title">Jasa Arsitek <span style="color: var(--rbk-orange);">Terbaik & Terlengkap</span> untuk Mewujudkan Bangunan Impian Anda</h1>
    
    <div style="font-size: clamp(1.25rem, 2.2vw, 1.6rem); font-weight: 700; color: #FFFFFF; margin-bottom: 1rem; border-left: 3px solid var(--rbk-orange); padding-left: 0.75rem; text-align: left;">
      Dari Konsep hingga Siap Dibangun, Semua Direncanakan dalam Satu Layanan.
    </div>

    <p class="hero-body">RBK Studio membantu merencanakan <strong>rumah, ruko, renovasi hingga bangunan komersial</strong> secara menyeluruh, mulai dari <strong>konsep arsitektur, visualisasi 3D, gambar kerja, struktur & MEP, hingga RAB.</strong></p>
    
    <div class="hero-cta-group">
      <?php 
        $waMsgUmum = "Halo RBK Studio, saya ingin konsultasi desain untuk proyek saya. Lokasi proyek: ____. Jenis bangunan: ____.";
      ?>
      <a href="<?= e(buildWaUrl($waNumber, $waMsgUmum)); ?>" target="_blank" class="btn btn-primary" data-cta-code="UMUM">
        <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
        Konsultasikan Proyek Anda
      </a>
      <a href="#paket" class="btn btn-outline">Lihat Paket Desain</a>
    </div>
    
    <p class="hero-microcopy">Bukan hanya indah secara visual, tetapi juga fungsional, terukur, efisien, dan siap direalisasikan.</p>

    <!-- Point 1: Hero Stats Banner Card (Image 1) -->
    <div class="hero-stats-banner">
      <div class="hero-stats-header">
        <div class="hero-stat-badge">
          <span class="badge-label">BERPENGALAMAN</span>
          <span class="badge-val">Sejak 2007</span>
        </div>
        <div class="hero-stat-badge">
          <span class="badge-label">PROYEK DIKERJAKAN</span>
          <span class="badge-val">1.350+ project</span>
        </div>
      </div>
      
      <div class="hero-stats-buttons">
        <a href="<?= e(buildWaUrl($waNumber, $waMsgUmum)); ?>" target="_blank" class="btn btn-primary" data-cta-code="UMUM">
          <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
          Konsultasikan sekarang
        </a>
        <a href="#paket" class="btn btn-outline" style="border: 1px solid rgba(255,255,255,0.4); background: transparent;">Lihat paket harga</a>
      </div>

      <div class="hero-stats-footer-grid">
        <div class="hero-stat-col">
          <div class="stat-main">Kota Bogor</div>
          <div class="stat-sub">Kantor pusat kami</div>
        </div>
        <div class="hero-stat-col">
          <div class="stat-main">Rp60 rb/m²</div>
          <div class="stat-sub">Desain / perencanaan mulai</div>
        </div>
        <div class="hero-stat-col">
          <div class="stat-main">Rp4 jt/m²</div>
          <div class="stat-sub">Bangun rumah mulai</div>
        </div>
        <div class="hero-stat-col">
          <div class="stat-main">Gratis</div>
          <div class="stat-sub">Konsultasi & survei (Jabodetabek)</div>
        </div>
      </div>
    </div>

    <!-- Running Text Ticker / Marquee Banner for Target Cities -->
    <div class="city-marquee-container">
      <div class="city-marquee-track">
        <div class="city-marquee-content">
          <span class="city-item">JASA DESAIN MULAI RP60.000/M²</span><span class="marquee-bullet">•</span>
          <span class="city-item">JAKARTA</span><span class="marquee-bullet">•</span>
          <span class="city-item">BOGOR</span><span class="marquee-bullet">•</span>
          <span class="city-item">DEPOK</span><span class="marquee-bullet">•</span>
          <span class="city-item">TANGERANG</span><span class="marquee-bullet">•</span>
          <span class="city-item">BEKASI</span><span class="marquee-bullet">•</span>
          <span class="city-item">JABODETABEK</span><span class="marquee-bullet">•</span>
        </div>
        <div class="city-marquee-content" aria-hidden="true">
          <span class="city-item">JASA DESAIN MULAI RP60.000/M²</span><span class="marquee-bullet">•</span>
          <span class="city-item">JAKARTA</span><span class="marquee-bullet">•</span>
          <span class="city-item">BOGOR</span><span class="marquee-bullet">•</span>
          <span class="city-item">DEPOK</span><span class="marquee-bullet">•</span>
          <span class="city-item">TANGERANG</span><span class="marquee-bullet">•</span>
          <span class="city-item">BEKASI</span><span class="marquee-bullet">•</span>
          <span class="city-item">JABODETABEK</span><span class="marquee-bullet">•</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Full-Width Running Text Branding Marquee Banner -->
<div class="brand-marquee-banner">
  <div class="brand-marquee-track">
    <div class="brand-marquee-content">
      <span>PLAN FIRST. BUILD ONCE.</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
      <span>ARSITEKTUR & INTERIOR PLANNING</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
      <span>DESAIN MENSIMULASIKAN ANGGARAN</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
      <span>GAMBAR KERJA DED & RAB TERUKUR</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
      <span>VISUALISASI 3D WALKTHROUGH</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
      <span>JABODETABEK</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
    </div>
    <div class="brand-marquee-content" aria-hidden="true">
      <span>PLAN FIRST. BUILD ONCE.</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
      <span>ARSITEKTUR & INTERIOR PLANNING</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
      <span>DESAIN MENSIMULASIKAN ANGGARAN</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
      <span>GAMBAR KERJA DED & RAB TERUKUR</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
      <span>VISUALISASI 3D WALKTHROUGH</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
      <span>JABODETABEK</span>
      <svg class="marquee-house-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5L12 3l9 7.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5z"/><path d="M6.5 7.5V4.5h2v1.5"/><path d="M7 21v-7h4v7"/><rect x="13" y="13.5" width="5" height="5"/><line x1="15.5" y1="13.5" x2="15.5" y2="18.5"/><line x1="13" y1="16" x2="18" y2="16"/></svg>
    </div>
  </div>
</div>

<!-- ==========================================================================
     SECTION 02: PROBLEM -> SOLUTION (TAHAP PROBLEM -> DESIRE)
     ========================================================================== -->
<section class="problem-part section-padding" id="problem">
  <div class="container">
    <div class="problem-eyebrow">KENAPA PERENCANAAN PENTING</div>
    <h2 class="problem-title">Bangun Sekali. Rencanakan dengan Benar Sejak Awal.</h2>
    <p class="problem-subtitle" style="color: var(--rbk-orange); font-weight: 600; margin-bottom: 2.5rem;">Jangan biarkan keputusan desain yang salah membuat Anda membayar dua kali.</p>

    <!-- Point 2: 4 Step Solution Cards (Matching Image 2) -->
    <div class="step-solution-grid">
      <div class="step-card">
        <div class="step-card-num">01</div>
        <h3 class="step-card-title">Rencanakan dengan matang</h3>
        <p class="step-card-desc">Tentukan kebutuhan ruang, jumlah kamar, dan rencana pengembangan rumah ke depan. Semua dituangkan ke dalam gambar desain yang jelas.</p>
      </div>

      <div class="step-card">
        <div class="step-card-num">02</div>
        <h3 class="step-card-title">Susun anggaran yang realistis</h3>
        <p class="step-card-desc">Biaya material, upah, perizinan, dan cadangan tak terduga dihitung di awal lewat RAB, supaya dana tidak habis di tengah jalan.</p>
      </div>

      <div class="step-card">
        <div class="step-card-num">03</div>
        <h3 class="step-card-title">Buat jadwal per tahap</h3>
        <p class="step-card-desc">Pekerjaan dibagi per tahap dengan target waktu masing-masing, sehingga progres mudah dipantau dari minggu ke minggu.</p>
      </div>

      <div class="step-card step-card-dark">
        <div class="step-card-num">04</div>
        <h3 class="step-card-title">Serahkan pada tim yang tepat</h3>
        <p class="step-card-desc">Pilih arsitek dan kontraktor yang terbuka soal harga dan spesifikasi. Di RBK, harga per m² dan material tiap paket ditulis sejak awal.</p>
      </div>
    </div>
  </div>
</section>

<div class="transition-bridge-box">
  <div class="container">
    <p class="bridge-text"><?= e($settings['section02_bridge'] ?? 'Desain bukan sekadar membuat bangunan terlihat bagus. Desain adalah keputusan yang menentukan bagaimana bangunan Anda dibangun, digunakan, dirawat, dan bernilai di masa depan.'); ?></p>
    <div class="transition-quote">"<?= e($settings['section02_transition'] ?? 'Investasikan pada perencanaannya sebelum menginvestasikan lebih besar pada konstruksinya.'); ?>"</div>
  </div>
</div>

<section class="solution-part section-padding" id="solution">
  <div class="container">
    <div class="eyebrow" style="margin-bottom: 0.5rem; color: var(--rbk-orange);">FILOSOFI DESAIN</div>
    <h2 class="section-title"><?= e($settings['section02_solution_headline'] ?? 'Bukan Sekadar Bagus di Render.'); ?></h2>
    <p class="section-subtitle" style="color: var(--rbk-orange); font-weight: 600;"><?= e($settings['section02_solution_subheadline'] ?? 'Tetapi Direncanakan untuk Bisa Dibangun.'); ?></p>
    
    <p class="solution-body"><?= e($settings['section02_solution_body'] ?? 'Kami percaya desain yang baik harus memiliki keseimbangan antara Estetika, Fungsi, Kenyamanan, Efisiensi, Teknis, dan Budget. Setiap ruang memiliki fungsi. Setiap ukuran memiliki alasan. Setiap detail direncanakan agar dapat direalisasikan secara presisi.'); ?></p>
    
    <p class="solution-closing"><?= e($settings['section02_solution_closing'] ?? 'Karena tujuan akhirnya bukan hanya menghasilkan gambar yang menarik, tetapi menciptakan bangunan yang benar-benar layak direalisasikan.'); ?></p>

    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
      <?php 
        $waMsgSebelum = "Halo RBK Studio, saya sedang merencanakan pembangunan dan ingin konsultasi sebelum konstruksi dimulai. Lokasi proyek: ____.";
      ?>
      <a href="<?= e(buildWaUrl($waNumber, $waMsgSebelum)); ?>" target="_blank" class="btn btn-primary" data-cta-code="SEBELUM-BANGUN">
        <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
        Konsultasi dengan RBK Studio
      </a>
      <a href="#portfolio" class="btn btn-outline">Lihat Portfolio</a>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 03: LINGKUP LAYANAN (TAHAP VALUE)
     ========================================================================== -->
<section class="theme-white section-padding" id="layanan">
  <div class="container">
    <div class="eyebrow" style="margin-bottom: 0.5rem; color: var(--rbk-orange);">LINGKUP LAYANAN</div>
    <h2 class="section-title"><?= e($settings['section03_headline'] ?? 'Semua Kebutuhan Desain dalam Satu Layanan'); ?></h2>
    <p class="section-subtitle" style="color: var(--rbk-orange); font-weight: 600;"><?= e($settings['section03_subheadline'] ?? 'Mulai dari Konsep Arsitektur hingga Dokumen Teknis Terukur'); ?></p>
    
    <p style="font-size: 1.0625rem; color: #525252; max-width: 760px; margin-bottom: 2.5rem; line-height: 1.7;">
      <?= e($settings['section03_body'] ?? 'RBK Studio menyediakan layanan perencanaan komprehensif untuk memastikan setiap tahap pembangunan direncanakan dengan tepat dan terintegrasi.'); ?>
    </p>

    <div class="values-grid">
      <div class="value-card">
        <div class="value-num">01</div>
        <div class="value-title">Konsep Arsitektur</div>
        <div class="value-desc">Konsep desain yang disesuaikan dengan kebutuhan, fungsi, lahan, dan karakter Anda.</div>
      </div>
      <div class="value-card">
        <div class="value-num">02</div>
        <div class="value-title">Space Planning</div>
        <div class="value-desc">Penataan ruang agar lebih nyaman, proporsional, dan efisien.</div>
      </div>
      <div class="value-card">
        <div class="value-num">03</div>
        <div class="value-title">Visualisasi 3D</div>
        <div class="value-desc">Lihat gambaran bangunan sebelum pembangunan dimulai.</div>
      </div>
      <div class="value-card">
        <div class="value-num">04</div>
        <div class="value-title">Gambar Kerja</div>
        <div class="value-desc">Panduan teknis yang lebih jelas untuk proses konstruksi.</div>
      </div>
      <div class="value-card">
        <div class="value-num">05</div>
        <div class="value-title">Struktur & MEP</div>
        <div class="value-desc">Perencanaan struktur, listrik, plumbing, dan kebutuhan teknis sesuai lingkup proyek.</div>
      </div>
      <div class="value-card">
        <div class="value-num">06</div>
        <div class="value-title">RAB</div>
        <div class="value-desc">Membantu memberikan gambaran kebutuhan biaya pembangunan secara lebih terukur.</div>
      </div>
    </div>

    <div class="brand-line-banner">
      <div class="brand-line-text">"<?= e($settings['brand_line_section_03'] ?? 'Elegan tanpa berlebihan. Fungsional tanpa kehilangan karakter.'); ?>"</div>
      <div style="margin-top: 1.5rem;">
        <a href="#paket" class="btn-text">PELAJARI LAYANAN &rarr;</a>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 04: SELECTED WORKS / PORTFOLIO (TAHAP PROOF)
     ========================================================================== -->
<section class="theme-light section-padding" id="portfolio">
  <div class="container">
    <div class="eyebrow" style="margin-bottom: 0.5rem; color: var(--rbk-orange);">PORTFOLIO</div>
    <h2 class="section-title">BUKAN SEKADAR RENDER.</h2>
    <p class="section-subtitle" style="color: var(--rbk-orange); font-weight: 600;">Setiap desain dimulai dari masalah yang harus diselesaikan.</p>

    <!-- Point 4: Before/After Renovation Showcase Cards (Matching Image 4 Top) -->
    <div class="before-after-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
      <!-- RenoVancy Card 1 -->
      <div class="ba-card" style="background: #FFF; border-radius: 8px; border: 1px solid #E5E5E5; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <div class="ba-media-split" style="position: relative; height: 200px; background: #262626; display: flex; align-items: center; justify-content: center; overflow: hidden;">
          <div style="position: absolute; inset: 0; display: grid; grid-template-columns: 1fr 1fr;">
            <div style="background: #1F1F1F; display: flex; align-items: center; justify-content: center; position: relative; border-right: 2px solid var(--rbk-orange);">
              <span style="position: absolute; top: 10px; left: 10px; font-size: 0.65rem; background: rgba(0,0,0,0.7); color: #FFF; padding: 2px 8px; border-radius: 4px; font-weight: 700;">BEFORE</span>
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#737373" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            </div>
            <div style="background: #2D2622; display: flex; align-items: center; justify-content: center; position: relative;">
              <span style="position: absolute; top: 10px; right: 10px; font-size: 0.65rem; background: var(--rbk-orange); color: #FFF; padding: 2px 8px; border-radius: 4px; font-weight: 700;">AFTER</span>
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--rbk-orange)" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
            </div>
          </div>
          <div style="position: absolute; width: 32px; height: 32px; background: var(--rbk-orange); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #FFF; font-size: 0.75rem; font-weight: 800; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">↔</div>
        </div>
        <div style="padding: 1.25rem;">
          <h4 style="font-size: 1.125rem; font-weight: 700; color: #0D0D0D; margin-bottom: 0.5rem;">RenoVancy Rumah Mr. Putra</h4>
          <span style="display: inline-block; font-size: 0.7rem; font-weight: 700; background: #F5F5F5; color: #525252; padding: 3px 8px; border-radius: 3px;">RENOVASI TOTAL</span>
        </div>
      </div>

      <!-- RenoVancy Card 2 -->
      <div class="ba-card" style="background: #FFF; border-radius: 8px; border: 1px solid #E5E5E5; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <div class="ba-media-split" style="position: relative; height: 200px; background: #262626; display: flex; align-items: center; justify-content: center; overflow: hidden;">
          <div style="position: absolute; inset: 0; display: grid; grid-template-columns: 1fr 1fr;">
            <div style="background: #1F1F1F; display: flex; align-items: center; justify-content: center; position: relative; border-right: 2px solid var(--rbk-orange);">
              <span style="position: absolute; top: 10px; left: 10px; font-size: 0.65rem; background: rgba(0,0,0,0.7); color: #FFF; padding: 2px 8px; border-radius: 4px; font-weight: 700;">BEFORE</span>
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#737373" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            </div>
            <div style="background: #2D2622; display: flex; align-items: center; justify-content: center; position: relative;">
              <span style="position: absolute; top: 10px; right: 10px; font-size: 0.65rem; background: var(--rbk-orange); color: #FFF; padding: 2px 8px; border-radius: 4px; font-weight: 700;">AFTER</span>
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--rbk-orange)" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/></svg>
            </div>
          </div>
          <div style="position: absolute; width: 32px; height: 32px; background: var(--rbk-orange); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #FFF; font-size: 0.75rem; font-weight: 800; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">↔</div>
        </div>
        <div style="padding: 1.25rem;">
          <h4 style="font-size: 1.125rem; font-weight: 700; color: #0D0D0D; margin-bottom: 0.5rem;">RenoVancy Rumah Mrs. Sela</h4>
          <span style="display: inline-block; font-size: 0.7rem; font-weight: 700; background: #F5F5F5; color: #525252; padding: 3px 8px; border-radius: 3px;">ROOFTOP</span>
        </div>
      </div>

      <!-- RenoVancy Card 3 -->
      <div class="ba-card" style="background: #FFF; border-radius: 8px; border: 1px solid #E5E5E5; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <div class="ba-media-split" style="position: relative; height: 200px; background: #262626; display: flex; align-items: center; justify-content: center; overflow: hidden;">
          <div style="position: absolute; inset: 0; display: grid; grid-template-columns: 1fr 1fr;">
            <div style="background: #1F1F1F; display: flex; align-items: center; justify-content: center; position: relative; border-right: 2px solid var(--rbk-orange);">
              <span style="position: absolute; top: 10px; left: 10px; font-size: 0.65rem; background: rgba(0,0,0,0.7); color: #FFF; padding: 2px 8px; border-radius: 4px; font-weight: 700;">BEFORE</span>
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#737373" stroke-width="1.5"><path d="M3 21h18M3 7l9-4 9 4v14H3V7z"/></svg>
            </div>
            <div style="background: #2D2622; display: flex; align-items: center; justify-content: center; position: relative;">
              <span style="position: absolute; top: 10px; right: 10px; font-size: 0.65rem; background: var(--rbk-orange); color: #FFF; padding: 2px 8px; border-radius: 4px; font-weight: 700;">AFTER</span>
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--rbk-orange)" stroke-width="1.5"><path d="M3 21h18M3 7l9-4 9 4v14H3V7z"/><path d="M9 21v-6h6v6"/></svg>
            </div>
          </div>
          <div style="position: absolute; width: 32px; height: 32px; background: var(--rbk-orange); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #FFF; font-size: 0.75rem; font-weight: 800; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">↔</div>
        </div>
        <div style="padding: 1.25rem;">
          <h4 style="font-size: 1.125rem; font-weight: 700; color: #0D0D0D; margin-bottom: 0.5rem;">Ruko Cigiringsing</h4>
          <span style="display: inline-block; font-size: 0.7rem; font-weight: 700; background: #F5F5F5; color: #525252; padding: 3px 8px; border-radius: 3px;">KOMERSIAL</span>
        </div>
      </div>
    </div>

    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
      <div style="font-size: 0.75rem; font-weight: 800; color: var(--rbk-orange); letter-spacing: 0.1em; text-transform: uppercase;">PROYEK SELESAI 100%</div>
      <!-- Portfolio Category Tabs per Point 4 PDF -->
      <div class="portfolio-tabs" style="margin-bottom: 0;">
        <button class="tab-btn active" data-filter="all">Semua</button>
        <button class="tab-btn" data-filter="Rumah">Rumah</button>
        <button class="tab-btn" data-filter="Kost">Kost</button>
        <button class="tab-btn" data-filter="Kantor & gudang">Kantor & gudang</button>
      </div>
    </div>

    <!-- Portfolio Grid -->
    <div class="portfolio-grid">
      <?php foreach ($portfolios as $item): ?>
        <div class="portfolio-card" data-category="<?= e($item['category']); ?>">
          <div class="portfolio-media">
            <?php if (!empty($item['image_url'])): ?>
              <img src="<?= e($item['image_url']); ?>" alt="<?= e($item['title']); ?>" class="portfolio-img">
            <?php else: ?>
              <div class="placeholder-render-box">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 0.75rem; color: var(--rbk-orange);"><path d="M3 21h18M3 7l9-4 9 4v14H3V7z"/><path d="M9 21v-6h6v6"/></svg>
                <div style="font-size: 0.875rem; font-weight: 600; color: #D4D4D4;"><?= e($item['title']); ?></div>
                <div style="font-size: 0.75rem; margin-top: 0.25rem;">[Visual Arsitektur RBK Studio]</div>
              </div>
            <?php endif; ?>
          </div>

          <div class="portfolio-body">
            <div class="portfolio-meta">
              <span class="portfolio-category"><?= e($item['category']); ?></span>
              <span class="portfolio-location"><?= e($item['location']); ?></span>
            </div>
            <h3 class="portfolio-title"><?= e($item['title']); ?></h3>
            <div class="portfolio-scope"><?= e($item['scope_tags']); ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Point 4: Bottom Projects List Note & CTA (Matching Image 4 Bottom) -->
    <div style="background: #FFF; padding: 1.25rem; border-radius: 6px; border: 1px solid #E5E5E5; margin-top: 2.5rem; text-align: center;">
      <p style="font-size: 0.875rem; color: #525252; margin-bottom: 1rem; max-width: 100%;">
        <strong>Proyek lain:</strong> Modiy Id Headquarters · Switch.Co Living/Kost · Sansweet Home & Wedding Gallery · Lula's Place · Orya Husna · ER House Santorini · dan lainnya.
      </p>
      <a href="<?= e(buildWaUrl($waNumber, 'Halo RBK Studio, saya ingin melihat galeri portfolio lengkap proyek RBK Studio.')); ?>" target="_blank" class="btn btn-outline" style="border-radius: 4px; padding: 0.6rem 1.25rem; font-size: 0.8rem;">
        Lihat hasil kerja lainnya ↗
      </a>
    </div>

    <?php if (!empty($portfolioVideos)): ?>
      <!-- Video Portfolio Showcase -->
      <div class="video-portfolio-section">
        <div class="video-section-header">
          <div class="video-eyebrow">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="color: #EF4444;"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            VIDEO SHOWCASE & WALKTHROUGH 3D
          </div>
          <h3 class="video-section-title">Visualisasi 3D & Walkthrough Proyek</h3>
          <p class="video-section-subtitle">Tonton preview animasi & video tur proyek RBK Studio secara langsung di YouTube.</p>
        </div>

        <div class="video-portfolio-grid">
          <?php foreach ($portfolioVideos as $video): 
            $thumbUrl = getYouTubeThumbnail($video['youtube_url'], $video['thumbnail_url']);
            $ytId = getYouTubeVideoId($video['youtube_url']);
          ?>
            <a href="<?= e($video['youtube_url']); ?>" target="_blank" rel="noopener noreferrer" class="video-card" data-youtube-id="<?= e($ytId); ?>" data-youtube-url="<?= e($video['youtube_url']); ?>">
              <div class="video-card-media">
                <img src="<?= e($thumbUrl); ?>" alt="<?= e($video['title']); ?>" class="video-card-img" loading="lazy" onerror="this.src='/assets/images/placeholder_video.jpg';">
                
                <div class="video-iframe-container"></div>

                <div class="video-play-overlay">
                  <div class="video-play-btn">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                  </div>
                </div>

                <?php if (!empty($video['duration'])): ?>
                  <span class="video-duration-badge"><?= e($video['duration']); ?></span>
                <?php endif; ?>

                <span class="video-yt-badge">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                  YouTube
                </span>
              </div>

              <div class="video-card-body">
                <div class="video-card-meta">
                  <span class="video-card-category"><?= e($video['category'] ?? 'Walkthrough'); ?></span>
                </div>
                <h4 class="video-card-title"><?= e($video['title']); ?></h4>
                <?php if (!empty($video['description'])): ?>
                  <p class="video-card-desc"><?= e($video['description']); ?></p>
                <?php endif; ?>
                <div class="video-card-link">
                  <span>Tonton di YouTube</span>
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 3.5rem;">
      <a href="<?= e(buildWaUrl($waNumber, 'Halo RBK Studio, saya ingin melihat galeri portfolio lengkap proyek arsitektur dan interior RBK Studio.')); ?>" target="_blank" class="btn btn-outline" data-cta-code="UMUM">
        <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
        Explore Our Projects &rarr;
      </a>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 05: WHY RBK STUDIO / KEUNGGULAN (TAHAP TRUST)
     ========================================================================== -->
<section class="theme-light section-padding" id="tentang">
  <div class="container">
    <div class="eyebrow" style="margin-bottom: 0.5rem; color: var(--rbk-orange);">KEUNGGULAN</div>
    <h2 class="section-title" style="letter-spacing: -0.02em;">KENAPA HARUS <span style="color: var(--rbk-orange);">RANCANG BANGUN KREASI?</span></h2>
    
    <!-- Point 3: 12 Numbered Keunggulan Cards (Matching Image 3) -->
    <div class="why12-grid">
      <div class="why12-card">
        <div class="why12-num">01</div>
        <h3 class="why12-title">Konsultasi & survei gratis</h3>
        <p class="why12-desc">Tanpa biaya untuk wilayah Jabodetabek, sebelum Anda memutuskan apa pun.</p>
      </div>

      <div class="why12-card">
        <div class="why12-num">02</div>
        <h3 class="why12-title">Kantor di Bogor</h3>
        <p class="why12-desc">Berkantor di Pasirmulya, Kota Bogor, jadi dekat dengan lokasi proyek Anda.</p>
      </div>

      <div class="why12-card">
        <div class="why12-num">03</div>
        <h3 class="why12-title">Harga per m² terbuka</h3>
        <p class="why12-desc">Paket Basic, Standard, dan Premium lengkap dengan kisaran harga sejak awal.</p>
      </div>

      <div class="why12-card">
        <div class="why12-num">04</div>
        <h3 class="why12-title">Spesifikasi material jelas</h3>
        <p class="why12-desc">Merek dan jenis material tiap paket ditulis, dari pondasi sampai sanitair.</p>
      </div>

      <div class="why12-card">
        <div class="why12-num">05</div>
        <h3 class="why12-title">Desain mulai Rp60 rb/m²</h3>
        <p class="why12-desc">Butuh gambar saja? Paket perencanaan tersedia terpisah dari konstruksi.</p>
      </div>

      <div class="why12-card">
        <div class="why12-num">06</div>
        <h3 class="why12-title">Harga fleksibel</h3>
        <p class="why12-desc">Rencana dan spesifikasi bisa disesuaikan dengan kemampuan anggaran Anda.</p>
      </div>

      <div class="why12-card">
        <div class="why12-num">07</div>
        <h3 class="why12-title">Satu tim, desain sampai bangun</h3>
        <p class="why12-desc">RBK Studio, RBK Konstruksi, dan RBK Kreasi bekerja dalam satu alur.</p>
      </div>

      <div class="why12-card">
        <div class="why12-num">08</div>
        <h3 class="why12-title">Pengalaman developer</h3>
        <p class="why12-desc">Grup kami membangun perumahan Eltama Property di Kota dan Kabupaten Bogor.</p>
      </div>

      <div class="why12-card">
        <div class="why12-num">09</div>
        <h3 class="why12-title">Arsitek berpengalaman</h3>
        <p class="why12-desc">Perencanaan ditangani tenaga ahli arsitek dengan pengalaman internasional.</p>
      </div>

      <div class="why12-card">
        <div class="why12-num">10</div>
        <h3 class="why12-title">Tenaga kerja profesional</h3>
        <p class="why12-desc">Tukang dan pengawas lapangan yang terbiasa dengan proyek rumah dan komersial.</p>
      </div>

      <div class="why12-card">
        <div class="why12-num">11</div>
        <h3 class="why12-title">Kalkulator RAB</h3>
        <p class="why12-desc">Cek perkiraan biaya pembangunan kapan saja lewat kalkulator di situs RBK.</p>
      </div>

      <div class="why12-card">
        <div class="why12-num">12</div>
        <h3 class="why12-title">Admin responsif, jujur & amanah</h3>
        <p class="why12-desc">Pertanyaan dijawab cepat di jam kerja, dan setiap proses dijalankan secara terbuka.</p>
      </div>
    </div>
    </div>

    <div style="margin-top: 3.5rem; text-align: center;">
      <a href="<?= e(buildWaUrl($waNumber, $waMsgUmum)); ?>" target="_blank" class="btn btn-primary" data-cta-code="UMUM">
        <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
        Konsultasikan Proyek Anda
      </a>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 06: PAKET & INVESTASI DESAIN (TAHAP PRICING)
     ========================================================================== -->
<section class="theme-light section-padding" id="paket">
  <div class="container">
    <h2 class="section-title">Investasi Kecil pada Desain. Dampaknya Besar pada Keseluruhan Proyek.</h2>
    <p style="font-size: 1.0625rem; color: #525252; max-width: 820px; line-height: 1.7; margin-bottom: 2.5rem;">
      Anda mungkin akan menginvestasikan ratusan juta hingga miliaran rupiah untuk membangun properti. Maka pertanyaannya bukan “Berapa biaya desainnya?”, tetapi “Berapa besar risiko yang bisa saya hindari ketika semuanya direncanakan dengan benar sejak awal?”
    </p>

    <!-- Live Interactive Cost Estimator Calculator Widget -->
    <div class="estimator-widget">
      <div class="estimator-grid">
        <div>
          <div class="eyebrow" style="margin-bottom: 0.5rem; color: var(--rbk-orange);">SIMULASI INTERAKTIF</div>
          <h3 style="font-size: 1.75rem; color: #FFF; margin-bottom: 1rem;" class="font-serif">Kalkulator Estimasi Biaya Desain</h3>
          <p style="font-size: 0.875rem; color: #A3A3A3; margin-bottom: 1.5rem; line-height: 1.6;">
            Geser slider luas bangunan untuk menghitung perkiraan investasi desain & estimasi durasi pengerjaan secara real-time.
          </p>

          <div style="margin-bottom: 1.5rem;">
            <label style="display: flex; justify-content: space-between; font-size: 0.875rem; color: #D4D4D4; margin-bottom: 0.5rem; font-weight: 600;">
              <span>Rencana Luas Bangunan:</span>
              <span id="calcAreaValue" style="color: var(--rbk-orange); font-size: 1.125rem;">150 m²</span>
            </label>
            <input type="range" id="calcAreaSlider" class="estimator-range-slider" min="100" max="1000" step="10" value="150">
            <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #737373; margin-top: 0.35rem;">
              <span>100 m² (Min)</span>
              <span>500 m²</span>
              <span>1.000 m²</span>
            </div>
          </div>

          <div>
            <div style="font-size: 0.9375rem; color: #A3A3A3; margin-bottom: 0.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Pilih Paket Desain:</div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
              <button type="button" class="calc-pkg-btn" data-price="60000" data-name="Basic" data-code="BASIC">Basic (60rb/m²)</button>
              <button type="button" class="calc-pkg-btn active" data-price="80000" data-name="Standard" data-code="STANDARD">Standard (80rb/m²)</button>
              <button type="button" class="calc-pkg-btn" data-price="150000" data-name="Premium" data-code="PREMIUM">Premium (150rb/m²)</button>
            </div>
          </div>
        </div>

        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); padding: 2rem; border-radius: 6px; text-align: center;">
          <div style="font-size: 0.9375rem; color: #A3A3A3; text-transform: uppercase; letter-spacing: 0.1em;">Estimasi Biaya Desain:</div>
          <div class="result-price-big" id="calcTotalCost">Rp12.000.000</div>
          
          <div style="font-size: 0.9375rem; color: #D4D4D4; margin-bottom: 1.5rem;">
            Perkiraan Waktu: <strong id="calcDuration" style="color: #FFF;">14-28 Hari Kerja</strong>
          </div>

          <a href="#" id="calcWaBtn" target="_blank" class="btn btn-primary" style="width: 100%; justify-content: center;" data-wa="<?= e($waNumber); ?>" data-cta-code="STANDARD">
            <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
            Konsultasi Hasil Simulasi Ini
          </a>
        </div>
      </div>
    </div>

    <!-- Pricing Cards Grid -->
    <div class="pricing-grid">
      <?php foreach ($packages as $pkg): ?>
        <?php 
          $isFeatured = $pkg['is_featured'] == 1;
          $cardClass = $isFeatured ? 'pricing-card featured' : 'pricing-card';
          $includes = array_filter(explode("\n", $pkg['included_items'] ?? ''));
          
          // Generate WA message according to package
          $pkgCode = strtoupper($pkg['code']);
          if ($pkgCode === 'BASIC') {
              $waMsgPkg = "Halo RBK Studio, saya tertarik dengan Paket Basic. Lokasi proyek: ____. Rencana luas bangunan: ____.";
          } elseif ($pkgCode === 'STANDARD') {
              $waMsgPkg = "Halo RBK Studio, saya ingin konsultasi Paket Standard. Lokasi proyek: ____. Rencana luas bangunan: ____.";
          } else {
              $waMsgPkg = "Halo RBK Studio, saya ingin mendiskusikan proyek dengan Paket Premium. Lokasi proyek: ____. Jenis bangunan: ____.";
          }
        ?>
        <div class="<?= $cardClass; ?>">
          <?php if (!empty($pkg['badge_label'])): ?>
            <div class="package-badge"><?= e($pkg['badge_label']); ?></div>
          <?php endif; ?>

          <div class="package-name"><?= e($pkg['name']); ?></div>
          <div class="package-subname"><?= e($pkg['subname']); ?></div>

          <div class="package-price">
            <?= e($pkg['price_formatted']); ?>
          </div>

          <div class="package-desc"><?= e($pkg['description']); ?></div>

          <?php if (!empty($pkg['ideal_for'])): ?>
            <div class="package-ideal">
              <strong>Ideal untuk:</strong><br><?= e($pkg['ideal_for']); ?>
            </div>
          <?php endif; ?>

          <div class="package-includes-title">Termasuk Layanan:</div>
          <ul class="package-includes-list">
            <?php foreach ($includes as $inc): ?>
              <li><?= e(trim($inc)); ?></li>
            <?php endforeach; ?>
          </ul>

          <?php if (!empty($pkg['positioning_line'])): ?>
            <div class="package-positioning">"<?= e($pkg['positioning_line']); ?>"</div>
          <?php endif; ?>

          <div style="margin-top: auto;">
            <a href="<?= e(buildWaUrl($waNumber, $waMsgPkg)); ?>" target="_blank" class="btn <?= $isFeatured ? 'btn-primary' : 'btn-outline'; ?>" style="width: 100%;" data-cta-code="<?= e($pkgCode); ?>">
              <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
              <?= e($pkg['cta_text']); ?>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- ==========================================================================
         POINT 5: PAKET BANGUN RUMAH PER M² & KALKULATOR BIAYA BANGUN
         ========================================================================== -->
    <div style="margin-top: 5rem; padding-top: 4rem; border-top: 1px solid rgba(0,0,0,0.1);" id="paket-bangun">
      <div class="eyebrow" style="margin-bottom: 0.5rem; color: var(--rbk-orange);">HARGA TRANSPARAN</div>
      <h2 class="section-title" style="letter-spacing: -0.02em;">PAKET BANGUN RUMAH <span style="color: var(--rbk-orange);">PER M²</span></h2>
      <p style="font-size: 1rem; color: #525252; max-width: 800px; margin-bottom: 2.5rem;">
        Pilih paket sesuai spesifikasi material yang Anda inginkan. Harga khusus wilayah Bogor.
      </p>

      <div class="construction-pricing-grid">
        <!-- BASIC -->
        <div class="const-card">
          <div class="const-pkg-name">BASIC</div>
          <div class="const-pkg-price">Rp4–4,5 jt</div>
          <div class="const-pkg-unit">per m² luas bangunan</div>
          <ul class="const-spec-list">
            <li><span>Struktur</span> <strong>Kolom praktis, pondasi batu kali</strong></li>
            <li><span>Lantai utama</span> <strong>Keramik Roman</strong></li>
            <li><span>Kusen & pintu</span> <strong>Kayu meranti</strong></li>
            <li><span>Cat</span> <strong>Vinilex</strong></li>
            <li><span>Atap</span> <strong>Baja ringan, genteng metal</strong></li>
            <li><span>Sanitair</span> <strong>INA</strong></li>
          </ul>
          <a href="<?= e(buildWaUrl($waNumber, "Halo RBK Studio, saya ingin konsultasi Paket Bangun Rumah Basic (Rp4-4,5 jt/m²).")); ?>" target="_blank" class="btn btn-outline" style="width: 100%; border-radius: 20px; font-size: 0.8rem; background: #0D0D0D;">Pilih Basic</a>
        </div>

        <!-- STANDARD (PALING SEIMBANG) -->
        <div class="const-card featured">
          <div class="const-badge">PALING SEIMBANG</div>
          <div class="const-pkg-name">STANDARD</div>
          <div class="const-pkg-price">Rp4,5–5 jt</div>
          <div class="const-pkg-unit">per m² luas bangunan</div>
          <ul class="const-spec-list">
            <li><span>Struktur</span> <strong>Kolom, pondasi batu kali</strong></li>
            <li><span>Dinding</span> <strong>Hebel</strong></li>
            <li><span>Kusen & jendela</span> <strong>Aluminium</strong></li>
            <li><span>Plafon</span> <strong>Gypsum + rangka hollow</strong></li>
            <li><span>Atap</span> <strong>Baja ringan, metal/beton</strong></li>
            <li><span>Sanitair</span> <strong>Setara American Standard</strong></li>
          </ul>
          <a href="<?= e(buildWaUrl($waNumber, "Halo RBK Studio, saya ingin konsultasi Paket Bangun Rumah Standard (Rp4,5-5 jt/m²).")); ?>" target="_blank" class="btn btn-primary" style="width: 100%; border-radius: 20px; font-size: 0.8rem;">Pilih Standard</a>
        </div>

        <!-- PREMIUM -->
        <div class="const-card">
          <div class="const-pkg-name">PREMIUM</div>
          <div class="const-pkg-price">Rp6–7,5 jt</div>
          <div class="const-pkg-unit">per m² luas bangunan</div>
          <ul class="const-spec-list">
            <li><span>Struktur</span> <strong>Footplate / batu kali</strong></li>
            <li><span>Lantai utama</span> <strong>Granit</strong></li>
            <li><span>Dinding</span> <strong>Bata merah</strong></li>
            <li><span>Kusen</span> <strong>Aluminium 4" Alexindo</strong></li>
            <li><span>Cat & listrik</span> <strong>Dulux - Panasonic</strong></li>
            <li><span>Sanitair</span> <strong>Setara Toto</strong></li>
          </ul>
          <a href="<?= e(buildWaUrl($waNumber, "Halo RBK Studio, saya ingin konsultasi Paket Bangun Rumah Premium (Rp6-7,5 jt/m²).")); ?>" target="_blank" class="btn btn-outline" style="width: 100%; border-radius: 20px; font-size: 0.8rem; background: #0D0D0D;">Pilih Premium</a>
        </div>
      </div>

      <p style="font-size: 0.8rem; color: #737373; margin-top: 1rem; margin-bottom: 2rem;">
        *Harga belum termasuk bangunan pendukung (pagar, carport, dll). Angka final ditetapkan setelah survei dan RAB.
      </p>

      <!-- Sub-banner: Hanya Butuh Desain? -->
      <div class="design-only-banner">
        <div>
          <div style="font-size: 0.75rem; font-weight: 800; color: var(--rbk-orange); letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.25rem;">HANYA BUTUH DESAIN?</div>
          <div style="font-size: 1.125rem; font-weight: 700; color: #FFF;">Paket perencanaan (gambar arsitektur) mulai <span style="color: var(--rbk-orange);">Rp60.000/m²</span></div>
        </div>
        <a href="#paket" class="btn btn-outline" style="border-radius: 20px; padding: 0.5rem 1.5rem; font-size: 0.8rem; background: #FFF; color: #0D0D0D !important;">Pesan desain</a>
      </div>

      <!-- Live Construction Estimator Box (Matching Image 5 Bottom) -->
      <div class="construction-calc-box">
        <div class="const-calc-grid">
          <div>
            <div style="font-size: 0.75rem; font-weight: 800; color: var(--rbk-orange); letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.25rem;">KALKULATOR CEPAT</div>
            <h3 style="font-size: 1.5rem; color: #FFF; font-weight: 800; margin-bottom: 1.25rem;">ESTIMASI BIAYA BANGUN</h3>
            
            <div style="margin-bottom: 1.25rem;">
              <label style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #D4D4D4; margin-bottom: 0.35rem; font-weight: 600;">
                <span>Luas bangunan (total semua lantai)</span>
                <span id="constAreaVal" style="color: #FFF; font-weight: 800;">120 m²</span>
              </label>
              <input type="range" id="constAreaSlider" class="estimator-range-slider" min="36" max="1000" step="5" value="120">
            </div>

            <div>
              <div style="font-size: 0.8rem; color: #A3A3A3; margin-bottom: 0.35rem; font-weight: 600;">Paket</div>
              <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;" id="constPkgBtnGroup">
                <button type="button" class="const-pkg-btn" data-min="4" data-max="4.5" data-name="Basic">Basic</button>
                <button type="button" class="const-pkg-btn active" data-min="4.5" data-max="5" data-name="Standard">Standard</button>
                <button type="button" class="const-pkg-btn" data-min="6" data-max="7.5" data-name="Premium">Premium</button>
              </div>
            </div>
            <p style="font-size: 0.7rem; color: #737373; margin-top: 1rem;">Estimasi kasar dari harga per m² di atas, bukan penawaran resmi.</p>
          </div>

          <div class="const-calc-result-panel">
            <div style="font-size: 0.75rem; font-weight: 700; color: #A3A3A3; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem;">PERKIRAAN BIAYA KONSTRUKSI</div>
            <div class="const-result-price" id="constTotalCost">Rp540–600 jt</div>
            <p class="const-result-sub" id="constTotalSub">120 m² x Rp4.5-5 jt/m² (Paket Standard). Desain mulai Rp7,2 jt.</p>
            <a href="#" id="constWaBtn" target="_blank" class="btn btn-outline" style="border-radius: 20px; padding: 0.6rem 1.25rem; font-size: 0.8rem; background: #FFF; color: #0D0D0D !important; font-weight: 700; width: 100%; justify-content: center;" data-wa="<?= e($waNumber); ?>">
              Minta RAB detail gratis →
            </a>
          </div>
        </div>
      </div>
    </div>

    <p style="text-align: center; font-size: 0.9375rem; color: #737373; margin-top: 1.5rem;">
      Biaya desain dihitung dari luas bangunan yang direncanakan. Tanda jadi dan termin pembayaran dijelaskan di FAQ.
    </p>

    <!-- Package Comparison Table -->
    <div class="pricing-table-container">
      <h3 style="font-size: 1.5rem; margin-bottom: 1.5rem; text-align: center;" class="font-serif">Tabel Perbandingan Fitur Paket</h3>
      <table class="pricing-table">
        <thead>
          <tr>
            <th>Kriteria</th>
            <th>BASIC</th>
            <th>STANDARD</th>
            <th>PREMIUM</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Investasi / m²</strong></td>
            <td>Rp60.000/m²</td>
            <td><strong>Rp80.000/m²</strong></td>
            <td>Rp150.000/m²</td>
          </tr>
          <tr>
            <td><strong>Positioning</strong></td>
            <td>Essential Architecture</td>
            <td><strong>Recommended</strong></td>
            <td>Signature Service</td>
          </tr>
          <tr>
            <td><strong>Cocok Untuk</strong></td>
            <td>Proyek sederhana / renovasi dasar</td>
            <td><strong>Mayoritas hunian & ruko</strong></td>
            <td>Proyek premium / villa / komersial</td>
          </tr>
          <tr>
            <td><strong>Kompleksitas</strong></td>
            <td>Basic</td>
            <td><strong>Medium</strong></td>
            <td>High / Custom</td>
          </tr>
          <tr>
            <td><strong>Pendekatan</strong></td>
            <td>Functional</td>
            <td><strong>Comprehensive</strong></td>
            <td>Bespoke Luxury</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Recommendation Bridge CTA -->
    <?php 
      $waMsgRekomendasi = "Halo RBK Studio, saya ingin rekomendasi paket desain. Jenis bangunan: ____. Rencana luas bangunan: ____. Jumlah lantai: ____.";
    ?>
    <div class="recommend-cta-box">
      <h4 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">Belum tahu paket yang sesuai?</h4>
      <p style="font-size: 0.9375rem; color: #525252; margin-bottom: 1.5rem;">Anda tidak perlu memutuskan sendiri. Ceritakan proyek Anda kepada tim kami untuk mendapatkan rekomendasi paket yang paling pas.</p>
      <a href="<?= e(buildWaUrl($waNumber, $waMsgRekomendasi)); ?>" target="_blank" class="btn btn-primary" data-cta-code="REKOMENDASI">
        <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
        Minta Rekomendasi Paket
      </a>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 07: HARD-SELL VALUE (TAHAP VALUE / RISK)
     ========================================================================== -->
<section class="hardsell-band" id="hardsell">
  <div class="container" style="text-align: left; max-width: 1000px;">
    <h2 class="hardsell-headline">
      <span class="text-white">INVESTASI KECIL PADA </span><br>
      <span class="text-black">DESAIN. DAMPAKNYA BESAR PADA PEMBANGUNAN.</span>
    </h2>
    
    <p class="hardsell-body"><?= e($settings['section07_body'] ?? 'Ketika struktur sudah berdiri, dinding sudah terbangun, dan instalasi sudah tertanam, perubahan dapat menjadi jauh lebih mahal.'); ?></p>
    
    <div class="hardsell-motto">
      PLAN FIRST.<br>BUILD ONCE.
    </div>

    <p class="hardsell-closing"><?= e($settings['section07_closing'] ?? 'Rencanakan keputusan penting sebelum proses konstruksi dimulai.'); ?></p>

    <a href="<?= e(buildWaUrl($waNumber, $waMsgSebelum)); ?>" target="_blank" class="btn btn-black-pill" data-cta-code="SEBELUM-BANGUN">
      <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
      Konsultasikan proyek Anda
    </a>
  </div>
</section>

<!-- ==========================================================================
     SECTION 08: PROCESS (TAHAP PROCESS)
     ========================================================================== -->
<section class="theme-white section-padding" id="proses">
  <div class="container">
    <div class="eyebrow" style="margin-bottom: 0.5rem; color: var(--rbk-orange);">ALUR KERJA</div>
    <h2 class="section-title">Proses yang Jelas dari Awal</h2>
    <p class="section-subtitle">Alur kerja transparan, terstruktur, dan aman dari tahap konsultasi hingga penyerahan berkas.</p>

    <div class="process-timeline">
      <div class="process-step">
        <div class="step-num">01</div>
        <div class="step-title">Consultation</div>
        <div class="step-desc">Memahami kebutuhan, lokasi, fungsi, style, dan budget.</div>
      </div>

      <div class="process-step">
        <div class="step-num">02</div>
        <div class="step-title">Concept Development</div>
        <div class="step-desc">Mengembangkan konsep, layout, dan karakter desain.</div>
      </div>

      <div class="process-step">
        <div class="step-num">03</div>
        <div class="step-title">Design Development</div>
        <div class="step-desc">Mematangkan ruang, material, dan detail.</div>
      </div>

      <div class="process-step">
        <div class="step-num">04</div>
        <div class="step-title">Technical Planning</div>
        <div class="step-desc">Menyiapkan kebutuhan teknis sesuai scope proyek.</div>
      </div>

      <div class="process-step">
        <div class="step-num">05</div>
        <div class="step-title">Budget Planning</div>
        <div class="step-desc">Memberikan gambaran kebutuhan biaya melalui RAB.</div>
      </div>

      <div class="process-step">
        <div class="step-num">06</div>
        <div class="step-title">Final Delivery</div>
        <div class="step-desc">Dokumen desain disiapkan untuk proses pembangunan.</div>
      </div>
    </div>

    <p style="text-align: center; font-size: 0.9375rem; color: #737373; margin-top: 3rem;">
      Pembayaran dilakukan bertahap mengikuti persetujuan setiap tahap desain. Detail ada di FAQ.
    </p>

    <div style="text-align: center; margin-top: 2rem;">
      <a href="<?= e(buildWaUrl($waNumber, $waMsgUmum)); ?>" target="_blank" class="btn btn-primary" data-cta-code="UMUM">
        <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
        Mulai Konsultasi
      </a>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 09: DESAIN UNTUK KEBUTUHAN BANGUNAN (TAHAP SEGMENT)
     ========================================================================== -->
<section class="theme-light section-padding" id="segment">
  <div class="container">
    <h2 class="section-title">Desain untuk Kebutuhan Bangunan yang Berbeda</h2>
    
    <div class="segment-panels">
      <!-- Panel Residential -->
      <div class="segment-panel">
        <h3 class="segment-title">Rumah Bukan Sekadar Tempat Tinggal.</h3>
        <p class="segment-body">Rumah adalah tempat Anda membesarkan keluarga, beristirahat, menerima orang-orang penting, merayakan pencapaian, dan menjalani sebagian besar hidup Anda.</p>
        <p class="segment-closing">Jika Anda akan membangunnya sekali, bangun sesuatu yang benar-benar layak Anda miliki.</p>
        
        <div class="segment-keywords">
          <span class="keyword-tag">Keluarga</span>
          <span class="keyword-tag">Aktivitas</span>
          <span class="keyword-tag">Kenyamanan</span>
          <span class="keyword-tag">Privasi</span>
          <span class="keyword-tag">Karakter Penghuni</span>
        </div>

        <?php $waMsgRumah = "Halo RBK Studio, saya ingin konsultasi desain rumah. Lokasi proyek: ____. Luas tanah: ____. Jumlah lantai: ____."; ?>
        <a href="<?= e(buildWaUrl($waNumber, $waMsgRumah)); ?>" target="_blank" class="btn btn-primary" data-cta-code="RUMAH">
          <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
          Konsultasi Desain Rumah
        </a>
      </div>

      <!-- Panel Commercial / Ruko -->
      <div class="segment-panel">
        <h3 class="segment-title">Untuk Ruko & Bangunan Komersial</h3>
        <p class="segment-body">Desain yang baik bukan hanya soal tampilan. Bangunan bisnis harus memperhitungkan customer flow, akses, branding, visibilitas, operasional, efisiensi ruang, kenyamanan, dan potensi pengembangan bisnis.</p>
        <p class="segment-closing">Kami membantu Anda merencanakan bangunan komersial yang bekerja untuk bisnis Anda.</p>
        
        <div class="segment-keywords">
          <span class="keyword-tag">Customer Flow</span>
          <span class="keyword-tag">Akses</span>
          <span class="keyword-tag">Branding</span>
          <span class="keyword-tag">Visibilitas</span>
          <span class="keyword-tag">Efisiensi Ruang</span>
        </div>

        <?php $waMsgKomersial = "Halo RBK Studio, saya ingin konsultasi desain ruko/bangunan komersial. Lokasi proyek: ____. Jenis usaha: ____."; ?>
        <a href="<?= e(buildWaUrl($waNumber, $waMsgKomersial)); ?>" target="_blank" class="btn btn-primary" data-cta-code="KOMERSIAL">
          <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
          Konsultasikan Ruko / Bangunan Komersial
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     BLOK FAQ (ACCORDION BLOCK BETWEEN SECTION 09 & 10)
     ========================================================================== -->
<section class="theme-white section-padding" id="faq">
  <div class="container">
    <h2 class="section-title" style="text-align: center;">Pertanyaan yang Sering Diajukan</h2>

    <div class="faq-container">
      <?php foreach ($faqs as $faq): ?>
        <div class="faq-item">
          <button class="faq-button">
            <span><?= e($faq['question']); ?></span>
            <span class="faq-icon">+</span>
          </button>
          <div class="faq-answer">
            <p><?= nl2br(e($faq['answer'])); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 3.5rem;">
      <p style="font-size: 1rem; color: #525252; margin-bottom: 1.5rem;">Masih ada pertanyaan? Tanyakan langsung via WhatsApp.</p>
      <?php $waMsgTanya = "Halo RBK Studio, saya punya pertanyaan tentang layanan desain: ____."; ?>
      <a href="<?= e(buildWaUrl($waNumber, $waMsgTanya)); ?>" target="_blank" class="btn btn-outline" data-cta-code="TANYA">
        <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
        Tanyakan via WhatsApp
      </a>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 10: JADWAL KONSULTASI / CAPACITY (TAHAP CTA)
     ========================================================================== -->
<section class="capacity-band" id="capacity">
  <div class="container capacity-content">
    <div>
      <h2 class="capacity-title"><?= e($settings['section10_headline'] ?? 'Jadwalkan Konsultasi Sebelum Pembangunan Dimulai.'); ?></h2>
      <p class="capacity-body"><?= e($settings['section10_body'] ?? 'Setiap proyek membutuhkan waktu untuk concept development, design review, technical detailing, dan koordinasi. Semakin awal Anda berkonsultasi, semakin banyak keputusan yang bisa direncanakan dengan matang.'); ?></p>
    </div>
    <div>
      <?php $waMsgJadwal = "Halo RBK Studio, saya ingin menjadwalkan konsultasi desain. Waktu yang saya inginkan: ____."; ?>
      <a href="<?= e(buildWaUrl($waNumber, $waMsgJadwal)); ?>" target="_blank" class="btn btn-primary" style="white-space: nowrap;" data-cta-code="JADWAL">
        <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
        Jadwalkan Konsultasi
      </a>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 11: LEAD CAPTURE WHATSAPP (TAHAP LEAD CAPTURE)
     ========================================================================== -->
<section class="theme-light section-padding" id="lead-capture-section">
  <div class="container">
    <div class="eyebrow" style="margin-bottom: 0.5rem; text-align: center; color: var(--rbk-orange);">MULAI DARI SINI</div>
    <h2 class="section-title" style="text-align: center; max-width: 900px; margin-left: auto; margin-right: auto;">
      <?= e($settings['section11_headline'] ?? 'Punya Tanah atau Rencana Membangun?'); ?>
    </h2>
    <p style="text-align: center; font-size: 1.125rem; color: #525252; max-width: 700px; margin: 0 auto 2.5rem auto;">
      <?= e($settings['section11_body'] ?? 'Tidak perlu sudah memiliki semua jawabannya. Tim RBK Studio akan membantu mengarahkan kebutuhan perencanaan proyek Anda.'); ?>
    </p>

    <div class="lead-form-box">
      <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;" class="font-serif">Checklist Brief Proyek Anda</h3>
      <p style="font-size: 0.875rem; color: #737373; margin-bottom: 1.5rem;">Isi formulir di bawah ini untuk menyusun pesan konsultasi terstruktur secara otomatis ke WhatsApp tim RBK Studio.</p>

      <form id="rbkBriefForm" data-wa="<?= e($waNumber); ?>">
        <div class="brief-grid">
          <div class="form-group">
            <label class="form-label" for="lead_location">Lokasi Proyek *</label>
            <input type="text" id="lead_location" class="form-input" placeholder="Contoh: Jakarta Selatan / Bogor Kota" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="lead_land_area">Luas Tanah (m²)</label>
            <input type="number" id="lead_land_area" class="form-input" placeholder="Contoh: 150">
          </div>

          <div class="form-group">
            <label class="form-label" for="lead_building_area">Rencana Luas Bangunan (m²)</label>
            <input type="number" id="lead_building_area" class="form-input" placeholder="Contoh: 200">
          </div>

          <div class="form-group">
            <label class="form-label" for="lead_floors">Jumlah Lantai</label>
            <input type="text" id="lead_floors" class="form-input" placeholder="Contoh: 1 / 2 / 3 lantai">
          </div>

          <div class="form-group">
            <label class="form-label" for="lead_type">Jenis Bangunan</label>
            <select id="lead_type" class="form-select">
              <option value="Rumah Tinggal">Rumah Tinggal</option>
              <option value="Ruko / Komersial">Ruko / Komersial</option>
              <option value="Renovasi">Renovasi</option>
              <option value="Villa">Villa / Custom</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label" for="lead_style">Style Desain yang Diinginkan</label>
            <select id="lead_style" class="form-select">
              <option value="Modern Minimalis">Modern Minimalis</option>
              <option value="Tropical Modern">Tropical Modern</option>
              <option value="Classic Modern">Classic Modern</option>
              <option value="Industrial / Contemporary">Industrial / Contemporary</option>
              <option value="Belum Tahu / Butuh Saran">Belum Tahu / Butuh Saran</option>
            </select>
          </div>

          <div class="form-group full-width">
            <label class="form-label" for="lead_budget">Estimasi Budget Pembangunan / Desain</label>
            <input type="text" id="lead_budget" class="form-input" placeholder="Contoh: Rp 500 Juta - 1 Miliyar / Sesuai Rekomendasi">
          </div>
        </div>

        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-top: 1rem;">
          <div style="font-size: 0.875rem; font-weight: 700; color: #FFFFFF;">
            Mulai dari Rp60.000/m² • Free Konsultasi Awal
          </div>
          <div style="font-size: 0.9375rem; color: #737373;">
            Coverage: Jakarta • Bogor • Depok • Tangerang • Bekasi
          </div>
        </div>

        <button type="submit" class="btn btn-primary big-wa-btn" data-cta-code="LENGKAP">
          <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
          Konsultasi Proyek via WhatsApp
        </button>
      </form>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 12: FINAL CLOSING (TAHAP CLOSING)
     ========================================================================== -->
<section class="final-closing" id="closing">
  <div class="container" style="text-align: center;">
    <h2 class="final-title font-serif" style="text-align: center; max-width: 900px; margin: 0 auto 1.5rem auto;">
      Anda Hanya Perlu Membangunnya Sekali. Pastikan Semuanya Direncanakan dengan Tepat.
    </h2>

    <div style="font-weight: 700; font-size: 1.125rem; letter-spacing: 0.15em; margin-bottom: 0.5rem;">RBK STUDIO</div>
    <div style="font-size: 0.9375rem; color: #8E8E8E; letter-spacing: 0.1em; margin-bottom: 2rem; text-transform: uppercase;">Architecture • Interior • Construction Planning</div>

    <div class="price-summary-line" style="color: #FFFFFF; font-weight: 600;">
      Basic Rp60.000/m² &nbsp;•&nbsp; Standard Rp80.000/m² &nbsp;•&nbsp; Premium Rp150.000/m²
    </div>

    <div style="font-family: var(--font-serif); font-style: italic; font-size: 1.5rem; color: var(--rbk-orange); margin-bottom: 2.5rem;">
      "<?= e($settings['brand_line_section_12'] ?? 'Your Vision. Professionally Planned. Beautifully Designed.'); ?>"
    </div>

    <a href="<?= e(buildWaUrl($waNumber, $waMsgUmum)); ?>" target="_blank" class="btn btn-primary" style="padding: 1.25rem 2.5rem;" data-cta-code="UMUM">
      <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
      Start Your Project &rarr;
    </a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
