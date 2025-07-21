<?php
წ
/**
 * Store item service
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

declare(strict_types=1);

namespace cot\modules\mstore\inc;

use Cot;
use cot\services\ItemService;
use cot\traits\GetInstanceTrait;
use Throwable;

defined('COT_CODE') or die('Wrong URL.');

class MstoreService
{
    use GetInstanceTrait;

    /**
     * Removes a store item from the CMS.
     * @param int $id Store item ID
     * @param array $itemData Store item data
     * @return bool|string "deleted" message on success, FALSE on error
     */
    function delete(int $id, array $itemData = [])
    {
        if (isset(Cot::$cfg['legacyMode']) && Cot::$cfg['legacyMode']) {
            // @deprecated in 0.9.26
            // $L, $Ls, $R are needed for hook includes
            global $L, $Ls, $R;
        }

        if ($id <= 0) {
            return false;
        }

        if (empty($itemData)) {
            $itemData = MstoreRepository::getInstance()->getById($id);
        }
        if (empty($itemData)) {
            return false;
        }

        if (isset(Cot::$cfg['legacyMode']) && Cot::$cfg['legacyMode']) {
            // @deprecated in 0.9.26
            $ritem = $itemData;
        }

        try {
            Cot::$db->beginTransaction();

            foreach (Cot::$extrafields[Cot::$db->mstore] as $exfld) {
                if (isset($itemData['msitem_' . $exfld['field_name']])) {
                    cot_extrafield_unlinkfiles($itemData['msitem_' . $exfld['field_name']], $exfld);
                }
            }

            $trashcanId = 0; // If trashcan plugin puts the item into trashcan, it should fill this var

            Cot::$db->delete(Cot::$db->mstore, 'msitem_id = ?', $id);
            cot_log("Deleted store item #" . $id, 'mstore', 'delete', 'done');

            cot_mstore_updateStructureCounters($itemData['msitem_cat']);

            $itemDeletedMessage = ['deleted' => Cot::$L['mstore_deleted']];

            if (isset(Cot::$cfg['legacyMode']) && Cot::$cfg['legacyMode']) {
                // @deprecated in 0.9.26
                /* === Hook === */
                foreach (cot_getextplugins('mstore.edit.delete.done') as $pl) {
                    include $pl;
                }
                /* ===== */
            }

            /* === Hook === */
            foreach (cot_getextplugins('mstore.delete.done') as $pl) {
                include $pl;
            }
            /* ===== */

            ItemService::getInstance()->onDelete(MstoreDictionary::SOURCE_MSTORE, $id, $trashcanId);

            Cot::$db->commit();
        } catch (Throwable $e) {
            Cot::$db->rollBack();
            throw $e;
        }

        if (Cot::$cache) {
            if (Cot::$cfg['cache_mstore']) {
                Cot::$cache->static->clearByUri(cot_mstore_url($itemData));
                Cot::$cache->static->clearByUri(cot_url('mstore', ['c' => $itemData['msitem_cat']]));
            }
            if (Cot::$cfg['cache_index']) {
                Cot::$cache->static->clear('index');
            }
        }

        return is_array($itemDeletedMessage) ? implode('; ', $itemDeletedMessage) : $itemDeletedMessage;
    }
}