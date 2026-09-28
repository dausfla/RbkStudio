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
            <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
            Konsultasi & Ambil Promo
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Sticky Mobile Bar (Disappear when Section 11 is in viewport) -->
  <div class="sticky-mobile-bar" id="stickyMobileBar">
    <a href="<?= e($waUrlMobile); ?>" target="_blank" class="btn btn-primary" style="width: 100%;" data-cta-code="UMUM">
      <svg class="header-wa-icon" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.485 1.332 5.001L2 22l5.127-1.338c1.464.795 3.111 1.213 4.88 1.214h.005c5.503 0 9.988-4.478 9.989-9.984 0-2.668-1.037-5.176-2.922-7.062A9.925 9.925 0 0 0 12.012 2zm5.827 14.19c-.244.688-1.42 1.314-1.95 1.397-.492.077-1.129.11-1.815-.109-.415-.132-.951-.309-1.642-.607-2.906-1.258-4.799-4.2-4.945-4.394-.146-.195-1.189-1.58-1.189-3.013 0-1.433.748-2.138 1.014-2.428.266-.29.58-.363.774-.363.194 0 .387.001.555.009.178.008.416-.068.65.493.244.58.826 2.013.899 2.158.073.146.121.315.024.507-.097.192-.145.312-.29.484-.145.172-.305.385-.436.517-.145.146-.297.305-.128.595.169.29.749 1.237 1.607 2.001 1.103.982 2.033 1.287 2.324 1.432.29.145.46.121.63-.073.17-.194.726-.846.919-1.137.193-.29.387-.242.652-.145.265.097 1.688.796 1.979.941.29.145.483.218.555.339.073.121.073.702-.171 1.39z"/></svg>
      Konsultasi Proyek
    </a>
  </div>

  <!-- JavaScript File -->
  <script src="/assets/js/main.js"></script>
</body>
</html>
