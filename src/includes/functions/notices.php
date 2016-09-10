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
 * Creates a WP Dashboard notice regarding PHP requirements.
 *
 * @param string $brand_name Name of the calling theme/plugin.
 */
function wp_php_rv_notice($brand_name = '')
{
    # Admin area only.

    if (!is_admin()) {
        return; // Unnecesary.
    } // Stop if not in admin area.

    # Maybe initialize.

    global $wp_php_rv;
    global $___wp_php_rv;

    if (isset($wp_php_rv)) {
        ___wp_php_rv_initialize();
    }
    # Copy of PHP requirements (RV).

    $rv = $___wp_php_rv; // Snapshot in time.

    # Determine reason for dependency failure.

    if (!($issue = ___wp_php_rv_issue())) {
        return; // Nothing to do here.
    } // This is also a snapshot in time.

    # Establish brand name for this software.

    if (!($brand_name = (string) $brand_name)) {
        $brand_name = ___wp_php_rv_notice_brand_name();
    } // If brand name detection fails too, use generic.
    $brand_name = $brand_name ? $brand_name : __('This Software', 'wp-php-rv');

    # @TODO Once WordPress has a minimum of PHP 5.3, move everything below into a closure.
    # See example of a closure for this in the WP Sharks Core RV variation of this function.

    # Define pre-styled icons needed for markup.

    $arrow = '<span class="dashicons dashicons-editor-break" style="-webkit-transform:scale(-1, 1); transform:scale(-1, 1);"></span>';
    $icon  = '<span class="dashicons dashicons-admin-tools" style="display:inline-block; width:64px; height:64px; font-size:64px; float:left; margin:-5px 10px 0 -2px;"></span>';

    # Generate markup for PHP dependency notice.

    switch ($issue['reason']) { // Based on reason.

        case 'os-incompatible': // OS incomaptible.
            $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
            $markup     .= __('Incompatible Operating System', 'wp-php-rv');
            $markup .= '</p>';
            $markup .= '<p style="margin:0 0 .5em 0;">';
            $markup     .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires a %2$s operating system.', 'wp-php-rv'), esc_html($brand_name), esc_html(___wp_php_rv_os_name($rv['os']))).'<br />';
            $markup     .= sprintf(__('You\'re currently running %1$s, which is not supported by %2$s at this time.', 'wp-php-rv'), esc_html(PHP_OS), esc_html($brand_name)).'<br />';
            $markup     .= $arrow.' '.__('A compatible OS is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
            $markup     .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, change the OS or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
            $markup .= '</p>';
            break; // All done here.

        case 'php-needs-upgrade': // Upgrade to latest version of PHP.
            $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
            $markup     .= __('PHP Upgrade Required', 'wp-php-rv');
            $markup .= '</p>';
            $markup .= '<p style="margin:0 0 .5em 0;">';
            $markup     .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires PHP v%2$s (or higher).', 'wp-php-rv'), esc_html($brand_name), esc_html($rv['min'])).'<br />';
            $markup     .= sprintf(__('You\'re currently running the older PHP v%1$s, which is not supported by %2$s.', 'wp-php-rv'), esc_html(___wp_php_rv_get_php_version()), esc_html($brand_name)).'<br />';
            $markup     .= $arrow.' '.__('An update is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
            $markup     .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, upgrade PHP or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
            $markup .= '</p>';
            break; // All done here.

        case 'php-needs-downgrade': // Downgrade to older version of PHP.
            $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
            $markup     .= __('PHP Downgrade Required', 'wp-php-rv');
            $markup .= '</p>';
            $markup .= '<p style="margin:0 0 .5em 0;">';
            $markup     .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires an older version of PHP.', 'wp-php-rv'), esc_html($brand_name)).'<br />';
            $markup     .= sprintf(__('This software is compatible up to PHP v%1$s, but you\'re running the newer PHP v%2$s.', 'wp-php-rv'), esc_html($rv['max']), esc_html(___wp_php_rv_get_php_version())).'<br />';
            $markup     .= $arrow.' '.__('A downgrade is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
            $markup     .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, downgrade PHP or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
            $markup .= '</p>';
            break; // All done here.

        case 'php-missing-bits': // Upgrade to a more powerful architecture.
            $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
            $markup     .= __('System Upgrade Required', 'wp-php-rv');
            $markup .= '</p>';
            $markup .= '<p style="margin:0 0 .5em 0;">';
            $markup     .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires PHP on a %2$s-bit+ architecture.', 'wp-php-rv'), esc_html($brand_name), esc_html($rv['bits'])).'<br />';
            $markup     .= sprintf(__('You\'re running an older %1$s-bit architecture, which is not supported by %2$s.', 'wp-php-rv'), esc_html(PHP_INT_SIZE * 8), esc_html($brand_name)).'<br />';
            $markup     .= $arrow.' '.__('An update is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
            $markup     .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, upgrade your system or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
            $markup .= '</p>';
            break; // All done here.

        case 'php-missing-functions': // PHP is missing required functions.
            $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
            $markup     .= __('PHP Function(s) Missing', 'wp-php-rv');
            $markup .= '</p>';
            $markup .= '<p style="margin:0 0 .5em 0;">';
            $markup     .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It depends on PHP function(s): %2$s.', 'wp-php-rv'), esc_html($brand_name), '<code>'.implode('</code>, <code>', array_map('esc_html', $issue['php_missing_functions'])).'</code>').'<br />';
            $markup     .= $arrow.' '.__('An action is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
            $markup     .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, enable missing function(s) or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
            $markup .= '</p>';
            break; // All done here.

        case 'php-missing-extensions': // PHP is missing required extensions.
            $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
            $markup     .= __('PHP Extension(s) Missing', 'wp-php-rv');
            $markup .= '</p>';
            $markup .= '<p style="margin:0 0 .5em 0;">';
            $markup     .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It depends on PHP extension(s): %2$s.', 'wp-php-rv'), esc_html($brand_name), '<code>'.implode('</code>, <code>', array_map('esc_html', $issue['php_missing_extensions'])).'</code>').'<br />';
            $markup     .= $arrow.' '.__('An action is necessary. <strong>Please contact your hosting company for assistance</strong>.', 'wp-php-rv').'<br />';
            $markup     .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, enable missing extension(s) or remove %1$s from WordPress.</em>', 'wp-php-rv'), esc_html($brand_name));
            $markup .= '</p>';
            break; // All done here.

        case 'wp-needs-upgrade': // Upgrade to latest version of WP.
            $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
            $markup     .= __('WP Upgrade Required', 'wp-php-rv');
            $markup .= '</p>';
            $markup .= '<p style="margin:0 0 .5em 0;">';
            $markup     .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires WP v%2$s (or higher).', 'wp-php-rv'), esc_html($brand_name), esc_html($rv['wp']['min'])).'<br />';
            $markup     .= sprintf(__('You\'re currently running the older WP v%1$s, which is not supported by %2$s.', 'wp-php-rv'), esc_html(___wp_php_rv_get_wp_version()), esc_html($brand_name)).'<br />';
            $markup     .= $arrow.' '.sprintf(__('An upgrade is necessary. <strong>Please <a href="%1$s">click here to upgrade now</a></strong>.', 'wp-php-rv'), esc_url(network_admin_url('/update-core.php'))).'<br />';
            $markup     .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, upgrade WordPress or deactivate %1$s.</em>', 'wp-php-rv'), esc_html($brand_name));
            $markup .= '</p>';
            break; // All done here.

        case 'wp-needs-downgrade': // Downgrade to older version of WP.
            $markup = '<p style="font-weight:bold; font-size:125%; margin:.25em 0 0 0;">';
            $markup     .= __('WP Downgrade Required', 'wp-php-rv');
            $markup .= '</p>';
            $markup .= '<p style="margin:0 0 .5em 0;">';
            $markup     .= $icon.sprintf(__('<strong>%1$s is not active.</strong> It requires an older version of WP.', 'wp-php-rv'), esc_html($brand_name)).'<br />';
            $markup     .= sprintf(__('This software is compatible up to WP v%1$s, but you\'re running the newer WP v%2$s.', 'wp-php-rv'), esc_html($rv['wp']['max']), esc_html(___wp_php_rv_get_wp_version())).'<br />';
            $markup     .= $arrow.' '.sprintf(__('A downgrade is necessary. <strong>Please see: <a href="%1$s">WordPress.org release archive</a></strong>.', 'wp-php-rv'), esc_url('https://wordpress.org/download/release-archive/')).'<br />';
            $markup     .= sprintf(__('<em style="font-size:80%%; opacity:.7;">To remove this message, downgrade WordPress or deactivate %1$s.</em>', 'wp-php-rv'), esc_html($brand_name));
            $markup .= '</p>';
            break; // All done here.

        default: // Default case handler; i.e., anything else.
            return; // Nothing to do here.
    }
    # This allows filters to alter markup before display.

    if (!($markup = apply_filters('wp_php_rv_notice_markup', $markup, get_defined_vars()))) {
        return; // Nothing to display in this case.
    }
    # Attach an action to display the notice now.

    add_action('all_admin_notices', create_function(
        '', // No args; i.e., leaving this empty.
        'global $pagenow;'.// Global used below.

        'if (!current_user_can(\'activate_plugins\')) return;'.
        'if (in_array($pagenow, array(\'update-core.php\'), true)) return;'.
        'if (in_array($pagenow, array(\'plugins.php\', \'themes.php\', \'update.php\'), true)'.
        '    && !empty($_REQUEST[\'___action_via\']) && $_REQUEST[\'___action_via\'] === \'wp-php-rv\') return;'.
        'if (!apply_filters(\'wp_php_rv_notice_display\', true, get_defined_vars())) return;'.

        'echo \''.// Wrap `$markup` inside a WordPress warning.
            '<div class="notice notice-warning" style="min-height:7.5em;">'.
                str_replace("'", "\\'", $markup).
            '</div>'.
        '\';'
    ));
}

/**
 * Last-ditch effort to find a brand name.
 *
 * @return string Name of the calling theme/plugin.
 */
function ___wp_php_rv_notice_brand_name()
{
    if (!($debug_backtrace = @debug_backtrace())) {
        return ''; // Not possible.
    } elseif (empty($debug_backtrace[1]['file'])) {
        return ''; // Not possible.
    }
    $calling_theme_plugin_dir = ''; // Initialize.
    $_calling_dir             = dirname($debug_backtrace[1]['file']);

    for ($_i = 0; $_i < 10; ++$_i) { // Search 10 levels max.
        //
        if (in_array(basename(dirname($_calling_dir)), array('themes', 'plugins'), true)
            && basename(dirname(dirname($_calling_dir))) === 'wp-content') {
            $calling_theme_plugin_dir = $_calling_dir;
            break; // We can stop here.
            //
        } else { // Move up one directory.
            $_calling_dir = dirname($_calling_dir);
        }
    } // unset($_i, $_calling_dir); // Housekeeping.

    if (!$calling_theme_plugin_dir) {
        return ''; // Not possible.
    }
    $brand_name        = strtolower(basename($calling_theme_plugin_dir));
    $brand_name        = preg_replace('/[_\-]+(?:lite|pro)$/u', '', $brand_name);
    $brand_name        = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $brand_name);
    return $brand_name = ucwords(trim($brand_name));
}
