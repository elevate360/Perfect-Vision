<?php
/**
 * Outputs the contact sidebar, used on the contact template
 */

if ( ! is_active_sidebar( 'sidebar-contact' ) ) {
	return;
}
?>

<aside id="content-sidebar" class="widget-area el-col-small-12 el-col-medium-4" role="complementary">
	<?php dynamic_sidebar( 'sidebar-contact' ); ?>
</aside><!-- #secondary -->
