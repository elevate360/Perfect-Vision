<?php
/**
 * Front Page (Homepage template)
 */
$theme_base = theme_base::getInstance();
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				$theme_base->get_services_object()->display_service_listing_grid();

				$theme_base->display_page_templates($post->ID);

				// get_template_part( 'template-parts/content', 'page' );
// 
				// // If comments are open or we have at least one comment, load up the comment template.
				// if ( comments_open() || get_comments_number() ) :
					// comments_template();
				// endif;

			endwhile; // End of the loop.
			?>
			
			
			
			<div class="el-row small-margin-top-bottom-medium">
			<?php
			//display top level products
			//$ycc_theme->get_products_object()->display_product_listing_top_level_grid(); 
			?>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
