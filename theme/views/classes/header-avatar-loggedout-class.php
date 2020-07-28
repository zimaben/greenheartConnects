<?php
namespace gh_connects\theme\classes;
use gh_connects\theme\Template as Template;

// fire in sequence
#\nv_dailyvibe\theme\modules\HeaderAvatar::run();

class HeaderAvatarLoggedOut extends \GreenheartConnects {
    
    public function __construct( $login_url ){
        $avatar = new Template('components/right_avatar_loggedout.php');
        $avatar->login_url = $login_url ;
        echo $avatar->render();
    }
};