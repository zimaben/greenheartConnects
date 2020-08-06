<?php
namespace gh_connects\theme\classes;
use gh_connects\theme\Template as Template;

// fire in sequence
#\nv_dailyvibe\theme\modules\HeaderAvatar::run();

class HomeHero extends \GreenheartConnects {
    
    public function __construct( $userState ){
        $homehero = new Template('components/hero_section.php');
        $homehero->ID = $userState->ID;
        $homehero->nearest_stream_id = $userState->nearest_stream_id; 
        $homehero->days2livestream = $userState->days2livestream;              
        $homehero->hours2livestream = $userState->hours2livestream;
        $homehero->mins2livestream = $userState->mins2livestream;
        $homehero->secs2livestream = $userState->secs2livestream;
        $homehero->secs_diff = $userState->secs_diff;

        echo $homehero->render();
    }
};