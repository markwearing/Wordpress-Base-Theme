<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package One_starter
 * @since One_starter 1.1
 */

if ( ! function_exists( 'oneltd_get_subnav' ) ):
	/*
	 * AO: Subnav function which will aim to find the highest parent and provide a subnav from that
	 * or can be passed a nav menu name and it will display that... (With containing UL)
	 *
	 */
	function oneltd_get_subnav($nav_menu = "") 
	{
	
		if($nav_menu == "")
		{
			$ancestors = get_post_ancestors( $post ); 
			$top = get_post(end($ancestors), "OBJECT");
			
			echo "<ul>";
			wp_list_pages('title_li=&link_before=<span></span>&child_of='.$top->ID);
			if( $top->ID == 4116 ):
			?><li><a href="/contact-us"><span></span>Contact us</a></li>
			<?php
			endif;
			echo "</ul>";
		}
		else
		{
			//get the nav menu based on the nav menu name received!
			wp_nav_menu("menu=".$nav_menu."&container=");
		}
	}
endif; 


if ( ! function_exists( 'oneltd_get_breadcrumbs' ) ):
	/*
	 * AO: Breadcrumbs function to echo out crumbs in LI's (With #breadcrumbs containing UL).
	 *
	 */
	function oneltd_get_breadcrumbs()
	{
		global $post;
	
		$ancestors = get_post_ancestors( $post );
		$ancestors = array_reverse($ancestors);
		$ancestors[] = $post->ID;
		echo '<ul id="breadcrumbs">';
		
		if( is_home() ):
			echo '<li><a href="/blog">blog</a></li>';
		else:
		
			foreach($ancestors as $crumb)
			{
				echo '<li><a href="';
				echo get_permalink($crumb);
				echo '">';
				echo get_the_title($crumb);
				echo '</a></li>';
			}	
		endif;
		
		echo '</ul>';
	}

endif; 

if ( ! function_exists( 'oneltd_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since oneltd 1.0
 */
function oneltd_content_nav( $nav_id ) {
	global $wp_query;

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	?>
	<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'oneltd' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'oneltd' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'oneltd' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'oneltd' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // oneltd_content_nav

if ( ! function_exists( 'oneltd_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since oneltd 1.0
 */
function oneltd_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'oneltd' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'oneltd' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'oneltd' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'oneltd' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'oneltd' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( '(Edit)', 'oneltd' ), ' ' );
					?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for oneltd_comment()

if ( ! function_exists( 'oneltd_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since oneltd 1.0
 */
function oneltd_posted_on() {
	printf( __( 'Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'oneltd' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'oneltd' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category
 *
 * @since oneltd 1.0
 */
function oneltd_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so oneltd_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so oneltd_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in oneltd_categorized_blog
 *
 * @since oneltd 1.0
 */
function oneltd_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'oneltd_category_transient_flusher' );
add_action( 'save_post', 'oneltd_category_transient_flusher' );