<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ycc
 */
$theme_base = theme_base::getInstance();
?>
		</div>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		
		<?php
		//display footer cta 
		do_action('el_display_footer_cta');
		
		?>
		
		<div class="footer-widgets el-row inner small-margin-top-bottom-medium medium-margin-top-bottom-x-large">
			
				<?php
				//display logo
				if(get_theme_mod('el_footer_logo')){
					echo '<div class="el-col-small-12 el-col-medium-3">';
						do_action('el_display_footer_logo');
					echo '</div>';
				}
				 
				 
				//display footer widget zones
				do_action('el_display_footer_widgets'); 
				
				?>

		</div>
		
		<div class="footer-attribution el-col-small-12 small-padding-top-bottom-small">
			<div class="el-row inner">
				Designed by Elevate
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
