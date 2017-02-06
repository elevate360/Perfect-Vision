<?php
/**
 * Template Name: Contact Page
 */
$ycc_theme = ycc_theme::getInstance();
get_header(); ?>

	<?php
	//get contact sidebar
	get_sidebar('contact');
	?>
	
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
	<br />//Display embeded Google map
				echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3310.335872036314!2d151.03430231517223!3d-33.932488529798434!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b12beaf4f599e9f%3A0xcc2d399d501b5787!2sYCC+Poultry!5e0!3m2!1sen!2sau!4v1484889078921" width="1920" height="822" frameborder="0" style="border:0" allowfullscreen></iframe>';

<?php

get_footer();
