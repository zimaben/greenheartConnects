    <div id="header_avatar" class="avatar-right">
        <div class="avatar-display">
            <!-- image -->
        <?php
            if( $this->avatar_img_id){
                $img_url = wp_get_attachment_image_src( $this->avatar_img_id, 'neon_avatar_tiny', false );
                if( $img_url ) $img_url = $img_url[0];
        
            }
            if( $this->avatar_img_id == false || $img_url === false ){
                //can't get image, let's try gravatar
                $img_url = \get_avatar_url( $this->user_email );
            }
            if( $img_url ){
                echo '<img class="ghc avatar-img" src="'.$img_url.'">';
            } else {
                echo '<img class="neonbadge avatar-img" src="'.self::get_plugin_url("/library/dist/css/img/avatar-blank.svg").'">';
            }
        ?>
        </div>
        <div class="avatar-info">
            <span onclick="expandMenu(event)" id="avatar-nav"><?php echo $this->user_nicename ?>
                <span id="avatar-right-dropdown-arrow" class="chevron chevron-down white" onclick="expandMenu(event)"></span>
            </span>
            <!-- menu -->
            <div class="avatar-right-menu-container">
                <ul data-clickable="dismiss" id="mobile-menu-container" class="avatar-right-dropdown">
                    <li><a href="<?php echo \get_site_url()?>/profile/">Edit Profile</a></li>
                    <li><a href="<?php echo \get_site_url()?>/login/?action=signout">Sign Out</a></li>
                </ul>
            </div>
        </div>
    </div>
</header><!-- #masthead -->