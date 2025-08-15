<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=global
 * [END_COT_EXT]
 */


defined('COT_CODE') or die('Wrong URL.');

require_once cot_langfile('mstoreexcelimport', 'plug');

// Здесь можно добавить глобальные хуки, если нужно



// Проверяем сессию
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* === AJAX для прогресс-бара === */
if (COT_AJAX && $a == 'progress') {
    $progress = $_SESSION['import_progress'] ?? 0;
    $total = $_SESSION['import_total'] ?? 0;
    $percentage = $total > 0 ? round(($progress / $total) * 100) : 0;
    echo json_encode(['progress' => $percentage]);
    exit;
}