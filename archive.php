<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ycc
 */

get_header(); ?>

	<div id="primary" class="content-area el-col-small-12">
		<main id="main" class="site-main news-listing" role="main">

		<?php
		if ( have_posts() ){

			echo '<div class="blog-card-listing el-row inner medium-row-of-three small-padding-top-bottom-medium medium-padding-top-bottom-large">';
				
				/* Start the Loop */
				while ( have_posts() ) : the_post();
					
					$html = $theme_base->get_single_post_card_html($post->ID);
					//get_template_part( 'template-parts/content','news-page');
					echo $html;
					
	
				endwhile;
			
	
				//navigation only on posts
				if(get_post_type($post) == 'post'){
					echo '<div class="el-row inner small-margin-top-bottom-medium">';
						echo '<div class="el-col-small-12">';
							the_posts_navigation();
						echo '</div>';
					echo '</div>';
				}
				
			echo '</div>';
		}
		else{
			get_template_part( 'template-parts/content', 'none' );
			
		}
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
