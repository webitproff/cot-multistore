<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=item.getItems
[END_COT_EXT]
==================== */

declare(strict_types = 1);

use cot\dto\ItemDto;
use cot\extensions\ExtensionsDictionary;
use cot\modules\mstore\inc\MstoreDictionary;
use cot\modules\mstore\inc\MstoreRepository;

defined('COT_CODE') or die('Wrong URL');

/**
 * Mstore module
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 *
 * @var string $source
 * @var list<int|numeric-string> $sourceIds
 * @var bool $withFullItemData
 * @var list<ItemDto> $result
 */

if ($source !== MstoreDictionary::SOURCE_MSTORE || empty($sourceIds)) {
    return;
}

// for include file
global $L, $R, $Ls;

require_once cot_incfile('mstore', ExtensionsDictionary::TYPE_MODULE);

$mstoreIds = [];
foreach ($sourceIds as $id) {
    $id = (int) $id;
    if ($id > 0) {
        $mstoreIds[] = $id;
    }
}
$mstoreIds = array_unique($mstoreIds);

$condition = 'msitem_id IN (' . implode(',', $mstoreIds) . ')';
$items = MstoreRepository::getInstance()->getByCondition($condition);

foreach ($items as $row) {
    $url = cot_mstore_url($row);
    if (!cot_url_check($url)) {
        $url = COT_ABSOLUTE_URL . $url;
    }

    $dto = new ItemDto(
        MstoreDictionary::SOURCE_MSTORE,
        $row['msitem_id'],
        'mstore',
        Cot::$L['Mstore'],
        $row['msitem_title'],
        $row['msitem_desc'],
        $url,
        (int) $row['msitem_ownerid']
    );

    if ($withFullItemData) {
        $dto->data = $row;
    }
    $dto->categoryCode = $row['msitem_cat'];
    $dto->categoryTitle = 'Unknown';
    if (isset(Cot::$structure['mstore'][$row['msitem_cat']])) {
        $dto->categoryUrl = cot_url('mstore', ['c' => $row['msitem_cat']]);
        $dto->categoryTitle = Cot::$structure['mstore'][$row['msitem_cat']]['title'];
    }

    $result[$dto->id] = $dto;
}

/* === Hook === */
foreach (cot_getextplugins('mstore.item.getItems.done') as $pl) {
    include $pl;
}
/* ===== */