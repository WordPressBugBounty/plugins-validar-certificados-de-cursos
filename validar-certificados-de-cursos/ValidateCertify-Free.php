<?php
/**
 *
 * Plugin Name:        ValidateCertify Free
 * Plugin URI:         https://www.systenjrh.com/plugin-validatecertify/
 * Author:             Systen JRH
 * Author URI:         https://www.systenjrh.com/
 * Version:            1.6.3
 * Requires at least:  6.0
 * Requires PHP:       7.3
 * Text Domain:        stvc_validatecertify
 * Domain Path:        /languages
 * License:            GPL v2 or later
 * License URI:        https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Description:        With ValidateCertify Free, you can guarantee the authenticity and veracity of the certificates issued, providing confidence to your students and those who validate them. Simplify the verification process and improve the experience of your users with ValidateCertify. Load your certificate base and validate them with the code from your website.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * @package ValidateCertify
 * @category Core Functionality
*/

// Define the current version of the plugin.
define('stvc_validatecertify_version', '1.6.3');

/**
 * Handles tasks during plugin activation.
 *
 * This function ensures that necessary setup tasks, such as creating
 * database tables, are performed when the plugin is activated.
 *
 * @return void
 */
function stvc_install() {
    require_once 'includes/class-stvc-activator.php';
}
register_activation_hook(__FILE__, 'stvc_install');


// Include necessary core classes and functionality files.
require_once plugin_dir_path(__FILE__) . 'includes/class-stvc-menu.php'; // Handles admin menu creation.
require_once plugin_dir_path(__FILE__) . 'includes/class-stvc-notification.php'; // Manages admin notifications.
require_once plugin_dir_path(__FILE__) . 'admin/partials/class-stvc-shortcode.php'; // Registers plugin shortcodes.
require_once plugin_dir_path(__FILE__) . 'admin/partials/class-stvc-admin-display.php'; // Renders admin settings display.
require_once plugin_dir_path(__FILE__) . 'admin/partials/class-stvc-admin-dashboard.php'; // Handles dashboard UI functionality.

// Instantiate the admin dashboard functionality.
$stvc_admin_dashboard = new STVC_Admin_Dashboard();

/**
 * Enqueue custom plugin styles.
 *
 * Adds CSS styles used by the plugin in both the admin panel and the frontend.
 *
 * @return void
 */
function validatecertify_enqueue_styles() {
    wp_enqueue_style( 
        'validatecertify-styles', 
        plugins_url( 'assets/css/validatecertify-styles.css', __FILE__ ), 
        array(),
        '1.0.0'
    );
}

add_action( 'wp_enqueue_scripts', 'validatecertify_enqueue_styles' ); // Frontend
add_action( 'admin_enqueue_scripts', 'validatecertify_enqueue_styles' ); // Backend

// Register the activation notice hook to display an admin notice when the plugin is activated.
register_activation_hook(__FILE__, 'stvc_admin_notice_plugin_activation_hook');

// Add admin notices for plugin updates or important messages.
add_action('admin_notices', 'stvc_admin_notice_plugin_notice');

// Add custom links to the plugin's action links in the Plugins screen.
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'stvc_validatecertify');

// Add custom action links to the plugin row meta information.
add_filter('plugin_row_meta', 'agregar_enlaces_version_plugin', 10, 2);

add_filter( 'plugin_action_links', 'stvc_validatecertify_add_action_plugin', 10, 5 );

/**
 * Load plugin text domain for translations.
 *
 * Ensures that all strings in the plugin can be translated into different languages.
 *
 * @return void
 */
function stvc_plugin_load_textdomain() {
    load_plugin_textdomain('stvc_validatecertify', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'stvc_plugin_load_textdomain');

/**
 * Translate the plugin description dynamically.
 *
 * Updates the plugin description on the Plugins screen to support translations.
 *
 * @param array $all_plugins Array of all installed plugins.
 * @return array Updated plugin data with translated description.
 */
add_filter('all_plugins', 'stvc_translate_plugin_description');
