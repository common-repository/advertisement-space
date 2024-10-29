<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              intlum
 * @since             1.0.0
 * @package           Advertisement_Space
 *
 * @wordpress-plugin
 * Plugin Name:       Advertisement Space
 * Plugin URI:        advertisement-space
 * Description:       Plugin To place Advertisement in your wordpress
 * Version:           1.0.0
 * Author:            intlum
 * Author URI:        https://www.intlum.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advertisement-space
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ADVERTISEMENT_SPACE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-advertisement-space-activator.php
 */
function activate_advertisement_space() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advertisement-space-activator.php';
	Advertisement_Space_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-advertisement-space-deactivator.php
 */
function deactivate_advertisement_space() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-advertisement-space-deactivator.php';
	Advertisement_Space_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_advertisement_space' );
register_deactivation_hook( __FILE__, 'deactivate_advertisement_space' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-advertisement-space.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_advertisement_space() {

	$plugin = new Advertisement_Space();
	$plugin->run();

}
run_advertisement_space();
