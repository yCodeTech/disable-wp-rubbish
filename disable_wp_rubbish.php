<?php

/**
 * Disable WP Rubbish
 * 
 * In this file, we disable and remove any unnecessary and unwanted WP rubbish
 * from both admin and frontend.
 * 
 * @link https://github.com/yCodeTech/disable-wp-rubbish
 * @author yCodetech
 * @license MIT
 */



/**
 * Remove dns-prefetch Link from WordPress Head (Frontend)
 */
remove_action('wp_head', 'wp_resource_hints', 2);


/**
 * Remove default styles added automatically by WP and never used.
 */
add_action('wp_enqueue_scripts', 'remove_default_styles');
function remove_default_styles() {
	wp_dequeue_style('wp-block-library');
}

/**
 * Remove pages from the admin menu.
 */
add_action('admin_init', 'remove_wp_block_menu', 100);
function remove_wp_block_menu() {
	// WP Block Patterns
	remove_submenu_page('themes.php', 'edit.php?post_type=wp_block');
	remove_submenu_page('themes.php', 'site-editor.php?path=/patterns');
}


/**
 * Disable head rubbish
 */
add_action('init', 'disable_head_rubbish');
add_action('admin_init', 'disable_admin_head_rubbish');
function disable_head_rubbish() {
	/**
	 * Remove emojis
	 */
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('wp_print_styles', 'print_emoji_styles'); // deprecated
	remove_action('wp_enqueue_scripts', 'wp_enqueue_emoji_styles'); // new

	remove_filter('the_content_feed', 'wp_staticize_emoji');
	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);

	/**
	 * Remove other stuff
	 */
	remove_other_stuff();
}
function disable_admin_head_rubbish() {
	/**
	 * Remove emojis
	 */
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('admin_print_styles', 'print_emoji_styles'); // deprecated
	remove_action('admin_enqueue_scripts', 'wp_enqueue_emoji_styles'); // new

	add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
	add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);
	
	/**
	 * Remove unnecessary meta box widgets from the admin dashboard
	 */
	
	 /* Remove welcome panel */
	remove_action('welcome_panel', 'wp_welcome_panel');

	// Removes the 'WordPress News' widget
	remove_meta_box('dashboard_primary', 'dashboard', 'normal');
	// Removes the 'Quick Draft' widget
	remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
	// Removes the 'Activity' widget (since 3.8)
	remove_meta_box('dashboard_activity', 'dashboard', 'normal');


	/**
	 * Remove other stuff
	 */
	remove_other_stuff();
}
/**
 * Remove other stuff.
 *
 * For both the frontend and admin.
 */
function remove_other_stuff() {
	// The links to the extra feeds such as category feeds
	remove_action('wp_head', 'feed_links_extra', 3);
	// The links to the general feeds: Post and Comment Feed
	remove_action('wp_head', 'feed_links', 2);
	// The link to the Really Simple Discovery service endpoint, EditURI link
	remove_action('wp_head', 'rsd_link');
	// The link to the Windows Live Writer manifest file.
	remove_action('wp_head', 'wlwmanifest_link');
	// Index link
	remove_action('wp_head', 'index_rel_link');
	// Prev link
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	// Start link
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	// The relational links for the posts adjacent to the current post.
	remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
	// The XHTML generator that is generated on the wp_head hook, WP version
	remove_action('wp_head', 'wp_generator');
	// The REST API link tag
	remove_action('wp_head', 'rest_output_link_wp_head');
	// oEmbed discovery links.
	remove_action('wp_head', 'wp_oembed_add_discovery_links');
}

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce($plugins) {
	if (is_array($plugins)) {
		return array_diff($plugins, ['wpemoji']);
	}
	else {
		return [];
	}
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch($urls, $relation_type) {
	if ('dns-prefetch' === $relation_type) {
		/**
		 * This filter is documented in wp-includes/formatting.php
		 */
		$emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

		$urls = array_diff($urls, [$emoji_svg_url]);
	}
	return $urls;
}
