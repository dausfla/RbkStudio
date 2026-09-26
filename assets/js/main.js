/**
 * RBK Studio - Landing Page Interactive Logic & WhatsApp CTA Automation
 */

document.addEventListener('DOMContentLoaded', function () {
  // 1. Mobile Menu Toggle
  const mobileToggle = document.getElementById('mobileNavToggle');
  const navMenu = document.getElementById('navMenu');

  if (mobileToggle && navMenu) {
    mobileToggle.addEventListener('click', function () {
      navMenu.classList.toggle('open');
      const isExpanded = navMenu.classList.contains('open');
      mobileToggle.setAttribute('aria-expanded', isExpanded);
    });

    // Close menu when clicking nav link
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => navMenu.classList.remove('open'));
    });
  }

  // 2. FAQ Accordion Toggle
  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach(item => {
    const btn = item.querySelector('.faq-button');
    btn.addEventListener('click', () => {
      const isOpen = item.classList.contains('open');

      // Close all other items
      faqItems.forEach(i => i.classList.remove('open'));

      if (!isOpen) {
        item.classList.add('open');
      }
    });
  });

  // 3. Portfolio Category Filtering
  const tabBtns = document.querySelectorAll('.portfolio-tabs .tab-btn');
  const portfolioCards = document.querySelectorAll('.portfolio-card');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      tabBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      const filter = this.getAttribute('data-filter');
      portfolioCards.forEach(card => {
        if (filter === 'all' || card.getAttribute('data-category').toLowerCase() === filter.toLowerCase()) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // 4. Interactive WhatsApp Brief Builder (Section 11)
  const leadForm = document.getElementById('rbkBriefForm');
  if (leadForm) {
    leadForm.addEventListener('submit', function (e) {
      e.preventDefault();

      const waNumber = leadForm.getAttribute('data-wa') || '081234500441';
      const location = document.getElementById('lead_location').value.trim() || '____';
      const landArea = document.getElementById('lead_land_area').value.trim() || '____';
      const buildingArea = document.getElementById('lead_building_area').value.trim() || '____';
      const floors = document.getElementById('lead_floors').value.trim() || '____';
      const buildingType = document.getElementById('lead_type').value || '____';
      const style = document.getElementById('lead_style').value || '____';
      const budget = document.getElementById('lead_budget').value || '____';

      // Brief prefill message as specified in Section 11 of PDF
      const message = `Halo RBK Studio, saya ingin konsultasi desain.\n\n` +
        `• Lokasi proyek: ${location}\n` +
        `• Luas tanah: ${landArea} m²\n` +
        `• Rencana luas bangunan: ${buildingArea} m²\n` +
        `• Jumlah lantai: ${floors}\n` +
        `• Jenis bangunan: ${buildingType}\n` +
        `• Style: ${style}\n` +
        `• Estimasi budget: ${budget}`;

      // Log Lead via API to CMS Admin
      fetch('/api/lead.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          location: location,
          land_area: landArea,
          building_area: buildingArea,
          floors: floors,
          building_type: buildingType,
          style: style,
          budget: budget,
          full_message: message,
          source_cta: 'LENGKAP'
        })
      }).catch(err => console.error('Error recording lead:', err));

      // Redirect to WhatsApp
      const cleanPhone = waNumber.replace(/[^0-9]/g, '').replace(/^0/, '62');
      const waUrl = `https://wa.me/${cleanPhone}?text=${encodeURIComponent(message)}`;
      window.open(waUrl, '_blank');
    });
  }

  // 5. Track general CTA WhatsApp clicks
  document.querySelectorAll('[data-cta-code]').forEach(btn => {
    btn.addEventListener('click', function () {
      const ctaCode = this.getAttribute('data-cta-code');
      fetch('/api/lead.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          source_cta: ctaCode,
          full_message: 'User clicked CTA button: ' + ctaCode
        })
      }).catch(err => console.error('Error logging click:', err));
    });
  });

  // 6. Hide Mobile Sticky Bar when Section 11 is in viewport (Section 11 implementation note)
  const section11 = document.getElementById('lead-capture-section');
  const stickyMobileBar = document.getElementById('stickyMobileBar');

  if (section11 && stickyMobileBar) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          stickyMobileBar.style.display = 'none';
        } else {
          stickyMobileBar.style.display = 'block';
        }
      });
    }, { threshold: 0.1 });

    observer.observe(section11);
  }

  // 7. Interactive 3D Cursor Spotlight & Tilt Effect on Pricing Cards
  const pricingCards = document.querySelectorAll('.pricing-card');
  pricingCards.forEach(card => {
    card.addEventListener('mousemove', function (e) {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      card.style.setProperty('--mouse-x', `${x}px`);
      card.style.setProperty('--mouse-y', `${y}px`);

      // 3D subtle tilt calculation
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;
      const rotateX = ((y - centerY) / centerY) * -6; // max 6 deg tilt
      const rotateY = ((x - centerX) / centerX) * 6;

      card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px)`;
    });

    card.addEventListener('mouseleave', function () {
      card.style.transform = '';
    });
  });

  // 8. Live Interactive Architectural Cost Estimator Calculator
  const slider = document.getElementById('calcAreaSlider');
  const areaValueDisplay = document.getElementById('calcAreaValue');
  const totalCostDisplay = document.getElementById('calcTotalCost');
  const durationDisplay = document.getElementById('calcDuration');
  const waBtn = document.getElementById('calcWaBtn');
  const pkgButtons = document.querySelectorAll('.calc-pkg-btn');

  if (slider && totalCostDisplay) {
    let currentArea = parseInt(slider.value, 10);
    let currentPricePerM2 = 80000; // default Standard
    let currentPkgName = 'Standard';
    let currentWaCode = 'STANDARD';

    function calculateEstimate() {
      currentArea = parseInt(slider.value, 10);
      areaValueDisplay.textContent = `${currentArea} m²`;

      // Minimum 100m² condition from brief
      const effectiveArea = Math.max(currentArea, 100);
      const totalPrice = effectiveArea * currentPricePerM2;

      // Format Rupiah
      const formattedTotal = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0
      }).format(totalPrice);

      totalCostDisplay.textContent = formattedTotal;

      // Estimate duration based on area
      let estDays = '7-14 Hari Kerja';
      if (effectiveArea > 250) estDays = '14-28 Hari Kerja';
      if (effectiveArea > 500) estDays = '28-45 Hari Kerja';
      durationDisplay.textContent = estDays;

      // Update WA Button Link
      if (waBtn) {
        const waPhone = waBtn.getAttribute('data-wa') || '081234500441';
        const msg = `Halo RBK Studio, hasil simulasi estimasi website:\n` +
          `• Rencana Luas Bangunan: ${currentArea} m²\n` +
          `• Paket Pilihan: ${currentPkgName} (${new Intl.NumberFormat('id-ID').format(currentPricePerM2)}/m²)\n` +
          `• Estimasi Biaya Desain: ${formattedTotal}\n` +
          `Saya ingin konsultasi lebih lanjut mengenai proyek ini.`;
        
        const cleanPhone = waPhone.replace(/[^0-9]/g, '').replace(/^0/, '62');
        waBtn.setAttribute('href', `https://wa.me/${cleanPhone}?text=${encodeURIComponent(msg)}`);
        waBtn.setAttribute('data-cta-code', currentWaCode);
      }
    }

    slider.addEventListener('input', calculateEstimate);

    pkgButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        pkgButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        currentPricePerM2 = parseInt(this.getAttribute('data-price'), 10);
        currentPkgName = this.getAttribute('data-name');
        currentWaCode = this.getAttribute('data-code');

        calculateEstimate();
      });
    });

    // Initial calculation
    calculateEstimate();
  }
});
