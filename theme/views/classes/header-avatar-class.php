<?php
namespace gh_connects\theme\classes;
use gh_connects\theme\Template as Template;

// fire in sequence
#\nv_dailyvibe\theme\modules\HeaderAvatar::run();

class HeaderAvatar extends \GreenheartConnects {
    
    public function __construct( $wp_user ){
        $avatar = new Template('components/right_avatar.php');
        $avatar->ID = $wp_user->ID;
        $avatar->user_email = $wp_user->user_email;
        $avatar->user_nicename = $wp_user->user_nicename;

        echo $avatar->render();
    }
};