<?php
/**
 * Add custom taxonomies
 *
 * Additional custom taxonomies can be defined here
 * http://codex.wordpress.org/Function_Reference/register_taxonomy
 */

function add_groups() {
	// Add new "topic" taxonomy to Posts
	register_taxonomy('group',array('whitepaper', 'ebook', 'article', 'post', 'downloads', 'video', 'attachment', 'casestudy', 'infographic', 'productbusinesscard', 'topiccta', 'webinar', 'event', 'case-study', 'presskits', 'promo'), array(
		'show_admin_column' => true,
		'labels' => array(
			'name' => _x( 'Group', 'taxonomy general name' ),
			'singular_name' => _x( 'Group', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search groups' ),
			'all_items' => __( 'All groups' ),
			'parent_item' => __( 'Parent groups' ),
			'parent_item_colon' => __( 'Parent asset type:' ),
			'edit_item' => __( 'Edit asset type' ),
			'update_item' => __( 'Update asset type' ),
			'add_new_item' => __( 'Add new group' ),
			'new_item_name' => __( 'New group name' ),
			'menu_name' => __( 'Groups' ),
			'menu_position' => 6,
      'public' => true, 
      'show_ui' => true,
      'hierarchical' => true,
      'query_var' => true,
      'rewrite'      => array('slug' => 'group', 'with_front' => false)
    ),		 
	));
}
add_action( 'init', 'add_groups', 0 );


function add_topics() {
	// Add new "topic" taxonomy to Posts
	register_taxonomy('topic',array('whitepaper', 'ebook', 'article', 'post', 'downloads', 'video', 'attachment', 'casestudy', 'infographic', 'productbusinesscard', 'topiccta', 'webinar', 'event', 'case-study', 'presskits', 'promo'), array(
		'hierarchical' => true,
		'show_admin_column' => true,
		'labels' => array(
			'name' => _x( 'Topic', 'taxonomy general name' ),
			'singular_name' => _x( 'Topic', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search topics' ),
			'all_items' => __( 'All topics' ),
			'parent_item' => __( 'Parent topics' ),
			'parent_item_colon' => __( 'Parent asset type:' ),
			'edit_item' => __( 'Edit asset type' ),
			'update_item' => __( 'Update asset type' ),
			'add_new_item' => __( 'Add new topic' ),
			'new_item_name' => __( 'New topic name' ),
			'menu_name' => __( 'Topics' ),
			'menu_position' => 6
		),
    // Control the slugs used for this taxonomy
    'rewrite' => array(
      'with_front' => false, // Don't display the category base before "/locations/"
      'hierarchical' => true, // This will allow URL's like "/locations/boston/cambridge/"
      'query_var' => '%topic%'
    ),		
	));
}
add_action( 'init', 'add_topics', 0 );

add_filter('post_link', 'topic_permalink', 10, 3);
add_filter('post_type_link', 'topic_permalink', 10, 3);
 
function topic_permalink($permalink, $post_id, $leavename) {
    if (strpos($permalink, '%topic%') === false) return $permalink;
     
        // Get post
        $post = get_post($post_id);
        if (!$post) return $permalink;
 
        // Get taxonomy terms
        $terms = wp_get_object_terms($post->ID, 'topic');   
        if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) $taxonomy_slug = $terms[0]->slug;
        else $taxonomy_slug = 'not-rated';
 
    return str_replace('%topic%', $taxonomy_slug, $permalink);
}   

function add_download_topics() {
	// Add new "topic" taxonomy to Posts
	register_taxonomy('download-type',array('post'), array(
		'hierarchical' => true,
		'show_admin_column' => true,
		'labels' => array(
			'name' => _x( 'Download category', 'taxonomy general name' ),
			'singular_name' => _x( 'Download type', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search download types' ),
			'all_items' => __( 'All download types' ),
			'parent_item' => __( 'Parent download types' ),
			'parent_item_colon' => __( 'Parent download type:' ),
			'edit_item' => __( 'Edit asset type' ),
			'update_item' => __( 'Update download type' ),
			'add_new_item' => __( 'Add new download type' ),
			'show_ui' => false,
			'new_item_name' => __( 'New download type name' ),
			'menu_name' => __( 'Download category' ),
			'menu_position' => 6
		),
		'rewrite' => array(
			'slug' => 'type', // This controls the base slug that will display before each term
			'with_front' => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	));
} 
add_action( 'init', 'add_download_topics', 0 );

add_action('init', function() {
  $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
  if ( $url_path === 'press-centre' ) {
     // load the file if exists
     $load = locate_template('page-press.php', true);
     if ($load) {
        exit(); // just exit if template was found and loaded
     }
  }
});


/*function add_audience() {
	// Add new "products-and-services" taxonomy to Posts
	register_taxonomy('audience',array('ebooks','post','checklists','event','webiinar','whitepaper','glossary', 'news', 'casestudy'), array(
		'hierarchical' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Audience', 'taxonomy general name' ),
			'singular_name' => _x( 'Audience', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search audiences' ),
			'all_items' => __( 'All audiences' ),
			'parent_item' => __( 'Parent audiences' ),
			'parent_item_colon' => __( 'Parent audience:' ),
			'edit_item' => __( 'Edit audience' ),
			'update_item' => __( 'Update audience' ),
			'add_new_item' => __( 'Add new audience' ),
			'new_item_name' => __( 'New audience name' ),
			'menu_name' => __( 'Audience' ),
			'menu_position' => 6
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'audiencess', // This controls the base slug that will display before each term
			'with_front' => false, // Don't display the category base before "/locations/"
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	));
}
add_action( 'init', 'add_audience', 0 ); */

?>