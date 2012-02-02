<?php
/*
Plugin Name: Get Options
Plugin URI: http://adresseContenantLesInfosSurVotrePlugin
Description: View all the WordPress Options, even the serialized ones !
Version: 1.1.6
Author: Gilles Dumas
Author URI: http://gillesdumas.com
*/
?>
<?php
/*  Copyright 2012  Gilles Dumas  (gillesdumas66@gmail.ocm)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php




/**
* Ajout d'une page dans le menu Admin
*/ 
function getoptions_init() {
	
	// Load the language pack
	load_plugin_textdomain('get-options', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
	// On ajoute la page.
	$page_title = __('All the WordPress options', 'get-options'); // <title>...</title>
	$menu_title = 'Get Options'; // Nom du plugin affiché
	$capability = 'administrator'; // (le menu sera visible par les admin)
	$menu_slug = 'all_options'; // ex : wp-admin/options-general.php?page=all_options
	$function  = 'all_options_page';
	add_submenu_page('tools.php', $page_title, $menu_title, $capability, $menu_slug, $function);
}
add_action('admin_menu', 'getoptions_init', 100);



function all_options_page() {
	require_once('inc/page-content.php');
}


?>
