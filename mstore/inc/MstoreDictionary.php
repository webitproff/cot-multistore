<?php
/**
 * Store item dictionary
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

declare(strict_types=1);

namespace cot\modules\mstore\inc;

defined('COT_CODE') or die('Wrong URL');

class MstoreDictionary
{
    public const SOURCE_MSTORE = 'mstore';

    /**
     * Published
     */
    public const STATE_PUBLISHED = 0;

    /**
     * Waiting for approve by admin (moderator)
     */
    public const STATE_PENDING = 1;

    /**
     * Draft
     */
    public const STATE_DRAFT = 2;
}