<?php
namespace gh_connects\theme\classes;
use gh_connects\theme\Template as Template;

// fire in sequence
#\nv_dailyvibe\theme\modules\HeaderAvatar::run();

class Settings extends \GreenheartConnects {
    
    public function __construct( $userState ){
        $settings = new Template('components/settings.php');
        $settings->ID = $userState->ID;
        $settings->user_email = $userState->user_email;
        $settings->display_name = $userState->display_name;
        $settings->cn_status = $userState->cn_status;
        $settings->avatar_img_id = $userState->avatar_img_id;


        echo $settings->render();
    }
};




   