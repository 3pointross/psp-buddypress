<?php
/**
 * Plugin Name: Project Panorama BuddyPress / BuddyBoss Integration
 * Plugin URI: http://www.projectpanorama.com
 * Description: Integrate Project Panorama with BuddyBoss
 * Version: 1.2.4
 * Author: SnapOrbital
 * Author URI: https://www.projectpanorama.com
 * License: GPL2
 * Text Domain: psp_projects
 */

do_action( 'pspbb_before_init' );

if( !function_exists('psp_get_option') || !function_exists('bp_is_active') ) {
     return; // fail silently
}

$defintions = array(
    'PSP_BB_VER'  =>  '1.2.4',
    'PSP_BB_PATH' =>  plugin_dir_path( __FILE__ ),
    'PSP_BB_URL'  =>  plugin_dir_url( __FILE__ )
);

foreach( $defintions as $definition => $value ) {
    if( !defined($definition) ) define( $definition, $value );
}

include_once( 'lib/init.php' );


add_action( 'plugins_loaded', 'psp_buddypress_localize_init' );
function psp_buddypress_localize_init() {
     load_plugin_textdomain( 'psp_projects', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
}


require 'vendor/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/3pointross/psp-buddypress/',
	__FILE__,
	'psp-buddypress'
);

do_action( 'pspbb_after_init' );
