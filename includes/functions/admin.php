<?php
namespace TenUp\A1D_Northeastern_CPT\Core;

/*
 * Generate plugin options
 * This is a simple select list to enable to creation of the custom post type
 *
 * @uses register_setting
 * @uses add_settings_section
 * @uses add_settings_field 
 *
 * @return void
 */

function a1dnecpt_settings_init(){

  register_setting(
    'a1dnecpt_cpt_options',
    'a1dnecpt_cpt_options',
    __NAMESPACE__ . '\a1dnecpt_options_validation'
  );
  add_settings_section(
    'a1dnecpt_cpt_settings',
    'NE CPT Settings',
    __NAMESPACE__ . '\a1dnecpt_settings_info',
    'a1d-necpt'
  );
  add_settings_field(
    'a1dnecpt_enable_cpt',
    'Enable NE Events CPT',
    __NAMESPACE__ . '\a1dnecpt_settings_enable_cpt',
    'a1d-necpt',
    'a1dnecpt_cpt_settings'
  );
}

/*
 * Display inputs based on user preference 
 *
 * @uses get_option
 *
 * @returns string html
 */

function a1dnecpt_settings_enable_cpt() {

  $options = get_option( 'a1dnecpt_cpt_options' );
  
  if ( array_key_exists( 'a1dnecpt_cpt_enabled', $options ) ){
    $cpt_input = "<select name='a1dnecpt_cpt_options[a1dnecpt_cpt_enabled]' value='{$options['a1dnecpt_cpt_enabled']}'>";
    if ( 'enabled' ===  $options['a1dnecpt_cpt_enabled'] ){
      $cpt_input .= "<option selected value='enabled'>Enabled</option>";
      $cpt_input .= "<option value='disabled'>Disabled</option>";
    } else {
      $cpt_input .= "<option value='enabled'>Enabled</option>";
      $cpt_input .= "<option selected value='disabled'>Disabled</option>";
    }
    $cpt_input .= "</select>";
  } else {
    $cpt_input = "<select name='a1dnecpt_cpt_options[a1dnecpt_cpt_enabled]' value=''>";
    $cpt_input .= "<option value='enabled'>Enabled</option>";
    $cpt_input .= "<option value='disabled'>Disabled</option>";
    $cpt_input .= "</select>";
  }
    echo $cpt_input;
}

/*
 * Generate Admin dashboard
 *
 * @uses add_options_page()
 *
 * @return void
 */

function register_a1dnecpt_admin() {

    add_options_page( 'A1D Custom Post Type- NE CPT', 'NE CPT Options', 'manage_options', 'a1d-necpt', '\TenUp\A1D_Northeastern_CPT\Core\a1dnecpt_dashboard', 'dashicons-desktop', 62 );
}

/*
 * Build HTMl dashboard
 *
 * @return string html
 */

function a1dnecpt_dashboard() {

  $admin_template = A1DNECPT_INC . 'templates/admin.php';
  include_once( $admin_template );
}

/*
 * Validate user input of cpt options 
 *
 * @uses get_option()
 *
 * @return array validated input
 */

function a1dnecpt_options_validation( $input ) {

  $options = get_option( 'a1dnecpt_cpt_options' );
  $new_input = array();

  if ( $input['a1dnecpt_cpt_enabled'] ) {
    if ( "enabled" ===  $input['a1dnecpt_cpt_enabled'] || "disabled" === $input['a1dnecpt_cpt_enabled'] ) {

     $new_input['a1dnecpt_cpt_enabled'] = $input['a1dnecpt_cpt_enabled'];
    } else {
      $new_input['a1dnecpt_cpt_enabled'] = 'disabled';
    }
    return $new_input;
  }
}

/*
 * Display Plugin info and helper text
 * Registered custom post if user has enabled CPT functionality
 *
 * @uses get_option()
 *
 * @return string html
 */

function a1dnecpt_settings_info() {

  $options = get_option( 'a1dnecpt_cpt_options' );

  $info = '';
  $is_registered = false;
  if ( array_key_exists( 'a1dnecpt_cpt_enabled', $options ) ) {
    $is_registered = true;
    a1dnecpt_register_custom_post();
  };
}

