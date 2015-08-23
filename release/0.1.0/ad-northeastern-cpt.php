<?php
/**
 * Plugin Name: A1D Northeastern CPT
 * Plugin URI:  https://a1d.co/
 * Description: A simple plugin that creates a custom post type
 * Version:     0.1.0
 * Author:      Anthony DeLorenzo
 * Author URI:  https://a1d.co/
 * License:     GPLv2+
 * Text Domain: a1dnecpt
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2015 10up (email : info@10up.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using yo wp-make:plugin
 * Copyright (c) 2015 10up, LLC
 * https://github.com/10up/generator-wp-make
 */

// Useful global constants
define( 'A1DNECPT_VERSION', '0.1.0' );
define( 'A1DNECPT_URL',     plugin_dir_url( __FILE__ ) );
define( 'A1DNECPT_PATH',    dirname( __FILE__ ) . '/' );
define( 'A1DNECPT_INC',     A1DNECPT_PATH . 'includes/' );

// Include files
require_once A1DNECPT_INC . 'functions/core.php';


// Activation/Deactivation
register_activation_hook( __FILE__, '\TenUp\A1D_Northeastern_CPT\Core\activate' );
register_deactivation_hook( __FILE__, '\TenUp\A1D_Northeastern_CPT\Core\deactivate' );

// Bootstrap
TenUp\A1D_Northeastern_CPT\Core\setup();