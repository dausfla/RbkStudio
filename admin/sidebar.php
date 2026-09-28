<?php
/**
 * Admin Sidebar Template
 */
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="admin-sidebar">
  <div class="sidebar-header">
    <span class="sidebar-badge">RBK</span>
    <span>CMS DASHBOARD</span>
  </div>

  <nav class="sidebar-nav">
    <a href="/admin/dashboard.php" class="sidebar-link <?= $currentPage == 'dashboard.php' ? 'active' : ''; ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      Overview
    </a>

    <a href="/admin/portfolio.php" class="sidebar-link <?= $currentPage == 'portfolio.php' ? 'active' : ''; ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
      Portfolio (CRUD)
    </a>

    <a href="/admin/videos.php" class="sidebar-link <?= $currentPage == 'videos.php' ? 'active' : ''; ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
      Video Portfolio (YouTube)
    </a>

    <a href="/admin/pricing.php" class="sidebar-link <?= $currentPage == 'pricing.php' ? 'active' : ''; ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      Paket & Harga
    </a>

    <a href="/admin/faq.php" class="sidebar-link <?= $currentPage == 'faq.php' ? 'active' : ''; ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      Kelola FAQ
    </a>

    <a href="/admin/leads.php" class="sidebar-link <?= $currentPage == 'leads.php' ? 'active' : ''; ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      WhatsApp Leads Log
    </a>

    <a href="/admin/content.php" class="sidebar-link <?= $currentPage == 'content.php' ? 'active' : ''; ?>">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      Copy & Settings
    </a>
  </nav>

  <div class="sidebar-footer">
    <div style="margin-bottom: 0.5rem; color: #FFF; font-weight: 600;"><?= e($_SESSION['admin_user']['name'] ?? 'Admin'); ?></div>
    <a href="/" target="_blank" style="color: var(--admin-primary); text-decoration: underline; display: block; margin-bottom: 0.5rem;">Pratinjau Landing Page &rarr;</a>
    <a href="/admin/logout.php" style="color: #EF4444; text-decoration: none;">Keluar (Logout)</a>
  </div>
</div>
