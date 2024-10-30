<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://bookacamp.de/en/integrations
 * @since             1.0.0
 * @package           Wp_Bookacamp
 *
 * @wordpress-plugin
 * Plugin Name:       Bookacamp
 * Plugin URI:        https://bookacamp.de/en/integrations
 * Description:       Bookacamp.de for Wordpress - a plugin for easy integration of the booking system in Wordpress
 * Version:           1.0.0
 * Author:            Mathias Methner
 * Text Domain:       wp-bookacamp
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
define( 'WP_BOOKACAMP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-bookacamp-activator.php
 */
function activate_wp_bookacamp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-bookacamp-activator.php';
	Wp_Bookacamp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-bookacamp-deactivator.php
 */
function deactivate_wp_bookacamp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-bookacamp-deactivator.php';
	Wp_Bookacamp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_bookacamp' );
register_deactivation_hook( __FILE__, 'deactivate_wp_bookacamp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-bookacamp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_bookacamp() {

	$plugin = new Wp_Bookacamp();
	$plugin->run();

}

run_wp_bookacamp();
