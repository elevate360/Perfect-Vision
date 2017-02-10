<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package ycc
 */

get_header(); ?>

	<div id="primary" class="content-area el-col-small-12">
		<main id="main" class="site-main" role="main">
			<div class="el-row inner small-padding-top-bottom-medium medium-padding-top-bottom-large">

				<section class="error-404 not-found">
					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'ycc' ); ?></h1>
					</header><!-- .page-header -->
	
					<div class="page-content">
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Please use the menu above to navigate through our site', 'ycc' ); ?></p>

					</div><!-- .page-content -->
				</section><!-- .error-404 -->
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
