<nav id="site-navigation" class="main-navigation">

    <?php
    wp_nav_menu(
        array(
            'theme_location' => 'menu-1',
            'menu_id'        => 'primary-menu',
        )
    );
    ?>
</nav><!-- #site-navigation -->
<div id="mobile-menu-container">
	<span id="mobile-menu-close"></span>
    <div class="site-branding">
        <?php
        the_custom_logo();
        ?>
    </div><!-- .site-branding -->
    <nav class="navbar" role="navigation">
    <?php
    if( \is_user_logged_in() ) {
        \wp_nav_menu( array(
            'theme_location' => 'mobile-logged-in',
            'depth'           => 1, // 1 = no dropdowns, 2 = with dropdowns.
            'container'       => 'ul',
            'container_class' => 'horizontal-nav',
            'container_id'    => 'topnav-mobile',
            'menu_class'      => 'navbar-nav',
        ) );
    } else {
        \wp_nav_menu( array(
            'theme_location' => 'mobile-logged-out',
            'depth'           => 1, // 1 = no dropdowns, 2 = with dropdowns.
            'container'       => 'ul',
            'container_class' => 'horizontal-nav',
            'container_id'    => 'topnav-mobile',
            'menu_class'      => 'navbar-nav',
        ) );
    } 

    ?>
    </nav>
    <!--
    <div class="bottomlinks">
    <a href="/profile">Edit Profile</a>
    <br>
    <a href="/login/?action=logout">Log Out</a>
    </div>
    -->
</div>
