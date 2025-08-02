<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=extension.getPublicUrl
[END_COT_EXT]
==================== */

/**
 * Mstore.
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 *
 * @var string $extensionCode
 * @var ?string $result
 */

declare(strict_types = 1);

// Mstore module has no public standalone page
if ($extensionCode === 'mstore') {
    $result = null;
}