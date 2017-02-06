<?php
/**
 * Displays latest blogs, blog index page
 */


get_header(); ?>

	<div id="primary" class="content-area el-col-small-12 el-col-medium-8">
		<main id="main" class="site-main news-listing" role="main">

		<?php
		if ( have_posts() ) :

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content','news-page');

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	<?php get_sidebar('news'); ?>
	
<?php

get_footer();
