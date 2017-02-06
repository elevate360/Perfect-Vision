<?php
/**
 * Template Name: Products Page
 */
$ycc_theme = ycc_theme::getInstance();
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
			while ( have_posts() ) : the_post();

				//render the ACC page templates
				$ycc_theme->display_page_templates($post->ID);
				
				//get_template_part( 'template-parts/content', 'page' );
				
				//Display association logos
				$ycc_theme->el_display_association_logos();

				$ycc_theme->get_products_object()->display_product_listing_top_level_list(); 
				

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
