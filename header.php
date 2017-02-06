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
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NHM9BVH');</script>
<!-- End Google Tag Manager -->
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
<?php
global $template;
//echo $template;
?>
<?php
$ycc_theme = ycc_theme::getInstance();

?>
</head>

<body <?php body_class(''); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NHM9BVH"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ycc' ); ?></a>

	<?php
	//display the fixed social media bar on the right
	$ycc_theme->el_display_fixed_social_bar();
	?>

	<header id="masthead" class="site-header " role="banner">
		<div class="el-row inner">
			<div class="site-branding el-col-small-12">
				<?php
				/* */?>
			</div><!-- .site-branding -->
			
			<!--NAV MENUS-->
			<nav id="site-navigation" class="el-row small-margin-top-bottom-small" role="navigation">	
				<div class="main-navigation left el-col-small-5 small-align-left">
					<div class="menu main-menu">
						<?php wp_nav_menu( array( 'theme_location' => 'left-menu', 'menu_id' => 'left-menu', 'container' => false) ); ?>
					</div>	
				</div>	
				<?php
				//Display theme logo
				if(get_theme_mod('el_logo')){
					echo '<div class="logo-wrap el-col-small-6 el-col-large-2">';
						echo '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
							do_action('el_display_theme_logo');
						echo '</a>';
					echo '</div>';
				}
				
				
				?>
				<div class="main-navigation right el-col-small-5 small-align-right">
					<div class="menu main-menu">
						<?php wp_nav_menu( array( 'theme_location' => 'right-menu', 'menu_id' => 'right-menu', 'container' => false) ); ?>
					</div>
				</div>	
				
				<!--Mobile Menu for small and medium-->
				<div class="toggle-wrap el-col-small-6 small-align-right">
					<button class="menu-toggle button secondary-button" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'ycc' ); ?></button>
				</div>
				
				<!--Mobile Menu-->
				<div class="main-navigation mobile el-col-small-12 small-margin-top-small">
					<div class="menu mobile-menu">
						<?php wp_nav_menu( array( 'theme_location' => 'mobile-menu', 'menu_id' => 'mobile-menu', 'container' => false) ); ?>
					</div>
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

	<div id="content" class="site-content small-padding-top-bottom-small medium-padding-bottom-xx-large">
		<div class="el-row inner">
