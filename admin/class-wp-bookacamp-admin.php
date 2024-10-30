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
class Wp_Bookacamp_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Bookacamp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bookacamp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-bookacamp-admin.css', [],
			$this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Bookacamp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Bookacamp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-bookacamp-admin.js', [ 'jquery' ],
			$this->version, false );

	}

	/**
	 * Register the admin page
	 *
	 * @since    1.0.0
	 */
	public function admin_menu_add() {

		$icon = base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 101.6 90.338"><path fill="#000000" d="M405.333 42.667H288V32c0-17.646-14.354-32-32-32s-32 14.354-32 32v10.667H106.667C83.146 42.667 64 61.802 64 85.333v256C64 364.865 83.146 384 106.667 384H224v106.667H96a10.66 10.66 0 00-10.667 10.667A10.66 10.66 0 0096 512h320a10.66 10.66 0 0010.667-10.667A10.66 10.66 0 00416 490.666H288V384h117.333C428.854 384 448 364.865 448 341.333v-256c0-23.531-19.146-42.666-42.667-42.666zm-22.5 271.521a10.678 10.678 0 01-9.5 5.813H288c-3.75 0-7.229-1.969-9.146-5.177L256 276.729l-22.854 38.094A10.657 10.657 0 01224 320h-85.333c-4 0-7.667-2.24-9.5-5.813a10.677 10.677 0 01.854-11.104l112.813-156.208-16.813-23.292c-3.458-4.781-2.375-11.448 2.396-14.896a10.652 10.652 0 0114.896 2.396L256 128.656l12.688-17.573c3.458-4.802 10.125-5.833 14.896-2.396 4.771 3.448 5.854 10.115 2.396 14.896l-16.813 23.292L381.98 303.083a10.68 10.68 0 01.853 11.105z" transform="matrix(.26458 0 0 .26458 -16.933 -11.273)"/></svg>' );

		add_menu_page(
			__( 'Bookacamp', 'wp-bookacamp' ),
			__( 'Bookacamp', 'wp-bookacamp' ),
			'manage_options',
			'wp-bookacamp',
			[ __CLASS__, 'admin_menu_item_main' ],
			'data:image/svg+xml;base64,' . $icon,
			4
		);
	}

	/**
	 * Build up the HTML code for the main admin page of the plugin
	 *
	 * @since    1.0.0
	 */
	public function admin_menu_item_main() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$show_firststeps = get_option( 'wp-bookacamp_show_firststeps' );

		$default_tab = $show_firststeps === true ? 'firststeps' : 'settings';
		$tab         = $_GET['tab'] ?? $default_tab;
		?>

        <div class="wrap">
            <h1>
                <img width="48" src="<? echo plugins_url( 'wp-bookacamp/admin/img/bookacamp.png' ); ?>">
            </h1>
            <nav class="nav-tab-wrapper">
				<? if ( $show_firststeps ) { ?>
                    <a href="?page=wp-bookacamp&tab=firststeps"
                       class="nav-tab <? if ( $tab === 'firststeps' ): ?>nav-tab-active<? endif; ?>"><? echo __( 'Erste Schritte',
							'wp_bookacamp' ); ?></a>
				<? } ?>
                <a href="?page=wp-bookacamp&tab=settings"
                   class="nav-tab <? if ( $tab === 'settings' ): ?>nav-tab-active<? endif; ?>"><? echo __( 'Einstellungen',
						'wp_bookacamp' ); ?></a>
                <a href="?page=wp-bookacamp&tab=about"
                   class="nav-tab <? if ( $tab === 'about' ): ?>nav-tab-active<? endif; ?>"><? echo __( 'Über',
						'wp_bookacamp' ); ?></a>
            </nav>
            <div class="tab-content">
                <div class="wrap">
					<?php
					switch ( $tab ) {
						case 'settings':
							self::admin_menu_item_settings();
							break;
						case 'about':
							self::admin_menu_item_about();
							break;
						case 'firststeps':
							self::admin_menu_item_firststeps();
							break;
						default:
							self::admin_menu_item_overview();
							break;
					} ?>
                </div>
            </div>
        </div>
		<?
	}

	/**
	 * Build up the HTML code for the settings admin page of the plugin
	 *
	 * @since    1.0.0
	 */
	public function admin_menu_item_settings() {
		?>
        <h2><? echo __( 'Einstellungen', 'wp-bookacamp' ); ?></h2>
        <form method="post" action="options.php">
			<?php settings_fields( 'wp-bookacamp-settings-general' ); ?>
			<?php do_settings_sections( 'wp-bookacamp' ); ?>
			<?php settings_fields( 'wp-bookacamp-settings-auth' ); ?>
			<?php submit_button(); ?>
        </form>
		<?php
	}

	/**
	 * Build up the HTML code for the about admin page of the plugin
	 *
	 * @since    1.0.0
	 */
	public function admin_menu_item_about() {
		$html = '<h2>' . __( 'Über WP-Bookacamp', 'wp-bookacamp' ) . '</h2>';
		$html .= '<p>WP-Bookacamp ist das WordPress-Plugin zur einfachen Integration des Buchungssystems <a href="https://bookacamp.de" target="_blank">bookacamp.de</a>.</p>';
		$html .= '<p>Version: ' . WP_BOOKACAMP_VERSION . '</p><br />';
		$html .= '<p>Wenn du Unterstützung bei der Betreuung deiner Wordpress Installation benötigst, empfehlen wir dir unseren Partner <a href="https://www.scharsich.dev/?pk_campaign=bookacamp&pk_source=wp-bookacamp" target="_blank">scharsich.dev</a>.';

		// no escaping
		echo $html;
	}

	/**
	 * Build up the HTML code for the first steps admin page of the plugin
	 *
	 * @since    1.0.0
	 */
	public function admin_menu_item_firststeps() {
		$html = '<h2>Das Bookacamp Wordpress Plugin!</h2>';

		$html .= '
		<p>
			Dieses Plugin unterstützt dich darin, die Camps &amp; Events von deinem <a href="https://bookacamp.de#pk_campaign=wp-wordpress&pk_source=wp-bookacamp" target="_blank">bookacamp.de</a> Account direkt in die Webseite zu integrieren.<br>
		</p>
		<p>
			Im ersten Schritt wechsel bitte in die Einstellungen oberhalb dieses Texts und hinterlege deine Sicherheitsschlüssel (skey = secure key).<br/>
			Dieser Sicherheitsschlüssel identifiziert deinen Bookacamp Account gegenüber diesem Wordpress Plugin und du findest ihn in deinem Bookacamp Account oben rechts mit Klick auf das Einstellungssymbol &gt; Konto.<br/>
			Auf der gleichen Seite wirst du nach deinen Benutzernamen und Passwort gefragt. Diese Daten erhältst du nach der erfolgreichen Zahlungen der Lizenzgebühr für dieses Plugin. Wende dich dazu gern an <a href="mailto:support@bookacamp.de?subject=WP-Bookacamp Lizenz">unseren Support</a>
		</p>
		<p>
			Falls du noch keinen Bookacamp Account besitzt, kannst du jederzeit auf <a href="https://bookacamp.de/de/register#pk_campaign=wp-wordpress&pk_source=wp-bookacamp" target="_blank">bookacamp.de</a> registrieren und ausprobieren. Selbstverständlich ist der Test des Systems kostenfrei.
		</p>';

		// no escaping
		echo $html;
	}

}
