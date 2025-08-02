<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=header.main
[END_COT_EXT]
==================== */

/**
 * Header notices for new store items
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

if (
    Cot::$usr['id'] > 0
    && (cot_auth('mstore', 'any', 'A') || cot_auth('mstore', 'any', 'W'))
) {
    require_once cot_incfile('mstore', 'module');
}

if (Cot::$usr['id'] > 0 && cot_auth('mstore', 'any', 'A')) {
    Cot::$sys['mstorequeued'] = (int) Cot::$db->query('SELECT COUNT(*) FROM ' . Cot::$db->mstore .
        ' WHERE msitem_state = 1')->fetchColumn();

    if (Cot::$sys['mstorequeued'] > 0) {
        Cot::$out['notices_array'][] = [
            cot_url('admin', 'm=mstore'),
            cot_declension(Cot::$sys['mstorequeued'], $Ls['unvalidated_mstore'])
        ];
    }

    Cot::$sys['mstoreindrafts'] = (int) Cot::$db->query('SELECT COUNT(*) FROM ' . Cot::$db->mstore
        ." WHERE msitem_state = 2")->fetchColumn();

    if (Cot::$sys['mstoreindrafts'] > 0) {
        Cot::$out['notices_array'][] = [
            cot_url('admin', 'm=mstore&filter=drafts'),
            cot_declension(Cot::$sys['mstoreindrafts'], $Ls['mstore_in_drafts'])
        ];
    }

} elseif (Cot::$usr['id'] > 0 && cot_auth('mstore', 'any', 'W')) {
    Cot::$sys['mstorequeued'] = (int) Cot::$db->query('SELECT COUNT(*) FROM ' . Cot::$db->mstore .
        ' WHERE msitem_state=1 AND msitem_ownerid = ' . Cot::$usr['id'])->fetchColumn();

    if (Cot::$sys['mstorequeued'] > 0) {
        Cot::$out['notices_array'][] = [
            cot_url('mstore', 'c=unvalidated'),
            cot_declension(Cot::$sys['mstorequeued'], $Ls['unvalidated_mstore'])
        ];
    }

    Cot::$sys['mstoreindrafts'] = (int) Cot::$db->query('SELECT COUNT(*) FROM ' . Cot::$db->mstore .
        " WHERE msitem_state=2 AND msitem_ownerid = " . Cot::$usr['id'])->fetchColumn();

    if (Cot::$sys['mstoreindrafts'] > 0) {
        Cot::$out['notices_array'][] = [
            cot_url('mstore', 'c=saved_drafts'),
            cot_declension(Cot::$sys['mstoreindrafts'], $Ls['mstore_in_drafts'])
        ];
    }
}