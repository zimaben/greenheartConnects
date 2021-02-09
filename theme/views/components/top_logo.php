<header id="masthead" class="site-header">
	<span id="mobile-menu"></span>	
	<div class="site-branding">
		<a href="<?php echo \get_home_url().'/dashboard/'?>" class="custom-logo-link" rel="home" aria-current="page">
			<?php
				$custom_logo_id = get_theme_mod( 'custom_logo' );
				$logo= wp_get_attachment_image_src( $custom_logo_id , 'full' );
			?>
			<img src="<?php echo $logo[0] ?>" class="custom-logo" alt="Greenheart Connects" srcset="<?php echo implode(',' , $srcset)?>" />
		</a>
	</div><!-- .site-branding -->
