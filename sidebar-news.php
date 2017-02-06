<?php
/**
 * Outputs the news sidebar, used on the blog index page
 */

if ( ! is_active_sidebar( 'sidebar-news' ) ) {
	return;
}
?>

<aside id="content-sidebar" class="widget-area el-col-small-12 el-col-medium-4" role="complementary">
	<?php dynamic_sidebar( 'sidebar-news' ); ?>
</aside><!-- #secondary -->
