<?php

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/**
 * Category Template
 *
 *
 * @file           taxonomy-topic.php
 * @package        Ubuntu blog theme 
 * @author         Canonical Web Team 
 * @copyright      2012 Canonical Ltd
 * @license
 * @version        Release: 1.0
 * @filesource     wp-content/themes/insights-theme/category.php
 * @link           http://codex.wordpress.org/Theme_Development#Archive_.28category.php.29
 * @since          available since Release 1.0
 */
 
 /* Template Name: Topics */
?>

<?php get_header(); 
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Needed for pagination
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	$tax = get_query_var( 'taxonomy' );
	$post = $wp_query->get_queried_object();
	$current_tax = basename(get_permalink());
?>
	
<?php
/* ============== pull in business card ============== */
  wp_reset_query();
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Needed for pagination
	$query = new WP_Query( array(
	  'posts_per_page'  => 1,
    'topic'           => $current_tax,
    'post_type'       => 'productbusinesscard',
    'post_status'     => 'publish',
    'post__not_in'    => $do_not_duplicate,
    'paged'           => $paged
	));

    if ($query->have_posts()) :
	while( $query->have_posts() ): 
	$query->the_post(); 
	$do_not_duplicate[] = $post->ID ?>
    <div class="row glossary-box glossary-box-<?php echo strtolower(wp_title( '', false, 'right' )); ?> no-border">
        <div class="inner-wrapper">
            <div id="rtp-banner" class="six-col">
                <h1><?php wp_title(''); ?></h1>
                <div id="rtp-banner"><?php the_content(); ?></div>
            </div>
            <div class="last-col">
            </div>
        </div>
    </div>

<?php endwhile; ?>

<?php endif; // if have_posts ends 
/* ============== end pull in business card ============== */ ?>

<?php 
/* ============== pull in featured ============== */
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Needed for pagination
	$query = new WP_Query( array(
	  'posts_per_page'  => 1,
    'topic'           => $current_tax,
    'meta_key'        => 'dbt_checkbox_featured',
    'post_type'       => 'post',
    'paged'           => $paged
	));
?>
<div class="row">
<div class="inner-wrapper">
<div class="eight-col no-margin-bottom">
<?php if ($query->have_posts()) : ?>
    <div class="featured box box-highlight eight-col">
        <h2>Featured</h2>
        <?php 
        	while( $query->have_posts() ): 
        	$query->the_post(); 
        	$do_not_duplicate[] = $post->ID ?>
            <?php get_template_part("content_home_featured"); ?>	
        <?php endwhile; ?>
    </div><!-- /.box -->
<?php else : ?>	
<?php 
// The Query
	$new_query = new WP_Query( array(
	  'posts_per_page'  => 1,
    'topic'           => $current_tax,
    'post_type'       => 'post',
    'paged'           => $paged
	));
?>
<?php while( $new_query->have_posts() ): $new_query->the_post(); ?>
	<?php if ('event' == get_post_type()) : ?>
		<?php get_template_part("includes/_event_meta"); ?>
	<?php else : ?>
		<?php get_template_part("content_home"); ?>	
	<?php endif ; ?>
<?php endwhile; wp_reset_postdata(); ?>
<?php endif; // if have_posts ends 
/* ============== end pull in featured ============== */
?>

<?php
/* ============== pull in latest articles ============== */
wp_reset_query();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Needed for pagination
$current_tax = basename(get_permalink());
$exclude_cat ='downloads';
$category = get_category_by_slug($exclude_cat);
$query = new WP_Query( array(
	'posts_per_page'  => 4,
	'topic'           => $current_tax,
	'cat'             => -$category->cat_ID,
  'post_type'       => 'post',
  'post__not_in'    => $do_not_duplicate,
	'paged'           => $paged
));
?>
 
<?php if ($query->have_posts()) : ?>
<div class="box box-highlight eight-col clear">
	<h2>Latest in <?php echo wp_title( '', false, 'right' ); ?><!--<span><?php echo strtolower(wp_title( '', false, 'right' )); ?></span> news --></h2>
	<ul class="no-bullets">
	<?php 
		while( $query->have_posts() ): 
		$query->the_post(); 
		$do_not_duplicate[] = $post->ID ?>
        <li class="eight-col"><?php get_template_part("content_home"); ?>	</li>
	<?php endwhile; ?>
	</ul>
</div><!-- /.eight-col -->
<?php endif; // if have_posts ends 

/* ============== end pull in latest articles ============== */
?>

<?php
/* ============== pull in most viewed articles ============== */
  wp_reset_query();
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Needed for pagination
	$query = new WP_Query( array(
	  'posts_per_page'  => 2,
    'topic'           => $current_tax,
    'post_type'       => 'post',
    'post__not_in'    => $do_not_duplicate,
    'meta_key'        => 'post_views_count',
    'orderby'         => 'meta_value_num',
    'paged'           => $paged
	));
?>

<?php if ($query->have_posts()) : ?>
<div class="box box-highlight  eight-col">
	<h2>Most viewed in <span><?php echo strtolower(wp_title( '', false, 'right' )); ?></span></h2>
	<ul class="no-bullets">
	<?php 
		while( $query->have_posts() ): 
		$query->the_post(); 
		$do_not_duplicate[] = $post->ID ?>
        <li class="eight-col"><?php get_template_part("content_home"); ?>	</li>
	<?php endwhile; ?>
	</ul>
</div><!-- /.box -->
<?php endif; // if have_posts ends 
/* ============== end pull in most viewed articles ============== */
?>

<?php /* ============== pull in latest events ============== */
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Needed for pagination
$exclude_cat = 'downloads';
$category = get_category_by_slug($exclude_cat);
$query = new WP_Query( array(
	'topic'           => $current_tax,
  'order'           => 'ASC',
  'orderby'         => 'date',
  'paged'           => $paged,
  'post__not_in'    => $do_not_duplicate,
  'post_type'       => 'event',
  'meta_key'        => '_end_eventtimestamp',
  'meta_value'      => $current_timestamp,
	'orderby'         => 'meta_value',
  'posts_per_page'  => 9999,
  'meta_compare'    => '>'
));
?><?php if ( $query->have_posts() ) : ?>

<div class="eight-col box box-highlight no-margin-bottom">
    <h2>Upcoming events</h2>
    <ul class="no-bullets">
        <?php
            while( $query->have_posts() ):
            $query->the_post();
            $do_not_duplicate[] = $post->ID ;
                // get time gubbin's
                $current_time = current_time('mysql'); 
                list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = split( '([^0-9])', $current_time );
                $end_month = get_post_meta( $post->ID, '_end_month', true );
                $end_day = get_post_meta( $post->ID, '_end_day', true );
                $end_year = get_post_meta( $post->ID, '_end_year', true );
                $eventend = $end_year . $end_month . $end_day;
                $current_timestamp = $today_year . $today_month . $today_day;
            ?>
            <?php if($eventend >= $current_timestamp) : ?>
        <li><?php get_template_part("/includes/_event_meta"); ?></li><?php endif; ?><?php endwhile; ?>
    </ul>
</div><!-- /.eight-col -->
<?php endif; // if have_posts ends
 wp_reset_query();
/* ============== end pull in latest events ============== */ ?>
</div><!-- /.eight-col -->

<aside id="sidebar" class="four-col last-col">
<?php
$myTopics = array("phone","tablet","internet-of-things");
if( !$current_tax == $myTopics ) :
/* ============== pull in topic specific cta ============== */
  wp_reset_query();
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Needed for pagination
	$query = new WP_Query( array(
	  'posts_per_page'  => 2,
    'topic'           => $current_tax,
    'post_type'       => 'topiccta',
    'post__not_in'    => $do_not_duplicate,
    'paged'           => $paged
	));
?>
<?php if ($query->have_posts()) : ?>
		<?php 
			while( $query->have_posts() ): 
			$query->the_post(); 
			$do_not_duplicate[] = $post->ID;
			$slug = basename(get_permalink()); ?>					
	<div class="box box-highlight box-<?php echo strtolower(wp_title( '', false, 'right' )); ?> box-<?php echo $slug; ?> contextual-footer four-col">
        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>
	</div><!-- /.box -->
		<?php endwhile; ?>
<?php endif; // if have_posts ends 
wp_reset_query(); 
/* ============== end pull in topic specific cta ============== */ 
endif; ?>

<?php get_template_part("includes/_digest"); ?>

    <div class="box box-highlight four-col">
    	<h2>All <?php echo strtolower(wp_title( '', false, 'right' )); ?> resources</h2>
    	<ul class="no-bullets no-margin-bottom">
    	<?php
    		// get all the categories from the database
    		$cats = get_categories(); 
    		// loop through the categries
    			foreach ($cats as $cat) :
    				// setup the cateogory ID
    				$cat_id= $cat->term_id;
    				// create a custom wordpress query
    				query_posts("topic=$current_tax&cat=$cat_id&posts_per_page=1");
    				// start the wordpress loop!
    		      if (have_posts()) : while (have_posts()) : the_post(); 
    						echo "<li><a href='/topic/" . $current_tax . "?cat=".$cat_id."'>".$cat->name." (" .$wp_query->found_posts . ")</a></li>";
    		      ?>
    					<?php endwhile; endif; // done our wordpress loop. Will start again for each category ?>
    			<?php endforeach; // done the foreach statement ?>
    	</ul>
    </div><!-- /.box -->
<?php /* ============== end links to all topics ============== */ ?>
</aside>

</div><!-- /.inner-wrapper -->
</div><!-- /.row -->
<?php get_footer(); ?>