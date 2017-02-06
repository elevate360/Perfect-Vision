<?php
/**
 * Outputs the products sidebar, used on various pages
 */

if ( ! is_active_sidebar( 'sidebar-products' ) ) {
	return;
}
?>

<aside id="content-sidebar" class="widget-area el-col-small-12 el-col-medium-4" role="complementary">
	<?php dynamic_sidebar( 'sidebar-products' ); ?>
</aside><!-- #secondary -->
