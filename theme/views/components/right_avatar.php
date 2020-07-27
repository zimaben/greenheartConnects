    <div id="header_avatar" class="avatar-right">
        <div class="avatar-display">
            <!-- image -->
            <?php $avatar_url = get_avatar_url( $this->user_email );
            if( $avatar_url ){
                echo '<img class="neonbadge avatar-img" src="'.$avatar_url.'">';
            } else {
                echo '<img class="neonbadge avatar-img" src="'.self::get_plugin_url("/library/dist/css/img/avatar-blank.svg").'">';
            }
            ?>
        </div>
        <div class="avatar-info">
            <span id="avatar-nav"><?php echo $this->user_nicename ?>
                <span id="avatar-right-dropdown-arrow" class="chevron chevron-down white" onclick="expandMenu(event)"></span>
            </span>
            <!-- menu -->
            <div class="avatar-right-menu-container">
                <ul data-clickable="dismiss" class="avatar-right-dropdown">
                    <li><a href="/me/">Edit Profile</a></li>
                    <li><a href="/login/?action=signout">Sign Out</a></li>
                </ul>
            </div>
        </div>
    </div>
</header><!-- #masthead -->