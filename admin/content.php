<?php
/**
 * Admin Copy & Site Settings Editor - RBK Studio
 */
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
checkAuth();

$db = getDB();
$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settingsKeys = [
        'wa_number', 'site_title', 'meta_description',
        'hero_eyebrow', 'hero_headline', 'hero_supporting', 'hero_price_cue', 'hero_trust_strip',
        'section02_problem_headline', 'section02_problem_subheadline', 'section02_bridge', 'section02_transition', 'section02_solution_headline', 'section02_solution_subheadline', 'section02_solution_body', 'section02_solution_closing',
        'section03_headline', 'section03_subheadline', 'section03_body',
        'brand_line_section_03', 'brand_line_section_07', 'brand_line_section_12',
        'section07_headline', 'section07_body', 'section07_closing',
        'section10_headline', 'section10_body',
        'section11_headline', 'section11_body',
        'section12_headline', 'section12_subheadline',
        'company_address', 'company_email', 'company_instagram', 'company_youtube'
    ];

    $stmt = $db->prepare("INSERT OR REPLACE INTO settings (setting_key, setting_value) VALUES (?, ?)");

    foreach ($settingsKeys as $key) {
        if (isset($_POST[$key])) {
            $stmt->execute([$key, trim($_POST[$key])]);
        }
    }

    setFlash('success', 'Pengaturan & copy landing page berhasil diperbarui!');
    header('Location: /admin/content.php');
    exit;
}

$settings = getSettings();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaturan Copy & Kontak | RBK Studio CMS</title>
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
  <?php include __DIR__ . '/sidebar.php'; ?>

  <div class="admin-main">
    <div class="admin-topbar">
      <div>Edit Copy Landing Page & Kontak WhatsApp</div>
    </div>

    <div class="admin-content">
      <?php if ($flash): ?>
        <div class="alert-flash <?= e($flash['type']); ?>"><?= e($flash['message']); ?></div>
      <?php endif; ?>

      <form method="POST">
        <!-- 1. WhatsApp & General Settings -->
        <div class="card">
          <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.25rem;">1. WhatsApp & SEO Metadata</h2>

          <div class="form-grid">
            <div class="form-group-admin">
              <label class="form-label-admin">Nomor WhatsApp Tujuan Lead *</label>
              <input type="text" name="wa_number" class="form-control-admin" required value="<?= e($settings['wa_number'] ?? '081234500441'); ?>" placeholder="081234500441">
              <span style="font-size: 0.75rem; color: #6B7280;">Semua tombol CTA WhatsApp akan mengarah ke nomor ini.</span>
            </div>

            <div class="form-group-admin">
              <label class="form-label-admin">SEO Title (Tag Judul Website) *</label>
              <input type="text" name="site_title" class="form-control-admin" required value="<?= e($settings['site_title'] ?? ''); ?>">
            </div>

            <div class="form-group-admin full">
              <label class="form-label-admin">SEO Meta Description *</label>
              <textarea name="meta_description" class="form-control-admin" rows="2" required><?= e($settings['meta_description'] ?? ''); ?></textarea>
            </div>
          </div>
        </div>

        <!-- 2. Section 01: Hero Copy -->
        <div class="card">
          <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.25rem;">2. Section 01: Hero</h2>

          <div class="form-grid">
            <div class="form-group-admin">
              <label class="form-label-admin">Eyebrow Hero</label>
              <input type="text" name="hero_eyebrow" class="form-control-admin" value="<?= e($settings['hero_eyebrow'] ?? ''); ?>">
            </div>

            <div class="form-group-admin">
              <label class="form-label-admin">Price Cue Hero</label>
              <input type="text" name="hero_price_cue" class="form-control-admin" value="<?= e($settings['hero_price_cue'] ?? ''); ?>">
            </div>

            <div class="form-group-admin full">
              <label class="form-label-admin">Headline Hero</label>
              <input type="text" name="hero_headline" class="form-control-admin" value="<?= e($settings['hero_headline'] ?? ''); ?>">
            </div>

            <div class="form-group-admin full">
              <label class="form-label-admin">Supporting Copy Hero</label>
              <textarea name="hero_supporting" class="form-control-admin" rows="3"><?= e($settings['hero_supporting'] ?? ''); ?></textarea>
            </div>
          </div>
        </div>

        <!-- 3. Brand Lines -->
        <div class="card">
          <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.25rem;">3. Signature Brand Lines</h2>

          <div class="form-group-admin">
            <label class="form-label-admin">Brand Line Section 03 (Core Value)</label>
            <input type="text" name="brand_line_section_03" class="form-control-admin" value="<?= e($settings['brand_line_section_03'] ?? ''); ?>">
          </div>

          <div class="form-group-admin">
            <label class="form-label-admin">Brand Line Section 07 (Hard-Sell)</label>
            <input type="text" name="brand_line_section_07" class="form-control-admin" value="<?= e($settings['brand_line_section_07'] ?? ''); ?>">
          </div>

          <div class="form-group-admin">
            <label class="form-label-admin">Brand Line Section 12 (Final Tagline)</label>
            <input type="text" name="brand_line_section_12" class="form-control-admin" value="<?= e($settings['brand_line_section_12'] ?? ''); ?>">
          </div>
        </div>

        <!-- 4. Section 07 & Section 10 Copy -->
        <div class="card">
          <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.25rem;">4. Copy Hard-Sell & Jadwal Konsultasi</h2>

          <div class="form-group-admin">
            <label class="form-label-admin">Section 07 Headline (Hard-Sell)</label>
            <input type="text" name="section07_headline" class="form-control-admin" value="<?= e($settings['section07_headline'] ?? ''); ?>">
          </div>

          <div class="form-group-admin">
            <label class="form-label-admin">Section 07 Body Text</label>
            <textarea name="section07_body" class="form-control-admin" rows="3"><?= e($settings['section07_body'] ?? ''); ?></textarea>
          </div>

          <div class="form-group-admin">
            <label class="form-label-admin">Section 10 Headline (Jadwal Konsultasi)</label>
            <input type="text" name="section10_headline" class="form-control-admin" value="<?= e($settings['section10_headline'] ?? ''); ?>">
          </div>

          <div class="form-group-admin">
            <label class="form-label-admin">Section 10 Body Text</label>
            <textarea name="section10_body" class="form-control-admin" rows="2"><?= e($settings['section10_body'] ?? ''); ?></textarea>
          </div>
        </div>

        <!-- 5. Company Info -->
        <div class="card">
          <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.25rem;">5. Data Perusahaan & Footer</h2>

          <div class="form-grid">
            <div class="form-group-admin">
              <label class="form-label-admin">Alamat Perusahaan</label>
              <input type="text" name="company_address" class="form-control-admin" value="<?= e($settings['company_address'] ?? ''); ?>">
            </div>

            <div class="form-group-admin">
              <label class="form-label-admin">Email Perusahaan</label>
              <input type="email" name="company_email" class="form-control-admin" value="<?= e($settings['company_email'] ?? ''); ?>">
            </div>

            <div class="form-group-admin">
              <label class="form-label-admin">Instagram Handle</label>
              <input type="text" name="company_instagram" class="form-control-admin" value="<?= e($settings['company_instagram'] ?? ''); ?>">
            </div>

            <div class="form-group-admin">
              <label class="form-label-admin">YouTube Channel</label>
              <input type="text" name="company_youtube" class="form-control-admin" value="<?= e($settings['company_youtube'] ?? ''); ?>">
            </div>
          </div>
        </div>

        <div style="margin-bottom: 3rem;">
          <button type="submit" class="btn-admin btn-admin-primary" style="padding: 1rem 2rem; font-size: 1rem;">
            Simpan Semua Pengaturan Copy & WA
          </button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
