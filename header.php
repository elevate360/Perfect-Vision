<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ycc
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
<?php
global $template;
//echo $template;
?>
<?php
$theme_base = theme_base::getInstance();

?>
</head>

<body <?php body_class(''); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ycc' ); ?></a>

	<?php
	//display the fixed social media bar on the right
	//$ycc_theme->el_display_fixed_social_bar();
	?>

	<!--Mobile Menu-->
	<!--<div class="mobile-background"></div>-->
	<div class="menu mobile-menu">
		<span class="menu-toggle"><span>Close</span><i class="icon fa fa-bars" aria-hidden="true"></i></span>
		<?php wp_nav_menu( array( 
			'theme_location' => 'mobile-menu', 
			'menu_id' 		=> 'mobile-menu', 
			'container' 	=> false,
			'link_after'	=> '<span class="submenu-toggle"><i class="fa fa-angle-down" aria-hidden="true"></i></span>'
			) 
		); ?>
	</div>

	<header id="masthead" class="site-header " role="banner">
		<div class="el-row inner small-padding-top-bottom-small">
			<div class="site-branding el-col-small-12 el-col-large-3 small-align-center large-align-left">
				<?php
				//Display theme logo
				if(get_theme_mod('el_logo')){
					echo '<div class="logo-wrap el-col-small-8 el-col-large-12 small-align-left">';
						echo '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
							do_action('el_display_theme_logo');
						echo '</a>';
					echo '</div>';
				}?>
				<!--Mobile Menu for small and medium-->
				<div class="toggle-wrap el-col-small-4 small-align-right">
					<div class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><i class="icon fa fa-bars" aria-hidden="true"></i></div>
				</div>
			</div><!-- .site-branding -->
			
			
			
			
			<!--NAV MENUS-->
			<nav id="site-navigation" class="el-col-small-12 el-col-large-9 small-align-center large-align-right" role="navigation">	
				
				<div class="menu main-menu">
					<?php wp_nav_menu( array( 'theme_location' => 'main-menu', 'menu_id' => 'main-nav', 'container' => false) ); ?>
				</div>	
				
			</nav><!-- #site-navigation -->
			
			
		</div>
	</header><!-- #masthead -->
	<?php
	
	
	//display partner images
	//do_action('el_display_association_logos');
	
	//display header CTA section
	
	$queried_object = get_queried_object();
	
	//display header banner section
	do_action('el_display_header_banner', $queried_object);
	
	do_action('el_display_header_cta', $queried_object);
	
	
	?>

	<div id="content" class="site-content">
		<div class="el-row">
