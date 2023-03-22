<?php
/*
Plugin Name: UCF Research Publication Plugin
Description: Provides a custom post type and taxonomies for describing UCF research publications.
Version: 1.1.1
Author: UCF Web Communications
License: GPL3
GitHub Plugin URI: UCF/UCF-Research-Publication-Plugin
*/

namespace UCFResearchPublication;
use UCFResearchPublication\PostTypes\ResearchPublication;

if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'UCF_RESEARCH_PUBLICATION__PLUGIN_FILE', __FILE__ );
define( 'UCF_RESEARCH_PUBLICATION__PLUGIN_PATH', dirname( __FILE__ ) );

require_once 'includes/class-post-type.php';
require_once 'includes/class-common.php';

/**
 * Function that runs when the plugin
 * is activated.
 * @author Jim Barnes
 * @since 1.0.0
 */
function activate() {
	$res = new ResearchPublication();
	$res->register();
	flush_rewrite_rules();
}

register_activation_hook( UCF_RESEARCH_PUBLICATION__PLUGIN_FILE, __NAMESPACE__ . '\activate' );

/**
 * Functions that runs when the plugin
 * is deactivated.
 * @author Jim Barnes
 * @since 1.0.0
 */
function deactivate() {
	flush_rewrite_rules();
}

register_deactivation_hook( UCF_RESEARCH_PUBLICATION__PLUGIN_FILE, __NAMESPACE__ . '\deactivate' );

/**
 * Function that runs on the
 * `plugins_loaded` hook.
 */
function init() {
	//Do some stuff here, for realz.
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\init', 10, 0 );
