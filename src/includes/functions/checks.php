<?php
/**
 * PHP vX.x Handlers.
 *
 * @since 160503 Reorganizing structure.
 *
 * @copyright WebSharks, Inc. <http://websharks-inc.com>
 * @license GNU General Public License, version 3
 */

/**
 * Any compatibility issue?
 *
 * @since 141004 First documented version.
 *
 * @return bool True if no issue.
 */
function wp_php_rv() // See below.
{
    return ___wp_php_rv_issue() ? false : true;
}

/**
 * Any compatibility issue?
 *
 * @since 141004 First documented version.
 *
 * @return array Issue; else empty array.
 */
function ___wp_php_rv_issue()
{
    global $wp_php_rv;
    global $___wp_php_rv;
    global $wp_version;

    if (isset($wp_php_rv)) {
        ___wp_php_rv_initialize();
    }
    $required_os         = $___wp_php_rv['os'];
    $min_version         = $___wp_php_rv['min'];
    $max_version         = $___wp_php_rv['max'];
    $minimum_bits        = $___wp_php_rv['bits'];
    $required_extensions = $___wp_php_rv['extensions'];
    $wp_min_version      = $___wp_php_rv['wp']['min'];
    $wp_max_version      = $___wp_php_rv['wp']['max'];

    if ($required_os && ___wp_php_rv_os() !== $required_os) {
        return array('reason' => 'os-incompatible');
    } elseif ($min_version && version_compare(PHP_VERSION, $min_version, '<')) {
        return array('reason' => 'php-needs-upgrade');
    } elseif ($max_version && version_compare(PHP_VERSION, $max_version, '>')) {
        return array('reason' => 'php-needs-downgrade');
    } elseif ($minimum_bits && $minimum_bits / 8 > PHP_INT_SIZE) {
        return array('reason' => 'php-missing-bits');
    }
    if ($required_extensions) { // Requires PHP extensions?
        $missing_extensions = array(); // Initialize.

        foreach ($required_extensions as $_required_extension) {
            if (!extension_loaded($_required_extension)) {
                $php_missing_extensions[] = $_required_extension;
            } // See also: <http://jas.xyz/1TtzgX5>
        } // unset($_required_extension); // Housekeeping.

        if ($missing_extensions) { // Missing PHP extensions?
            return array('php_missing_extensions' => $php_missing_extensions,
                         'reason'                 => 'php-missing-extensions', );
        }
    }
    if ($wp_min_version && version_compare($wp_version, $wp_min_version, '<')) {
        return array('reason' => 'wp-needs-upgrade');
    } elseif ($wp_max_version && version_compare($wp_version, $wp_max_version, '>')) {
        return array('reason' => 'wp-needs-downgrade');
    }
    return array(); // No problem.
}

/**
 * Initializes each instance; unsets `$wp_php_rv`.
 *
 * @since 141004 First documented version.
 *
 * @note `$wp_php_rv` is for the API, we use a different variable internally.
 *    The internal global is defined here: `$___wp_php_rv`.
 */
function ___wp_php_rv_initialize()
{
    global $wp_php_rv;
    global $___wp_php_rv;

    $___wp_php_rv = array(
        'os'         => '',
        'min'        => '',
        'max'        => '',
        'bits'       => 0,
        'extensions' => array(),
        'wp'         => array(
            'min' => '',
            'max' => '',
        ),
    );
    if (!empty($wp_php_rv)) {
        if (is_string($wp_php_rv)) {
            $___wp_php_rv['min'] = $wp_php_rv;
        } elseif (is_array($wp_php_rv)) {
            if (!empty($wp_php_rv['os'])) {
                $___wp_php_rv['os'] = (string) $wp_php_rv['os'];
            }
            if (!empty($wp_php_rv['min'])) {
                $___wp_php_rv['min'] = (string) $wp_php_rv['min'];
            } elseif (!empty($wp_php_rv['rv'])) {
                $___wp_php_rv['min'] = (string) $wp_php_rv['rv'];
            }
            if (!empty($wp_php_rv['max'])) {
                $___wp_php_rv['max'] = (string) $wp_php_rv['max'];
            }
            if (!empty($wp_php_rv['bits'])) {
                $___wp_php_rv['bits'] = (int) $wp_php_rv['bits'];
            }
            if (!empty($wp_php_rv['extensions'])) {
                $___wp_php_rv['extensions'] = (array) $wp_php_rv['extensions'];
            } elseif (!empty($wp_php_rv['re'])) {
                $___wp_php_rv['extensions'] = (array) $wp_php_rv['re'];
            }
            if (!empty($wp_php_rv['wp']['min'])) {
                $___wp_php_rv['wp']['min'] = (string) $wp_php_rv['wp']['min'];
            }
            if (!empty($wp_php_rv['wp']['max'])) {
                $___wp_php_rv['wp']['max'] = (string) $wp_php_rv['wp']['max'];
            }
        }
    } // End of API conversion to internal global settings.
    $wp_php_rv = null; // Unset to avoid theme/plugin conflicts.
}
