<?php
/**
 * Template part for displaying single post excepts when posts are displayed on the blog page
 * (home.php) or when displayed on archive pages like tags, categories and search
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('small-padding-bottom-medium small-margin-bottom-medium'); ?>>
	<header class="entry-header">
		<?php
		the_title( '<h3 class="entry-title bold capitalize"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		?>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'ycc' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
			
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ycc' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<div class="entry-meta">
			<?php ycc_posted_on(); ?>	
		</div><!-- .entry-meta -->
		<?php ycc_entry_comments(); ?>
		<?php
			//view link
			echo '<a class="button secondary-button" href="' . get_permalink($post->ID) . '">View Full Article</a>';
		?>
		<?php ycc_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
