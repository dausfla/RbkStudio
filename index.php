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
    <div class="eyebrow"><?= e($settings['hero_eyebrow'] ?? 'ARCHITECTURE • INTERIOR • PLANNING'); ?></div>
    <h1 class="hero-title"><?= e($settings['hero_headline'] ?? 'Bangun Sekali. Rencanakan dengan Benar Sejak Awal.'); ?></h1>
    <p class="hero-body"><?= e($settings['hero_supporting'] ?? 'Rumah, ruko, dan bangunan bernilai tinggi tidak seharusnya dimulai dari gambar seadanya. RBK Studio membantu Anda merencanakan bangunan secara menyeluruh, mulai dari konsep arsitektur, gambar kerja, struktur & MEP, RAB, hingga visualisasi 3D.'); ?></p>
    
    <div class="hero-price-cue"><?= e($settings['hero_price_cue'] ?? 'Mulai dari Rp60.000/m²'); ?></div>
    
    <div class="hero-cta-group">
      <?php 
        $waMsgUmum = "Halo RBK Studio, saya ingin konsultasi desain untuk proyek saya. Lokasi proyek: ____. Jenis bangunan: ____.";
      ?>
      <a href="<?= e(buildWaUrl($waNumber, $waMsgUmum)); ?>" target="_blank" class="btn btn-primary" data-cta-code="UMUM">
        <svg class="wa-icon" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.705 1.754zm6.097-4.901l.374.222c1.464.869 3.146 1.328 4.863 1.329 5.26 0 9.54-4.28 9.543-9.544.001-2.548-.991-4.943-2.793-6.746-1.801-1.802-4.195-2.794-6.744-2.795-5.263 0-9.544 4.28-9.546 9.545-.001 1.78.47 3.515 1.365 5.064l.244.423-1.002 3.662 3.696-.969z"/></svg>
        Konsultasikan Proyek Anda
      </a>
      <a href="#portfolio" class="btn btn-outline">Lihat Portfolio RBK</a>
    </div>
    
    <p class="hero-microcopy">Konsultasikan kebutuhan, luas bangunan, style, dan estimasi scope proyek Anda bersama tim RBK Studio.</p>

    <div class="trust-strip">
      <span>Jakarta</span>
      <span>Bogor</span>
      <span>Depok</span>
      <span>Tangerang</span>
      <span>Bekasi</span>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 02: PROBLEM -> SOLUTION (TAHAP PROBLEM -> DESIRE)
     ========================================================================== -->
<section class="problem-part section-padding" id="problem">
  <div class="container">
    <div class="eyebrow">TAHAP PROBLEM</div>
    <h2 class="section-title text-white"><?= e($settings['section02_problem_headline'] ?? 'Biaya Konstruksi Bisa Mencapai Ratusan Juta hingga Miliaran.'); ?></h2>
    <p class="section-subtitle"><?= e($settings['section02_problem_subheadline'] ?? 'Jangan biarkan keputusan desain yang salah membuat Anda membayar dua kali.'); ?></p>

    <div class="risk-grid">
      <div class="risk-item">Layout yang tidak nyaman</div>
      <div class="risk-item">Struktur yang tidak terencana</div>
      <div class="risk-item">Ruang yang tidak optimal</div>
      <div class="risk-item">Instalasi listrik & plumbing yang berantakan</div>
      <div class="risk-item">Perubahan saat konstruksi</div>
      <div class="risk-item">Pembengkakan budget pembangunan</div>
      <div class="risk-item">Pekerjaan bongkar ulang</div>
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
    <div class="eyebrow">TAHAP DESIRE & SOLUSI</div>
    <h2 class="section-title"><?= e($settings['section02_solution_headline'] ?? 'Kami Tidak Hanya Mendesain Bangunan.'); ?></h2>
    <p class="section-subtitle"><?= e($settings['section02_solution_subheadline'] ?? 'Kami Merencanakan Bagaimana Anda Akan Hidup di Dalamnya.'); ?></p>
    
    <p class="solution-body"><?= e($settings['section02_solution_body'] ?? 'Setiap ruang memiliki fungsi. Setiap ukuran memiliki alasan. Setiap material memiliki pertimbangan. Setiap detail direncanakan agar bangunan Anda memiliki keseimbangan antara estetika, fungsi, kenyamanan, efisiensi, dan nilai.'); ?></p>
    
    <p class="solution-closing"><?= e($settings['section02_solution_closing'] ?? 'RBK Studio merancang bangunan yang pantas dibangun, bukan hanya menarik untuk dilihat.'); ?></p>

    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
      <?php 
        $waMsgSebelum = "Halo RBK Studio, saya sedang merencanakan pembangunan dan ingin konsultasi sebelum konstruksi dimulai. Lokasi proyek: ____.";
      ?>
      <a href="<?= e(buildWaUrl($waNumber, $waMsgSebelum)); ?>" target="_blank" class="btn btn-primary" data-cta-code="SEBELUM-BANGUN">
        <svg class="wa-icon" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.705 1.754zm6.097-4.901l.374.222c1.464.869 3.146 1.328 4.863 1.329 5.26 0 9.54-4.28 9.543-9.544.001-2.548-.991-4.943-2.793-6.746-1.801-1.802-4.195-2.794-6.744-2.795-5.263 0-9.544 4.28-9.546 9.545-.001 1.78.47 3.515 1.365 5.064l.244.423-1.002 3.662 3.696-.969z"/></svg>
        Konsultasi dengan RBK Studio
      </a>
      <a href="#portfolio" class="btn btn-outline">Lihat Portfolio</a>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 03: CORE VALUE & LUXURY POSITIONING (TAHAP VALUE)
     ========================================================================== -->
<section class="theme-white section-padding" id="layanan">
  <div class="container">
    <div class="eyebrow">TAHAP VALUE</div>
    <h2 class="section-title"><?= e($settings['section03_headline'] ?? 'Luxury Is Not About Making Everything Expensive.'); ?></h2>
    <p class="section-subtitle"><?= e($settings['section03_subheadline'] ?? 'Luxury Is About Making Every Decision Feel Intentional.'); ?></p>
    
    <p style="font-size: 1.0625rem; color: #525252; max-width: 760px; margin-bottom: 2.5rem; line-height: 1.7;">
      <?= e($settings['section03_body'] ?? 'Bangunan premium bukan bangunan yang dipenuhi material mahal. Bangunan premium adalah bangunan yang proporsional, memiliki flow ruang yang baik, detailnya konsisten, materialnya tepat, dan setiap elemen terasa direncanakan.'); ?>
    </p>

    <div class="values-grid">
      <div class="value-card">
        <div class="value-num">01</div>
        <div class="value-title">Konsep Arsitektur</div>
        <div class="value-desc">Menerjemahkan kebutuhan, gaya hidup, fungsi, kondisi lahan, serta karakter yang Anda inginkan menjadi konsep desain yang jelas.</div>
      </div>
      <div class="value-card">
        <div class="value-num">02</div>
        <div class="value-title">Space Planning</div>
        <div class="value-desc">Setiap meter persegi direncanakan agar ruang terasa lebih proporsional, efisien, dan nyaman digunakan.</div>
      </div>
      <div class="value-card">
        <div class="value-num">03</div>
        <div class="value-title">Visualisasi 3D</div>
        <div class="value-desc">Lihat bentuk, atmosfer, dan karakter bangunan sebelum pembangunan dimulai.</div>
      </div>
      <div class="value-card">
        <div class="value-num">04</div>
        <div class="value-title">Gambar Kerja</div>
        <div class="value-desc">Memberikan panduan teknis yang lebih jelas untuk pelaksanaan konstruksi di lapangan.</div>
      </div>
      <div class="value-card">
        <div class="value-num">05</div>
        <div class="value-title">Struktur & MEP</div>
        <div class="value-desc">Perencanaan bangunan tidak berhenti pada fasad. Struktur, listrik, plumbing, dan kebutuhan teknis lainnya ikut dipertimbangkan.</div>
      </div>
      <div class="value-card">
        <div class="value-num">06</div>
        <div class="value-title">RAB</div>
        <div class="value-desc">Membantu Anda memahami gambaran kebutuhan biaya pembangunan secara lebih terukur.</div>
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
    <div class="eyebrow">TAHAP PROOF</div>
    <h2 class="section-title">Bukan Sekadar Render.</h2>
    <p class="section-subtitle">Setiap desain dimulai dari masalah yang harus diselesaikan.</p>

    <!-- Portfolio Category Tabs -->
    <div class="portfolio-tabs">
      <button class="tab-btn active" data-filter="all">Semua Project</button>
      <button class="tab-btn" data-filter="Residential">Residential</button>
      <button class="tab-btn" data-filter="Commercial">Commercial</button>
      <button class="tab-btn" data-filter="Ruko">Ruko</button>
      <button class="tab-btn" data-filter="Interior">Interior</button>
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
            
            <?php if (!empty($item['challenge']) || !empty($item['solution'])): ?>
              <div class="portfolio-story">
                <?php if (!empty($item['challenge'])): ?>
                  <p><span class="story-label">Tantangan:</span> <?= e($item['challenge']); ?></p>
                <?php endif; ?>
                <?php if (!empty($item['solution'])): ?>
                  <p style="margin-top: 0.35rem;"><span class="story-label">Solusi:</span> <?= e($item['solution']); ?></p>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
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
        Explore Our Projects &rarr;
      </a>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 05: WHY RBK STUDIO (TAHAP TRUST)
     ========================================================================== -->
<section class="theme-white section-padding" id="tentang">
  <div class="container">
    <div class="eyebrow">TAHAP TRUST</div>
    <h2 class="section-title">Why RBK Studio</h2>
    <p class="section-subtitle">Mengubah capability menjadi alasan kuat untuk memilih partner perencanaan terbaik.</p>

    <div class="why-grid">
      <div class="why-item">
        <div class="why-title">Design With Purpose</div>
        <div class="why-desc">Setiap keputusan desain harus mempunyai fungsi dan pertimbangan teknis yang matang, bukan sekadar ornamen estetika.</div>
      </div>

      <div class="why-item">
        <div class="why-title">Integrated Planning</div>
        <div class="why-desc">Arsitektur, gambar kerja, struktur, MEP, dan estimasi biaya (RAB) direncanakan secara terpadu dalam satu alur terstruktur.</div>
      </div>

      <div class="why-item">
        <div class="why-title">Personalized Design</div>
        <div class="why-desc">Bukan desain template. Setiap karya dikembangkan khusus berdasarkan karakter, kebutuhan proyek, dan lahan Anda.</div>
      </div>

      <div class="why-item">
        <div class="why-title">Cost Awareness</div>
        <div class="why-desc">Desain selalu mempertimbangkan realitas anggaran pembangunan sehingga keputusan dapat diambil secara rasional dan terukur.</div>
      </div>

      <div class="why-item">
        <div class="why-title">Buildable Design</div>
        <div class="why-desc">Tujuan akhir bukan hanya mendapatkan gambar indah, melainkan menghasilkan dokumen teknis yang siap dan mudah direalisasikan tukang di lapangan.</div>
      </div>

      <div class="why-item">
        <div class="why-title">Jabodetabek Coverage</div>
        <div class="why-desc">Layanan profesional terjangkau untuk wilayah Jakarta, Bogor, Depok, Tangerang, dan Bekasi dengan standar pengawasan tinggi.</div>
      </div>
    </div>

    <div style="margin-top: 3.5rem; text-align: center;">
      <a href="<?= e(buildWaUrl($waNumber, $waMsgUmum)); ?>" target="_blank" class="btn btn-primary" data-cta-code="UMUM">
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
    <div class="eyebrow">TAHAP PRICING</div>
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
            <div style="font-size: 0.8125rem; color: #A3A3A3; margin-bottom: 0.5rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Pilih Paket Desain:</div>
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
              <button type="button" class="calc-pkg-btn" data-price="60000" data-name="Basic" data-code="BASIC">Basic (60rb/m²)</button>
              <button type="button" class="calc-pkg-btn active" data-price="80000" data-name="Standard" data-code="STANDARD">Standard (80rb/m²)</button>
              <button type="button" class="calc-pkg-btn" data-price="150000" data-name="Premium" data-code="PREMIUM">Premium (150rb/m²)</button>
            </div>
          </div>
        </div>

        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); padding: 2rem; border-radius: 6px; text-align: center;">
          <div style="font-size: 0.8125rem; color: #A3A3A3; text-transform: uppercase; letter-spacing: 0.1em;">Estimasi Biaya Desain:</div>
          <div class="result-price-big" id="calcTotalCost">Rp12.000.000</div>
          
          <div style="font-size: 0.8125rem; color: #D4D4D4; margin-bottom: 1.5rem;">
            Perkiraan Waktu: <strong id="calcDuration" style="color: #FFF;">14-28 Hari Kerja</strong>
          </div>

          <a href="#" id="calcWaBtn" target="_blank" class="btn btn-primary" style="width: 100%; justify-content: center;" data-wa="<?= e($waNumber); ?>" data-cta-code="STANDARD">
            <svg class="wa-icon" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.705 1.754zm6.097-4.901l.374.222c1.464.869 3.146 1.328 4.863 1.329 5.26 0 9.54-4.28 9.543-9.544.001-2.548-.991-4.943-2.793-6.746-1.801-1.802-4.195-2.794-6.744-2.795-5.263 0-9.544 4.28-9.546 9.545-.001 1.78.47 3.515 1.365 5.064l.244.423-1.002 3.662 3.696-.969z"/></svg>
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
              <?= e($pkg['cta_text']); ?>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <p style="text-align: center; font-size: 0.8125rem; color: #737373; margin-top: 1.5rem;">
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
        Minta Rekomendasi Paket
      </a>
    </div>
  </div>
</section>

<!-- ==========================================================================
     SECTION 07: HARD-SELL VALUE (TAHAP VALUE / RISK)
     ========================================================================== -->
<section class="hardsell-band" id="hardsell">
  <div class="container">
    <div class="eyebrow">TAHAP HARD-SELL VALUE</div>
    <h2 class="hardsell-headline"><?= e($settings['section07_headline'] ?? 'Jangan Menghemat pada Bagian yang Menentukan Seluruh Pembangunan.'); ?></h2>
    
    <p class="hardsell-body"><?= e($settings['section07_body'] ?? 'Salah menentukan cat masih bisa diganti. Salah memilih furniture masih bisa diperbaiki. Tetapi ketika struktur sudah dibangun, dinding sudah berdiri, instalasi sudah tertanam, dan pekerjaan konstruksi sudah berjalan, perubahan menjadi jauh lebih mahal.'); ?></p>
    
    <p class="hardsell-closing"><?= e($settings['section07_closing'] ?? 'Karena itu, keputusan terbaik dilakukan sebelum tukang mulai bekerja.'); ?></p>

    <div class="brand-signature">"<?= e($settings['brand_line_section_07'] ?? 'Plan First. Build Once.'); ?>" &mdash; RBK STUDIO</div>

    <a href="<?= e(buildWaUrl($waNumber, $waMsgSebelum)); ?>" target="_blank" class="btn btn-primary" data-cta-code="SEBELUM-BANGUN">
      Konsultasikan Sebelum Membangun
    </a>
  </div>
</section>

<!-- ==========================================================================
     SECTION 08: PROCESS (TAHAP PROCESS)
     ========================================================================== -->
<section class="theme-white section-padding" id="proses">
  <div class="container">
    <div class="eyebrow">TAHAP PROCESS</div>
    <h2 class="section-title">Dari Ide hingga Siap Dibangun</h2>
    <p class="section-subtitle">Alur kerja transparan, terstruktur, dan aman dari tahap konsultasi hingga penyerahan berkas.</p>

    <div class="process-timeline">
      <div class="process-step">
        <div class="step-num">01</div>
        <div class="step-title">Consultation</div>
        <div class="step-desc">Memahami kebutuhan, lokasi, ukuran, fungsi, style, dan target budget proyek.</div>
      </div>

      <div class="process-step">
        <div class="step-num">02</div>
        <div class="step-title">Concept Development</div>
        <div class="step-desc">Konsep, layout, dan karakter arsitektur mulai dikembangkan.</div>
      </div>

      <div class="process-step">
        <div class="step-num">03</div>
        <div class="step-title">Design Development</div>
        <div class="step-desc">Proporsi, material, ruang, dan detail desain dimatangkan.</div>
      </div>

      <div class="process-step">
        <div class="step-num">04</div>
        <div class="step-title">Technical Planning</div>
        <div class="step-desc">Gambar teknis, struktur, MEP, dan kebutuhan perencanaan lainnya disusun sesuai scope.</div>
      </div>

      <div class="process-step">
        <div class="step-num">05</div>
        <div class="step-title">Budget Planning</div>
        <div class="step-desc">Perencanaan RAB membantu memberikan gambaran kebutuhan biaya.</div>
      </div>

      <div class="process-step">
        <div class="step-num">06</div>
        <div class="step-title">Final Delivery</div>
        <div class="step-desc">Dokumen desain disiapkan untuk menjadi dasar realisasi pembangunan.</div>
      </div>
    </div>

    <p style="text-align: center; font-size: 0.8125rem; color: #737373; margin-top: 3rem;">
      Pembayaran dilakukan bertahap mengikuti persetujuan setiap tahap desain. Detail ada di FAQ.
    </p>

    <div style="text-align: center; margin-top: 2rem;">
      <a href="<?= e(buildWaUrl($waNumber, $waMsgUmum)); ?>" target="_blank" class="btn btn-primary" data-cta-code="UMUM">
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
    <div class="eyebrow">TAHAP SEGMENT</div>
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
    <div class="eyebrow" style="text-align: center; display: block;">BLOK FAQ</div>
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
    <div class="eyebrow" style="text-align: center; display: block;">LEAD CAPTURE FORM</div>
    <h2 class="section-title" style="text-align: center; max-width: 900px; margin-left: auto; margin-right: auto;">
      <?= e($settings['section11_headline'] ?? 'Punya Tanah? Sudah Punya Ide? Atau Masih Bingung Harus Mulai dari Mana?'); ?>
    </h2>
    <p style="text-align: center; font-size: 1.125rem; color: #525252; max-width: 700px; margin: 0 auto 2.5rem auto;">
      <?= e($settings['section11_body'] ?? 'Mulai dengan konsultasi bersama RBK Studio. Ceritakan proyek Anda kepada tim kami.'); ?>
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
          <div style="font-size: 0.875rem; font-weight: 700; color: var(--rbk-orange);">
            Mulai dari Rp60.000/m² • Free Konsultasi Awal
          </div>
          <div style="font-size: 0.8125rem; color: #737373;">
            Coverage: Jakarta • Bogor • Depok • Tangerang • Bekasi
          </div>
        </div>

        <button type="submit" class="btn btn-primary big-wa-btn" data-cta-code="LENGKAP">
          <svg class="wa-icon" viewBox="0 0 24 24" style="width: 22px; height: 22px;"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.705 1.754zm6.097-4.901l.374.222c1.464.869 3.146 1.328 4.863 1.329 5.26 0 9.54-4.28 9.543-9.544.001-2.548-.991-4.943-2.793-6.746-1.801-1.802-4.195-2.794-6.744-2.795-5.263 0-9.544 4.28-9.546 9.545-.001 1.78.47 3.515 1.365 5.064l.244.423-1.002 3.662 3.696-.969z"/></svg>
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
  <div class="container">
    <h2 class="final-title font-serif"><?= e($settings['section12_headline'] ?? 'Anda Hanya Perlu Membangunnya Sekali.'); ?></h2>
    <p class="final-subtitle"><?= e($settings['section12_subheadline'] ?? 'Pastikan Semuanya Direncanakan dengan Tepat.'); ?></p>

    <div style="font-weight: 700; font-size: 1.125rem; letter-spacing: 0.15em; margin-bottom: 0.5rem;">RBK STUDIO</div>
    <div style="font-size: 0.8125rem; color: #8E8E8E; letter-spacing: 0.1em; margin-bottom: 2rem; text-transform: uppercase;">Architecture • Interior • Construction Planning</div>

    <div class="price-summary-line">
      Basic Rp60.000/m² &nbsp;•&nbsp; Standard Rp80.000/m² &nbsp;•&nbsp; Premium Rp150.000/m²
    </div>

    <div style="font-family: var(--font-serif); font-style: italic; font-size: 1.5rem; color: var(--rbk-orange); margin-bottom: 2.5rem;">
      "<?= e($settings['brand_line_section_12'] ?? 'Your Vision. Professionally Planned. Beautifully Designed.'); ?>"
    </div>

    <a href="<?= e(buildWaUrl($waNumber, $waMsgUmum)); ?>" target="_blank" class="btn btn-primary" style="padding: 1.25rem 2.5rem;" data-cta-code="UMUM">
      Start Your Project &rarr;
    </a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
