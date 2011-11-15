<?
/*
Plugin Name: ReciPress
Plugin URI: http://www.recipress.com
Description: Create recipes in your posts with a clean interface and layout that are easy to organize.
Version: 1.0
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

// The full path to the plugin directory
define( 'RECIPRESS_DIR', WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) . '/' );
define( 'RECIPRESS_URL', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/' );

// Load plugin files
include_once(RECIPRESS_DIR.'php/functions.php');
include_once(RECIPRESS_DIR.'php/options.php');
include_once(RECIPRESS_DIR.'php/meta_box.php');
include_once(RECIPRESS_DIR.'php/taxonomies.php');
include_once(RECIPRESS_DIR.'php/output.php');

// Styles and Scripts
if (is_admin()) {
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script( 'suggest' );
	wp_enqueue_script('recipress_back', RECIPRESS_URL.'js/back.js');
	wp_enqueue_style('recipress_back', RECIPRESS_URL.'css/back.css');
}
else {
	wp_enqueue_style('recipress_front', RECIPRESS_URL.'css/front.css');
}

// Admin Head Script
add_action('admin_head', 'add_script_config');
function add_script_config() {
?>
    <script type="text/javascript" >
    // Function to add auto suggest
    function setSuggest(id) {
        jQuery('#' + id).suggest("<?= get_bloginfo('wpurl'); ?>/wp-admin/admin-ajax.php?action=ajax-tag-search&tax=ingredient");
    }
	var pluginDir = '<? echo RECIPRESS_URL ?>';
    </script>
<?
}

// Register taxonomies and insert terms on plugin activation
add_action('init', 'register_taxonomy_ingredient');
add_action('init', 'register_taxonomy_cuisine' );
add_action('init', 'register_taxonomy_course' );
add_action('init', 'register_taxonomy_skill_level' );

register_activation_hook( __FILE__, 'activate_recipress_taxonomies' );

function activate_recipress_taxonomies() {
	// activate taxonomies
	register_taxonomy_ingredient();
	register_taxonomy_cuisine();
	register_taxonomy_course();
	register_taxonomy_skill_level();
	// insert terms
	recipress_default_taxonomies();
	$GLOBALS['wp_rewrite']->flush_rules();
}

?>