<?php
/**
 * Template for displaying single products 
 */
$ycc_theme = ycc_theme::getInstance();
get_header(); ?>
	<?php 
	//get products sidebar
	get_sidebar('products');
	?>
	
	<div id="primary" class="content-area el-col-small-12 el-col-medium-8">
		<main id="main" class="site-main" role="main">

		<?php
		while ( have_posts() ) : the_post();

			$ycc_theme->get_products_object()->display_category_listing_for_product($post->ID);

			//Main page content
			echo '<div class="el-col-small-12">';
			get_template_part( 'template-parts/content', get_post_format() );
			echo '</div>';
			
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

get_footer();
