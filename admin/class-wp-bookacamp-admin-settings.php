<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bookacamp.de/
 * @since      1.0.0
 *
 * @package    Wp_Bookacamp
 * @subpackage Wp_Bookacamp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Bookacamp
 * @subpackage Wp_Bookacamp/admin
 * @author     Mathias Methner, Bookacamp <support@bookacamp.de>
 */
class Wp_Bookacamp_Admin_Settings {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the settings of the plugin
	 *
	 * @since    1.0.0
	 */
	public function register() {

		self::register_general();

	}


	/**
	 * Register the general settings of the plugin
	 *
	 * @since    1.0.0
	 */
	public function register_general() {

		add_settings_section(
			'wp-bookacamp-settings-general',
			__( 'Allgemein', 'wp-bookacamp' ),
			null,
			'wp-bookacamp'
		);
		add_settings_section(
			'wp-bookacamp-settings-auth',
			__( 'Zugangsdaten', 'wp-bookcamp' ),
			null,
			'wp-bookacamp'
		);

		add_settings_field(
			'wp-bookacamp_show_firststeps',
			__( 'Erste Schritte anzeigen', 'wp-bookacamp' ),
			[ __CLASS__, 'general_show_firststeps' ],
			'wp-bookacamp',
			'wp-bookacamp-settings-general'
		);
		add_settings_field(
			'wp-bookacamp_username',
			__( 'Benutzername', 'wp-bookacamp' ),
			[ __CLASS__, 'auth_username' ],
			'wp-bookacamp',
			'wp-bookacamp-settings-auth'
		);
		add_settings_field(
			'wp-bookacamp_password',
			__( 'Passwort', 'wp-bookacamp' ),
			[ __CLASS__, 'auth_password' ],
			'wp-bookacamp',
			'wp-bookacamp-settings-auth'
		);
		add_settings_field(
			'wp-bookacamp_skey',
			__( 'Sicherheitsschüssel', 'wp-bookacamp' ),
			[ __CLASS__, 'auth_skey' ],
			'wp-bookacamp',
			'wp-bookacamp-settings-auth'
		);

		register_setting(
			'wp-bookacamp-settings-general',
			'wp-bookacamp_show_firststeps',
			[ 'type' => 'boolean', 'default' => true ]
		);
		register_setting(
			'wp-bookacamp-settings-auth',
			'wp-bookacamp_username',
			[
				'type'              => 'string',
				'default'           => null,
				'show_in_rest'      => false,
				'sanitize_callback' => 'sanitize_text_field'
			]
		);
		register_setting(
			'wp-bookacamp-settings-auth',
			'wp-bookacamp_password',
			[
				'type'              => 'string',
				'default'           => null,
				'show_in_rest'      => false,
				'sanitize_callback' => 'sanitize_text_field'
			]
		);
		register_setting(
			'wp-bookacamp-settings-auth',
			'wp-bookacamp_skey',
			[
				'type'              => 'string',
				'default'           => null,
				'show_in_rest'      => false,
				'sanitize_callback' => 'sanitize_text_field'
			]
		);
	}

	/**
	 * Build the HTML code for the First steps checkbox
	 *
	 * @since    1.0.0
	 */
	public function general_show_firststeps() {

		$show_firststeps = get_option( 'wp-bookacamp_show_firststeps' ); ?>
        <input type="checkbox"
               id="wp-bookacamp_show_firststeps"
               name="wp-bookacamp_show_firststeps"
		       <? if ( $show_firststeps === true ): ?>checked<? endif; ?>/>
		<?php
	}

	/**
	 * Build the HTML code for the bookacamp SKey
	 *
	 * @since    1.0.0
	 */
	public function auth_skey() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
        <input type="text" id="wp-bookacamp_skey" name="wp-bookacamp_skey"
               value="<? echo esc_attr(get_option( 'wp-bookacamp_skey' )); ?>"
               class="wp-bookacamp w-300"/>
		<?php
	}

	/**
	 * Build the HTML code for the bookacamp username
	 *
	 * @since    1.0.0
	 */
	public function auth_username() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
        <input type="text" id="wp-bookacamp_username" name="wp-bookacamp_username"
               value="<? echo esc_attr(get_option( 'wp-bookacamp_username' )); ?>"
               class="wp-bookacamp w-300"/>
		<?php
	}

	/**
	 * Build the HTML code for the bookacamp password
	 *
	 * @since    1.0.0
	 */
	public function auth_password() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
        <input type="password"
               id="wp-bookacamp_password"
               name="wp-bookacamp_password"
               value="<? echo esc_attr(get_option( 'wp-bookacamp_password' )); ?>"
               class="wp-bookacamp w-300"/>
		<?php
	}
}