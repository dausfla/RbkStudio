<?php
/**
 * Auth & Helper Functions for RBK Studio
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAuth() {
    if (empty($_SESSION['admin_user'])) {
        header('Location: /admin/index.php');
        exit;
    }
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Generate WhatsApp URL with prefilled text
 */
function buildWaUrl($phone, $message) {
    // Sanitize phone number (remove leading +, spaces, dashes)
    $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
    if (strpos($cleanPhone, '08') === 0) {
        $cleanPhone = '628' . substr($cleanPhone, 2);
    }
    return 'https://wa.me/' . $cleanPhone . '?text=' . rawurlencode($message);
}

/**
 * Sanitization helper
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Extract YouTube Video ID from various YouTube URL formats
 */
function getYouTubeVideoId($url) {
    if (empty($url)) return '';
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^"&?\/\s]{11})/';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return '';
}

/**
 * Get YouTube Thumbnail URL (fallback to default HQ if no custom thumbnail provided)
 */
function getYouTubeThumbnail($youtubeUrl, $customThumbnailUrl = '') {
    if (!empty($customThumbnailUrl)) {
        return $customThumbnailUrl;
    }
    $videoId = getYouTubeVideoId($youtubeUrl);
    if (!empty($videoId)) {
        return 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg';
    }
    return '/assets/images/placeholder_video.jpg';
}

