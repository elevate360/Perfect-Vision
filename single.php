<?php
/**
 * The template for displaying a single post (news item)
 * 
 * Displayed slightly different than other pages (inner container)
 */
$theme_base = theme_base::getInstance();
get_header(); ?>
	<div id="primary" class="content-area el-row inner-small">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			//get_template_part( 'template-parts/content', 'post' );
			
			//render the ACC page templates
			$theme_base->display_page_templates($post->ID);

			the_post_navigation();

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
