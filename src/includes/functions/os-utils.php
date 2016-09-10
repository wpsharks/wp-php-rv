<?php
/**
 * PHP vX.x Handlers.
 *
 * @since 160910 Reorganizing OS utils.
 *
 * @copyright WebSharks, Inc. <http://websharks-inc.com>
 * @license GNU General Public License, version 3
 */

/**
 * OS detection.
 *
 * @since 160523 OS detection.
 *
 * @return string OS identifier.
 */
function ___wp_php_rv_os()
{
    static $os; // Static cache.

    if (isset($os)) {
        return $os; // Cached already.
    } elseif (stripos(PHP_OS, 'win') === 0) {
        return $os = 'win';
    } else {
        return $os = 'nix';
    }
}

/**
 * OS long name.
 *
 * @since 160523 OS detection.
 *
 * @return string OS long name.
 */
function ___wp_php_rv_os_name($os)
{
    if ($os === 'win') {
        return 'Windows®';
    } else {
        return 'Unix-like';
    }
}
