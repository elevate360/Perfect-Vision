<?php
/**
 * Template Name: Wholesale Page
 */
$ycc_theme = ycc_theme::getInstance();
get_header(); ?>


	<div id="primary" class="content-area el-col-small-12 el-col-medium-8">
		<main id="main" class="site-main" role="main">
			
			<?php
			while ( have_posts() ) : the_post();

				//render the ACC page templates
				$ycc_theme->display_page_templates($post->ID);
				
				//get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php
	//get contact sidebar
	get_sidebar('wholesale');
	?>
	
	
<?php

get_footer();
