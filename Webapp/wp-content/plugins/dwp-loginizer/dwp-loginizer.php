<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              ''
 * @since             1.0.0
 * @package           Dwp_Loginizer
 *
 * @wordpress-plugin
 * Plugin Name:       DWP Loginizer
 * Plugin URI:        ''
 * Description:       DWP Loginizer allows you to customize the default wordpress login page
 * Version:           1.0.0
 * Author:            Demos Palana
 * Author URI:        ''
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dwp-loginizer
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dwp-loginizer-activator.php
 */
function activate_dwp_loginizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dwp-loginizer-activator.php';
	Dwp_Loginizer_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dwp-loginizer-deactivator.php
 */
function deactivate_dwp_loginizer() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dwp-loginizer-deactivator.php';
	Dwp_Loginizer_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dwp_loginizer' );
register_deactivation_hook( __FILE__, 'deactivate_dwp_loginizer' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dwp-loginizer.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dwp_loginizer() {

	$plugin = new Dwp_Loginizer();
	$plugin->run();

}
run_dwp_loginizer();