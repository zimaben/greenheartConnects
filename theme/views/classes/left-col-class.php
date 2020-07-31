<?php
namespace gh_connects\theme\classes;
use gh_connects\theme\Template as Template;

// fire in sequence
#\nv_dailyvibe\theme\modules\HeaderAvatar::run();

class LeftCol extends \GreenheartConnects {
    
    public function __construct( $userState ){
        $leftcol = new Template('components/left_col.php');
        $leftcol->nearest_stream_id = $userState->nearest_stream_id; 

        echo $leftcol->render();
    }
};