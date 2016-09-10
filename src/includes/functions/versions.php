<?php
/**
 * PHP vX.x Handlers.
 *
 * @since 160910 Adding version utils.
 *
 * @copyright WebSharks, Inc. <http://websharks-inc.com>
 * @license GNU General Public License, version 3
 */

/**
 * Get installed WP version.
 *
 * @since 160910 Enhancing notices.
 *
 * @return string Installed WP version.
 */
function ___wp_php_rv_get_wp_version()
{
    return (string) (!empty($GLOBALS['wp_version']) ? $GLOBALS['wp_version'] : '');
}

/**
 * Get installed PHP version.
 *
 * @since 160910 Enhancing notices.
 *
 * @return string Installed PHP version.
 */
function ___wp_php_rv_get_php_version()
{
    return strpos(PHP_VERSION, '+') !== false ? strstr(PHP_VERSION, '+', true) : PHP_VERSION;
}
