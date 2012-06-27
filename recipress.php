<?php
/*
Plugin Name: ReciPress
Plugin URI: http://www.recipress.com
Description: Create recipes in your posts with a clean interface and layout that are easy to organize.
Version: 1.9.4
Author: Tammy Hart
Author URI: http://tammyhartdesigns.com
*/

/* 
Copyright (c) 2011, Tammy Hart 
 
This program is free software; you can redistribute it and/or 
modify it under the terms of the GNU General Public License 
as published by the Free Software Foundation; either version 2 
of the License, or (at your option) any later version. 
 
This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
GNU General Public License for more details. 
 
You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA. 
*/  

// Localization
load_plugin_textdomain( 'recipress', false, basename( dirname( __FILE__ ) ) . '/lang' );

// The full path to the plugin directory
define( 'RECIPRESS_DIR', WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) . '/' );
define( 'RECIPRESS_URL', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/' );

function get_recipress_url() { return RECIPRESS_URL; }

// Load plugin files
include_once(RECIPRESS_DIR.'php/functions.php');
include_once(RECIPRESS_DIR.'php/options.php');
include_once(RECIPRESS_DIR.'php/meta_box.php');
include_once(RECIPRESS_DIR.'php/taxonomies.php');
include_once(RECIPRESS_DIR.'php/output.php');
include_once(RECIPRESS_DIR.'php/widgets.php');

// Styles and Scripts
add_action('admin_enqueue_scripts', 'recipress_admin_enqueue');
function recipress_admin_enqueue() {
	wp_enqueue_script('recipress_back', RECIPRESS_URL.'js/back.js', array('jquery', 'jquery-ui-sortable'));
	wp_enqueue_style('recipress_back', RECIPRESS_URL.'css/back.css');
}
add_action('wp_enqueue_scripts', 'recipress_wp_enqueue');
function recipress_wp_enqueue() {
	wp_enqueue_script('jquery');
	wp_enqueue_style('recipress_front', RECIPRESS_URL.'css/front.css');
}

// Admin Head Script
add_action('admin_head', 'add_recipress_script_config');
function add_recipress_script_config() {
?>
    <script type="text/javascript" >
    // Function to add auto suggest
    function setSuggest(id) {
        jQuery('#' + id).suggest("<?php echo get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php?action=ajax-tag-search&tax=ingredient");
    }
	var pluginDir = '<?php echo get_recipress_url() ?>';
    </script>
	<!--[if lt IE 9]>
        <link rel="stylesheet" type="text/css" href="<?php echo get_recipress_url() ?>css/ie.css" />
	<![endif]-->
<?php
}

// Register taxonomies and insert terms on plugin activation
add_action('init', 'register_recipress_taxonomies');
register_activation_hook( __FILE__, 'activate_recipress_taxonomies' );

function activate_recipress_taxonomies() {
	// activate taxonomies
	register_recipress_taxonomies();
	// insert terms
	recipress_default_taxonomies();
	$GLOBALS['wp_rewrite']->flush_rules();
}
	
?>