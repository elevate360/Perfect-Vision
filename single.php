<?php
/**
 * The template for displaying a single post (news item)
 * 
 * Displayed slightly different than other pages (inner container)
 */
$theme_base = theme_base::getInstance();
get_header(); ?>
	<div id="primary" class="content-area el-row">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			//render the ACC page templates
			$theme_base->display_page_templates($post->ID);

			//navigation only on posts
			if(get_post_type($post) == 'post'){
				echo '<div class="el-row inner small-margin-top-bottom-medium">';
					echo '<div class="el-col-small-12">';
						the_post_navigation();
					echo '</div>';
				echo '</div>';
			}
			

			// If comments are open or we have at least one comment, load up the comment template.
			//if ( comments_open() || get_comments_number() ) :
			//	comments_template();
			//endif;

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
