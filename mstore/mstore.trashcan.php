<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=trashcan.api
[END_COT_EXT]
==================== */

/**
 * Trash can support for mstore
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');

// Register restoration table
$trash_types['mstore'] = Cot::$db->mstore;

/**
 * Sync mstore action
 * @param array $data Mstore item data as array (trashcan item data)
 * @return bool
 */
function cot_trash_mstore_sync($data)
{
    cot_mstore_updateStructureCounters($data['msitem_cat']);
    if (\Cot::$cache) {
        if (\Cot::$cfg['cache_mstore']) {
            \Cot::$cache->static->clearByUri(cot_mstore_url($data));
            \Cot::$cache->static->clearByUri(cot_url('mstore', ['c' => $data['msitem_cat']]));
        }
        if (Cot::$cfg['cache_index']) {
            Cot::$cache->static->clear('index');
        }
    }
	return true;
}