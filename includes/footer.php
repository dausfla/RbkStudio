<?php
/**
 * Footer Component for RBK Studio
 */
$waNumber = $settings['wa_number'] ?? '081234500441';
$waMsgMobile = "Halo RBK Studio, saya ingin konsultasi desain untuk proyek saya. Lokasi proyek: ____. Jenis bangunan: ____.";
$waUrlMobile = buildWaUrl($waNumber, $waMsgMobile);
?>

  <!-- Site Footer -->
  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <div>
          <div class="footer-brand-title">RBK STUDIO</div>
          <p style="margin-bottom: 1rem; color: #A3A3A3; font-size: 0.875rem;">
            Rancang Bangun Kreasi • PT Rancang Bangun Sedaya<br>
            <?= e($settings['company_address'] ?? 'Komp. BPPB Pasirmulya Blok Q No. 1, Bogor'); ?>
          </p>
          <p style="color: #8E8E8E; font-size: 0.8125rem;">
            Jasa Perencanaan Arsitektur, Gambar Kerja, Struktur & MEP, RAB, dan Visualisasi 3D.
          </p>
        </div>

        <div>
          <div style="color: var(--rbk-white); font-weight: 700; margin-bottom: 1rem; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;">Navigasi</div>
          <a href="#portfolio" class="footer-link">Portfolio</a>
          <a href="#layanan" class="footer-link">Layanan & Value</a>
          <a href="#paket" class="footer-link">Paket & Investasi</a>
          <a href="#proses" class="footer-link">Tahap Kerja</a>
          <a href="#faq" class="footer-link">FAQ</a>
          <div style="margin-top: 1.25rem;">
            <a href="/admin/index.php" class="btn btn-outline" style="padding: 0.4rem 0.85rem; font-size: 0.75rem; border-color: rgba(255,255,255,0.2); color: #D4D4D4; border-radius: 2px;">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 0.35rem;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              Login Admin CMS
            </a>
          </div>
        </div>

        <div>
          <div style="color: var(--rbk-white); font-weight: 700; margin-bottom: 1rem; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em;">Kontak & Social</div>
          <a href="<?= e(buildWaUrl($waNumber, 'Halo RBK Studio')); ?>" target="_blank" class="footer-link">WhatsApp: <?= e($waNumber); ?></a>
          <a href="mailto:<?= e($settings['company_email'] ?? 'official@rancangbangunkreasi.id'); ?>" class="footer-link"><?= e($settings['company_email'] ?? 'official@rancangbangunkreasi.id'); ?></a>
          <a href="https://instagram.com/rbkofficial.id" target="_blank" class="footer-link">Instagram: <?= e($settings['company_instagram'] ?? '@rbkofficial.id'); ?></a>
          <a href="#" class="footer-link">YouTube: <?= e($settings['company_youtube'] ?? 'Rancang Bangun Kreasi'); ?></a>
          <div style="margin-top: 1rem; font-size: 0.75rem; color: #737373;">
            Coverage: Jakarta • Bogor • Depok • Tangerang • Bekasi
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; <?= date('Y'); ?> RBK Studio (PT Rancang Bangun Sedaya). All Rights Reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Scroll-Triggered Promotional Pop-up Modal -->
  <div id="promoModal" class="promo-modal-overlay">
    <div class="promo-modal-container">
      <button class="promo-modal-close" id="promoModalClose" aria-label="Tutup Promo">&times;</button>
      <div class="promo-modal-body">
        <a href="<?= e(buildWaUrl($waNumber, 'Halo RBK Studio, saya berminat dengan promo Paket Perencanaan Rancang Bangun Kreasi.')); ?>" target="_blank" data-cta-code="PROMO_POPUP" class="promo-img-link">
          <img src="/assets/images/promo_poster.png" alt="Paket Perencanaan RBK Studio Promo" class="promo-poster-img">
        </a>
        <div class="promo-modal-footer">
          <a href="<?= e(buildWaUrl($waNumber, 'Halo RBK Studio, saya berminat dengan promo Paket Perencanaan Rancang Bangun Kreasi.')); ?>" target="_blank" data-cta-code="PROMO_POPUP" class="btn btn-primary promo-cta-btn">
            <svg class="wa-icon" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.705 1.754zm6.097-4.901l.374.222c1.464.869 3.146 1.328 4.863 1.329 5.26 0 9.54-4.28 9.543-9.544.001-2.548-.991-4.943-2.793-6.746-1.801-1.802-4.195-2.794-6.744-2.795-5.263 0-9.544 4.28-9.546 9.545-.001 1.78.47 3.515 1.365 5.064l.244.423-1.002 3.662 3.696-.969z"/></svg>
            Konsultasi & Ambil Promo
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Sticky Mobile Bar (Disappear when Section 11 is in viewport) -->
  <div class="sticky-mobile-bar" id="stickyMobileBar">
    <a href="<?= e($waUrlMobile); ?>" target="_blank" class="btn btn-primary" style="width: 100%;" data-cta-code="UMUM">
      <svg class="wa-icon" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.705 1.754zm6.097-4.901l.374.222c1.464.869 3.146 1.328 4.863 1.329 5.26 0 9.54-4.28 9.543-9.544.001-2.548-.991-4.943-2.793-6.746-1.801-1.802-4.195-2.794-6.744-2.795-5.263 0-9.544 4.28-9.546 9.545-.001 1.78.47 3.515 1.365 5.064l.244.423-1.002 3.662 3.696-.969z"/></svg>
      Konsultasi Proyek
    </a>
  </div>

  <!-- JavaScript File -->
  <script src="/assets/js/main.js"></script>
</body>
</html>
