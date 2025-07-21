<?php
/**
 * Store item repository
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

declare(strict_types=1);

namespace cot\modules\mstore\inc;

use Cot;
use cot\repositories\BaseRepository;

defined('COT_CODE') or die('Wrong URL');

class MstoreRepository extends BaseRepository
{
    private static $cacheById = [];

    public static function getTableName(): string
    {
        if (empty(Cot::$db->mstore)) {
            Cot::$db->registerTable('mstore');
        }
        return Cot::$db->mstore;
    }

    /**
     * Fetches store item entry from DB
     * @param int $id Store item ID
     * @param bool $useCache Use one time session cache
     * @return ?array
     */
    public function getById(int $id, bool $useCache = true): ?array
    {
        if ($id < 1) {
            return null;
        }

        if ($useCache && isset(self::$cacheById[$id])) {
            return self::$cacheById[$id] !== false ? self::$cacheById[$id] : null;
        }

        $condition = 'msitem_id = :itemId';
        $params = ['itemId' => $id];

        $results = $this->getByCondition($condition, $params);
        $result = !empty($results) ? $results[0] : null;

        self::$cacheById[$id] = !empty($result) ? $result : false;

        return $result;
    }

    protected function afterFetch(array $item): array
    {
        $item['msitem_id'] = (int) $item['msitem_id'];
        $item['msitem_state'] = (int) $item['msitem_state'];
        $item['msitem_ownerid'] = (int) $item['msitem_ownerid'];
        $item['msitem_date'] = (int) $item['msitem_date'];
        $item['msitem_begin'] = (int) $item['msitem_begin'];
        $item['msitem_expire'] = (int) $item['msitem_expire'];
        $item['msitem_updated'] = (int) $item['msitem_updated'];
        $item['msitem_size'] = (int) $item['msitem_size'];
        $item['msitem_count'] = (int) $item['msitem_count'];
        $item['msitem_filecount'] = (int) $item['msitem_filecount'];

        return $item;
    }
}