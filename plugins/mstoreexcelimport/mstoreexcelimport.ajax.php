<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=ajax
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstoreexcelimport', 'plug');

// Проверяем сессию
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* === AJAX для прогресс-бара === */
if (COT_AJAX && cot_import('a', 'G', 'TXT') === 'progress') {
    $progress = (int) ($_SESSION['import_progress'] ?? 0);
    $total = (int) ($_SESSION['import_total'] ?? 0);
    $percentage = $total > 0 ? round(($progress / $total) * 100) : 0;
    header('Content-Type: application/json');
    echo json_encode(['progress' => $percentage]);
    exit;
}