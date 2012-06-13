<?php
/*
	Plugin Name: List All Pages
	Plugin URI: http://www.thejakegroup.com/wordpress
	Description: This plugin is used to provide quick access to edit any page in a site from anywhere in the Wordpress Admin.  To control the display order of the pages, use the WordPress Menu Order field found on each page.
	Author: Tyler Bruffy
	Version: 1.1
	Author URI: http://www.thejakegroup.com/
*/

//	Builds the pages list
	function buildPageList()	{
		$site = get_bloginfo('wpurl');
		$pluginDir = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
?>
		<div id="tjg-show-all" class="wp-submenu">
<?php
		$allpages = get_pages('parent=0&sort_column=menu_order' ); 
	//	Gets the children of the page, if any.
		function getChildren($page)	{
			if ($page->post_parent)	{
				$ancestors=get_post_ancestors($page->ID);
				$root=count($ancestors)-1;
				$pageparent = $ancestors[$root];
			} else {
				$pageparent = $page->ID;
			}
			$haschildren = get_pages("child_of=$pageparent&sort_column=menu_order" );
			if ($haschildren) {

				foreach ( $haschildren as $childpage ) {
					$childtitle = $childpage->post_title;
					$childID = $childpage->ID;
					$childDepth = count(get_ancestors($childID, 'page' ));
					$site = get_bloginfo('wpurl');
					$childlink =  "$site/wp-admin/post.php?post=$childID&action=edit";
					echo	'<dd class="depth-' .$childDepth . '"> <a href="' . $childlink . '">' . $childtitle . '</a></dd>';
				}
			}	
		}
	// Foreach run on everypage, to get the ID and children	
		foreach ( $allpages as $page ) {
			$title = $page->post_title;
			$theID = $page->ID;
			$site = get_bloginfo('wpurl');
			$thelink =  "$site/wp-admin/post.php?post=$theID&action=edit";
			$theslug = $page->post_name;	
			echo	'<dl class="pagelist" id="' . $theslug . '-pagelist">'.
						'<dt> <a href="' . $thelink . '">' . $title . '</a></dt>';

			getChildren($page);
			echo	"</dl>";
		}
?>
		</div>
<?php
	}	
// Wordpress actions and functions to add the menu and styles/scripts.
	add_action('admin_head', 'buildPageList');
	add_action('admin_menu', 'tjg_add_button');
	add_action( 'admin_enqueue_scripts', 'tjg_list_all_scripts' );
	add_option("tjg_list_all_version", "1.0");

	function tjg_add_button() {
		add_menu_page(__('Show All Pages','menu-test'), __('Show All Pages','menu-test'), 'manage_options', 'show-all-button', 'mt_toplevel_page',  plugins_url('/icon.png', __FILE__), '-1');	
	}
	
	function tjg_list_all_scripts()	{
		wp_register_style( 'tjg_list_all_css', plugins_url('/allpages.css', __FILE__), false, '1.0.0' );
        wp_enqueue_style( 'tjg_list_all_css' );
		wp_enqueue_script( 'tjg_list_all_js', plugins_url('/allpages.js', __FILE__) );
	}

 
?>