<?php
namespace TenUp\A1D_Northeastern_CPT\Core;

/*
 * Include admin functions
 */

include_once( __DIR__ . '/admin.php' );

/*
 * Include custom post type functions
 */

include_once( __DIR__. '/custom-post-type.php' );

/**
 * Default setup routine
 *
 * @uses add_action()
 * @uses do_action()
 *
 * @return void
 */
function setup() {
	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'i18n' ) );
	add_action( 'init', $n( 'init' ) );
  add_action( 'init', $n( 'a1dnecpt_register_custom_post' ) );
  add_action( 'init', $n( 'a1dnecpt_create_events_taxonomy' ) );
  add_action( 'admin_menu', $n( 'register_a1dnecpt_admin' ) );
  add_action( 'admin_menu', $n( 'a1dnecpt_settings_init' ) );

	do_action( 'a1dnecpt_loaded' );
}

/**
 * Registers the default textdomain.
 *
 * @uses apply_filters()
 * @uses get_locale()
 * @uses load_textdomain()
 * @uses load_plugin_textdomain()
 * @uses plugin_basename()
 *
 * @return void
 */
function i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'a1dnecpt' );
	load_textdomain( 'a1dnecpt', WP_LANG_DIR . '/a1dnecpt/a1dnecpt-' . $locale . '.mo' );
	load_plugin_textdomain( 'a1dnecpt', false, plugin_basename( A1DNECPT_PATH ) . '/languages/' );
}

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @uses do_action()
 *
 * @return void
 */
function init() {
	do_action( 'a1dnecpt_init' );
}

/**
 * Activate the plugin
 *
 * @uses init()
 * @uses flush_rewrite_rules()
 *
 * @return void
 */
function activate() {
	// First load the init scripts in case any rewrite functionality is being loaded
	init();
	flush_rewrite_rules();
}

/**
 * Deactivate the plugin
 *
 * Uninstall routines should be in uninstall.php
 *
 * @return void
 */
function deactivate() {

}
