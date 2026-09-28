<?php
/**
 * Database Connection & Migration Helper for RBK Studio
 * SQLite connection using PDO with fallback structure
 */

define('DB_PATH', __DIR__ . '/../database/rbkstudio.sqlite');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $dbDir = dirname(DB_PATH);
        if (!file_exists($dbDir)) {
            mkdir($dbDir, 0755, true);
        }

        try {
            $pdo = new PDO('sqlite:' . DB_PATH);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->exec("PRAGMA foreign_keys = ON;");
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $pdo;
}

// Auto Initialize Tables and Seed Initial Data if database is empty
function initDatabase() {
    $db = getDB();

    // 1. Users table
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        name TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Settings table
    $db->exec("CREATE TABLE IF NOT EXISTS settings (
        setting_key TEXT PRIMARY KEY,
        setting_value TEXT
    )");

    // 3. Portfolio table
    $db->exec("CREATE TABLE IF NOT EXISTS portfolio (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        category TEXT NOT NULL,
        location TEXT NOT NULL,
        scope_tags TEXT,
        image_url TEXT,
        sort_order INTEGER DEFAULT 0,
        is_active INTEGER DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 4. Pricing packages table
    $db->exec("CREATE TABLE IF NOT EXISTS pricing_packages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code TEXT UNIQUE NOT NULL,
        name TEXT NOT NULL,
        subname TEXT,
        badge_label TEXT,
        price_per_m2 INTEGER NOT NULL,
        price_formatted TEXT NOT NULL,
        description TEXT,
        ideal_for TEXT,
        included_items TEXT,
        positioning_line TEXT,
        is_featured INTEGER DEFAULT 0,
        cta_text TEXT,
        cta_code TEXT,
        sort_order INTEGER DEFAULT 0
    )");

    // 5. FAQs table
    $db->exec("CREATE TABLE IF NOT EXISTS faqs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        question TEXT NOT NULL,
        answer TEXT NOT NULL,
        sort_order INTEGER DEFAULT 0,
        is_active INTEGER DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 6. Leads table
    $db->exec("CREATE TABLE IF NOT EXISTS leads (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        wa_number TEXT,
        location TEXT,
        land_area TEXT,
        building_area TEXT,
        floors TEXT,
        building_type TEXT,
        style TEXT,
        budget TEXT,
        full_message TEXT,
        source_cta TEXT,
        status TEXT DEFAULT 'new',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // 7. Portfolio Videos table
    $db->exec("CREATE TABLE IF NOT EXISTS portfolio_videos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        category TEXT DEFAULT 'Walkthrough 3D',
        youtube_url TEXT NOT NULL,
        thumbnail_url TEXT,
        description TEXT,
        duration TEXT,
        sort_order INTEGER DEFAULT 0,
        is_active INTEGER DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Seed default admin user if not exists
    $userCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($userCount == 0) {
        $stmt = $db->prepare("INSERT INTO users (username, password_hash, name) VALUES (?, ?, ?)");
        $stmt->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT), 'Administrator RBK']);
    }

    // Seed default settings from brief
    $settingsCount = $db->query("SELECT COUNT(*) FROM settings")->fetchColumn();
    if ($settingsCount == 0) {
        $defaultSettings = [
            'wa_number' => '081234500441',
            'site_title' => 'Jasa Desain Arsitektur Rumah, Ruko & Bangunan Jabodetabek | RBK Studio',
            'meta_description' => 'RBK Studio menyediakan jasa desain arsitektur rumah, ruko dan bangunan di Jakarta, Bogor, Depok, Tangerang dan Bekasi. Konsep, gambar kerja, struktur & MEP, RAB dan visualisasi 3D. Mulai Rp60.000/m².',
            'hero_eyebrow' => 'ARCHITECTURE • INTERIOR • PLANNING',
            'hero_headline' => 'Bangun Sekali. Rencanakan dengan Benar Sejak Awal.',
            'hero_supporting' => 'Rumah, ruko, dan bangunan bernilai tinggi tidak seharusnya dimulai dari gambar seadanya. RBK Studio membantu Anda merencanakan bangunan secara menyeluruh, mulai dari konsep arsitektur, gambar kerja, struktur & MEP, RAB, hingga visualisasi 3D. Hasilnya, desain bukan hanya terlihat mewah, tetapi juga fungsional, terukur, dan siap direalisasikan.',
            'hero_price_cue' => 'Mulai dari Rp60.000/m²',
            'hero_trust_strip' => 'Jakarta • Bogor • Depok • Tangerang • Bekasi',
            'section02_problem_headline' => 'Biaya Konstruksi Bisa Mencapai Ratusan Juta hingga Miliaran.',
            'section02_problem_subheadline' => 'Jangan biarkan keputusan desain yang salah membuat Anda membayar dua kali.',
            'section02_bridge' => 'Desain bukan sekadar membuat bangunan terlihat bagus. Desain adalah keputusan yang menentukan bagaimana bangunan Anda dibangun, digunakan, dirawat, dan bernilai di masa depan.',
            'section02_transition' => 'Investasikan pada perencanaannya sebelum menginvestasikan lebih besar pada konstruksinya.',
            'section02_solution_headline' => 'Kami Tidak Hanya Mendesain Bangunan.',
            'section02_solution_subheadline' => 'Kami Merencanakan Bagaimana Anda Akan Hidup di Dalamnya.',
            'section02_solution_body' => 'Setiap ruang memiliki fungsi. Setiap ukuran memiliki alasan. Setiap material memiliki pertimbangan. Setiap detail direncanakan agar bangunan Anda memiliki keseimbangan antara estetika, fungsi, kenyamanan, efisiensi, dan nilai.',
            'section02_solution_closing' => 'RBK Studio merancang bangunan yang pantas dibangun, bukan hanya menarik untuk dilihat.',
            'section03_headline' => 'Luxury Is Not About Making Everything Expensive.',
            'section03_subheadline' => 'Luxury Is About Making Every Decision Feel Intentional.',
            'section03_body' => 'Bangunan premium bukan bangunan yang dipenuhi material mahal. Bangunan premium adalah bangunan yang proporsional, memiliki flow ruang yang baik, detailnya konsisten, materialnya tepat, dan setiap elemen terasa direncanakan.',
            'brand_line_section_03' => 'Elegan tanpa berlebihan. Fungsional tanpa kehilangan karakter.',
            'brand_line_section_07' => 'Plan First. Build Once.',
            'brand_line_section_12' => 'Your Vision. Professionally Planned. Beautifully Designed.',
            'section07_headline' => 'Jangan Menghemat pada Bagian yang Menentukan Seluruh Pembangunan.',
            'section07_body' => 'Salah menentukan cat masih bisa diganti. Salah memilih furniture masih bisa diperbaiki. Tetapi ketika struktur sudah dibangun, dinding sudah berdiri, instalasi sudah tertanam, dan pekerjaan konstruksi sudah berjalan, perubahan menjadi jauh lebih mahal.',
            'section07_closing' => 'Karena itu, keputusan terbaik dilakukan sebelum tukang mulai bekerja.',
            'section10_headline' => 'Jadwalkan Konsultasi Sebelum Pembangunan Dimulai.',
            'section10_body' => 'Setiap proyek membutuhkan waktu untuk concept development, design review, technical detailing, dan koordinasi. Semakin awal Anda berkonsultasi, semakin banyak keputusan yang bisa direncanakan dengan matang.',
            'section11_headline' => 'Punya Tanah? Sudah Punya Ide? Atau Masih Bingung Harus Mulai dari Mana?',
            'section11_body' => 'Mulai dengan konsultasi bersama RBK Studio. Ceritakan proyek Anda kepada tim kami.',
            'section12_headline' => 'Anda Hanya Perlu Membangunnya Sekali.',
            'section12_subheadline' => 'Pastikan Semuanya Direncanakan dengan Tepat.',
            'company_address' => 'Komp. BPPB Pasirmulya Blok Q No. 1, Bogor',
            'company_email' => 'official@rancangbangunkreasi.id',
            'company_instagram' => '@rbkofficial.id',
            'company_youtube' => 'Rancang Bangun Kreasi',
            'company_website' => 'rancangbangunkreasi.id',
        ];

        $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
        foreach ($defaultSettings as $k => $v) {
            $stmt->execute([$k, $v]);
        }
    }

    // Seed default Pricing Packages
    $pkgCount = $db->query("SELECT COUNT(*) FROM pricing_packages")->fetchColumn();
    if ($pkgCount == 0) {
        $packages = [
            [
                'code' => 'BASIC',
                'name' => 'BASIC',
                'subname' => 'Essential Architecture',
                'badge_label' => '',
                'price_per_m2' => 60000,
                'price_formatted' => 'Rp60.000/m²',
                'description' => 'Untuk Anda yang membutuhkan pondasi desain arsitektur yang jelas sebelum pembangunan.',
                'ideal_for' => 'Rumah tinggal • Renovasi • Bangunan sederhana • Pengembangan konsep awal',
                'included_items' => "Gambar denah layout\n3D visual bangunan",
                'positioning_line' => '',
                'is_featured' => 0,
                'cta_text' => 'PILIH BASIC',
                'cta_code' => 'BASIC',
                'sort_order' => 1
            ],
            [
                'code' => 'STANDARD',
                'name' => 'STANDARD',
                'subname' => 'Architecture Planning',
                'badge_label' => 'RECOMMENDED',
                'price_per_m2' => 80000,
                'price_formatted' => 'Rp80.000/m²',
                'description' => 'Untuk Anda yang membutuhkan proses desain lebih matang dan dokumentasi yang lebih komprehensif.',
                'ideal_for' => 'Rumah tinggal premium • Ruko • Renovasi besar • Bangunan komersial skala kecil-menengah',
                'included_items' => "Semua isi Basic\nGambar kerja & detail (arsitektur, struktur, MEP)\nRAB (Rencana Anggaran Biaya)",
                'positioning_line' => 'Best Balance Between Design × Detail × Investment',
                'is_featured' => 1,
                'cta_text' => 'KONSULTASI PAKET STANDARD',
                'cta_code' => 'STANDARD',
                'sort_order' => 2
            ],
            [
                'code' => 'PREMIUM',
                'name' => 'PREMIUM',
                'subname' => 'Comprehensive Architecture Planning',
                'badge_label' => 'SIGNATURE SERVICE',
                'price_per_m2' => 150000,
                'price_formatted' => 'Rp150.000/m²',
                'description' => 'Untuk proyek yang tidak ingin berkompromi pada kualitas perencanaan. Pendekatan lebih komprehensif untuk kebutuhan desain dengan kompleksitas lebih tinggi.',
                'ideal_for' => 'Luxury Residential • Villa • Commercial Building • Premium Ruko • Complex Renovation • Custom Architectural Project',
                'included_items' => "Semua isi Standard\nDesain interior\nVideo animasi 3D eksterior & interior",
                'positioning_line' => 'Untuk Anda yang tidak sekadar ingin membangun, tetapi ingin menciptakan properti yang memiliki karakter dan value.',
                'is_featured' => 0,
                'cta_text' => 'DISCUSS YOUR PREMIUM PROJECT',
                'cta_code' => 'PREMIUM',
                'sort_order' => 3
            ]
        ];

        $stmt = $db->prepare("INSERT INTO pricing_packages 
            (code, name, subname, badge_label, price_per_m2, price_formatted, description, ideal_for, included_items, positioning_line, is_featured, cta_text, cta_code, sort_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($packages as $p) {
            $stmt->execute([
                $p['code'], $p['name'], $p['subname'], $p['badge_label'], $p['price_per_m2'],
                $p['price_formatted'], $p['description'], $p['ideal_for'], $p['included_items'],
                $p['positioning_line'], $p['is_featured'], $p['cta_text'], $p['cta_code'], $p['sort_order']
            ]);
        }
    }

    // Seed default FAQs from Section 14 of Brief
    $faqCount = $db->query("SELECT COUNT(*) FROM faqs")->fetchColumn();
    if ($faqCount == 0) {
        $faqs = [
            [
                'q' => 'Berapa biaya jasa desain di RBK Studio?',
                'a' => 'Biaya desain dihitung dari luas bangunan yang direncanakan dikalikan harga paket per meter persegi: Basic Rp60.000/m², Standard Rp80.000/m², dan Premium Rp150.000/m². Sebagai gambaran, bangunan 150 m² dengan Paket Standard: 150 × Rp80.000 = Rp12.000.000.',
                'order' => 1
            ],
            [
                'q' => 'Apakah ada minimum luas bangunan?',
                'a' => 'Ya. Perhitungan biaya desain menggunakan luas minimum 100 m².',
                'order' => 2
            ],
            [
                'q' => 'Apa saja yang saya dapatkan di setiap paket?',
                'a' => 'Basic: gambar denah layout dan 3D visual bangunan. Standard: seluruh isi Basic, gambar kerja & detail (arsitektur, struktur, MEP), serta RAB. Premium: seluruh isi Standard, desain interior, serta video animasi 3D eksterior & interior.',
                'order' => 3
            ],
            [
                'q' => 'Apakah sudah termasuk gambar struktur, MEP, dan RAB?',
                'a' => 'Ya, untuk Paket Standard dan Premium. Gambar struktur mencakup denah pondasi & sloof, balok lantai, kolom, plat lantai, detail pondasi, detail tangga (untuk 2 lantai atau lebih), dan penulangan. Gambar MEP mencakup instalasi listrik, air bersih, air kotor, serta detail septic tank & resapan. RAB membantu Anda melihat gambaran kebutuhan biaya pembangunan.',
                'order' => 4
            ],
            [
                'q' => 'Berapa kali revisi yang bisa dilakukan?',
                'a' => 'Setiap tahap desain dapat direvisi maksimal 3 kali. Revisi dapat disampaikan melalui chat atau Google Meet, dan sebaiknya dirangkum dalam satu kali pengiriman. Tahap yang sudah disetujui dan berlanjut ke tahap berikutnya tidak direvisi kembali.',
                'order' => 5
            ],
            [
                'q' => 'Berapa lama proses desainnya?',
                'a' => 'Durasi tergantung luas bangunan dan tahap pekerjaan. Sebagai gambaran, denah 3-7 hari kerja, fasad (visual 3D) 3-14 hari kerja, serta gambar kerja (DED) dan RAB 14-28 hari kerja. Jadwal pasti disampaikan saat konsultasi sesuai luas dan scope proyek Anda.',
                'order' => 6
            ],
            [
                'q' => 'Bagaimana sistem pembayarannya?',
                'a' => 'Pembayaran dilakukan bertahap mengikuti progres desain: tanda jadi saat memulai kerja sama, Tahap I 50% setelah desain denah disetujui (dikurangi tanda jadi), Tahap II 30% setelah visual 3D disetujui, dan pelunasan 20% setelah gambar kerja & detail selesai. Tanda jadi: Basic Rp1.000.000, Standard Rp2.500.000, Premium Rp3.000.000. Semua pembayaran ditransfer ke rekening resmi a.n. PT Rancang Bangun Sedaya.',
                'order' => 7
            ],
            [
                'q' => 'Area mana saja yang dilayani?',
                'a' => 'Kami melayani proyek di Jakarta, Bogor, Depok, Tangerang, dan Bekasi. Kantor kami berada di Bogor.',
                'order' => 8
            ],
            [
                'q' => 'Apakah bisa untuk renovasi?',
                'a' => 'Bisa. Paket Basic cocok untuk renovasi, Paket Standard untuk renovasi besar, dan Paket Premium untuk renovasi dengan kompleksitas tinggi.',
                'order' => 9
            ],
            [
                'q' => 'Apakah RBK juga bisa membangunnya?',
                'a' => 'Selain perencanaan, RBK juga melayani jasa konstruksi dan interior. Kebutuhan pembangunan dapat didiskusikan saat konsultasi.',
                'order' => 10
            ],
            [
                'q' => 'Bagaimana cara memulai?',
                'a' => 'Klik tombol WhatsApp dan ceritakan proyek Anda: lokasi, luas tanah, rencana luas bangunan, jumlah lantai, jenis bangunan, style yang diinginkan, dan estimasi budget. Konsultasi awal tanpa biaya.',
                'order' => 11
            ]
        ];

        $stmt = $db->prepare("INSERT INTO faqs (question, answer, sort_order) VALUES (?, ?, ?)");
        foreach ($faqs as $f) {
            $stmt->execute([$f['q'], $f['a'], $f['order']]);
        }
    }

    // Seed default Sample Portfolios (6 items as per brief direction 6-10 projects)
    $portCount = $db->query("SELECT COUNT(*) FROM portfolio")->fetchColumn();
    if ($portCount == 0) {
        $projects = [
            [
                'title' => 'Villa Minimalis Modern 2 Lantai',
                'category' => 'Residential',
                'location' => 'Jakarta Selatan',
                'scope_tags' => 'Arsitektur • Struktur • MEP • RAB',
                'challenge' => 'Lahan hook 180 m² berlokasi padat dengan kebutuhan ruang keluarga yang luas serta privasi tinggi.',
                'solution' => 'Penerapan konsep split-level dengan louver kayu outdoor untuk menyaring cahaya sekaligus menjaga privasi.',
                'image_url' => '',
                'sort_order' => 1
            ],
            [
                'title' => 'Tropical Modern Residence',
                'category' => 'Residential',
                'location' => 'BSD City, Tangerang',
                'scope_tags' => 'Arsitektur • Interior • MEP • RAB',
                'challenge' => 'Sirkulasi udara dan cahaya alami yang minim pada denah awal bangunan eksisting.',
                'solution' => 'Pengembangan inner courtyard tengah dan pemanfaatan fasad secondary skin untuk efisiensi energi.',
                'image_url' => '',
                'sort_order' => 2
            ],
            [
                'title' => 'Boutique Office & Showroom Ruko',
                'category' => 'Commercial',
                'location' => 'Gading Serpong, Tangerang',
                'scope_tags' => 'Arsitektur • Interior • Struktur',
                'challenge' => 'Kebutuhan menggabungkan area display lantai dasar dengan workspace eksekutif di lantai 2 & 3.',
                'solution' => 'Perencanaan customer flow transisi yang efisien dan fasad komersial berdaya tarik visual tinggi.',
                'image_url' => '',
                'sort_order' => 3
            ],
            [
                'title' => 'Komersial Ruko Modern 3 Lantai',
                'category' => 'Ruko',
                'location' => 'Bogor Kota',
                'scope_tags' => 'Arsitektur • MEP • RAB',
                'challenge' => 'Fasad standar ruko yang kurang menonjol dan keterbatasan area parkir depan.',
                'solution' => 'Redesain fasad berkarakter geometris dengan optimalisasi setback pedestrian.',
                'image_url' => '',
                'sort_order' => 4
            ],
            [
                'title' => 'Luxury Private Living & Dining',
                'category' => 'Interior',
                'location' => 'Kemang, Jakarta Selatan',
                'scope_tags' => 'Desain Interior • 3D Render',
                'challenge' => 'Integrasi pencahayaan hangat dengan pemanfaatan material marmer & kayu alam tanpa mengesankan sempit.',
                'solution' => 'Penyusunan lighting layout terstruktur & custom furniture proporsional.',
                'image_url' => '',
                'sort_order' => 5
            ],
            [
                'title' => 'Renovasi Hunian Classic Modern',
                'category' => 'Residential',
                'location' => 'Depok',
                'scope_tags' => 'Arsitektur • Struktur • RAB',
                'challenge' => 'Struktur lama tidak simetris dan kebocoran pada atap bentang lebar.',
                'solution' => 'Perkuatan kolom dan peremajaan rencana atap dengan estetika modern yang kokoh.',
                'image_url' => '',
                'sort_order' => 6
            ]
        ];

        $stmt = $db->prepare("INSERT INTO portfolio 
            (title, category, location, scope_tags, image_url, sort_order) 
            VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($projects as $pr) {
            $stmt->execute([
                $pr['title'], $pr['category'], $pr['location'], $pr['scope_tags'],
                $pr['image_url'], $pr['sort_order']
            ]);
        }
    }

    // Seed default Portfolio Videos
    $videoCount = $db->query("SELECT COUNT(*) FROM portfolio_videos")->fetchColumn();
    if ($videoCount == 0) {
        $videos = [
            [
                'title' => 'Tour Villa Minimalis Modern 2 Lantai - Architecture & Interior Showcase',
                'category' => 'Walkthrough 3D',
                'youtube_url' => 'https://www.youtube.com/watch?v=L_LUpnjgPso',
                'thumbnail_url' => '',
                'description' => 'Visualisasi 3D komprehensif & walkthrough animasi tata ruang interior villa tropis modern.',
                'duration' => '03:45',
                'sort_order' => 1
            ],
            [
                'title' => 'Fasad Ruko Komersial 3 Lantai Gading Serpong',
                'category' => 'Komersial',
                'youtube_url' => 'https://www.youtube.com/watch?v=ScMzIvxBSi4',
                'thumbnail_url' => '',
                'description' => 'Konsep fasad komersial berkarakter geometris dengan optimalisasi setback pedestrian & pencahayaan.',
                'duration' => '02:30',
                'sort_order' => 2
            ],
            [
                'title' => 'Virtual Tour Tropical Residence BSD City',
                'category' => 'Showcase',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail_url' => '',
                'description' => 'Presentasi video animasi 3D inner courtyard & integrasi sirkulasi udara alami.',
                'duration' => '04:15',
                'sort_order' => 3
            ]
        ];

        $stmt = $db->prepare("INSERT INTO portfolio_videos 
            (title, category, youtube_url, thumbnail_url, description, duration, sort_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($videos as $v) {
            $stmt->execute([
                $v['title'], $v['category'], $v['youtube_url'], $v['thumbnail_url'],
                $v['description'], $v['duration'], $v['sort_order']
            ]);
        }
    }
}

// Helper to retrieve all settings as key-value array
function getSettings() {
    $db = getDB();
    $rows = $db->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
    $settings = [];
    foreach ($rows as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

// Call database initialization on load
initDatabase();
