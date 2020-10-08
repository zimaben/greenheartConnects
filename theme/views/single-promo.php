<?php
use gh_connects\theme\Modules as Modules; 
use gh_connects\theme\classes\Condenser as Condenser;
use \gh_connects\admin\AuthNet as AuthNet;
require_once GreenheartConnects::get_plugin_path('theme/views/classes/condenser-class.php');

Modules::open_page_norobots();
/*/-------------------------------------------------------------------------------------------------------
/*
/*/ Modules::top_logo();                      Modules::top_menu();           $userState = Modules::top_avatar();
/*
/*---------------------------------------------------------------------------------------------------------
/*
/*
/*/
if($userState){
    if( \current_user_can('administrator') ){
        if ( have_posts() ) : while ( have_posts() ) : the_post(); 
        ?>
        <div id ="home-hero" class="container">
            <div class="row">
                <div class="col-12">
                    <?php
                        $promo_code = \get_post_meta( \get_the_ID(), 'ghc_stream_promo', true );
                        $promo_quantity = \get_post_meta( \get_the_ID(), 'ghc_stream_promocount',true );
                        $promo_quantity_max = \get_post_meta( \get_the_ID(), 'ghc_promo_quantity_max',true );
                        $promo_expiration = \get_post_meta( \get_the_ID(), 'ghc_promo_expiration',true );
                        $promo_active = \get_post_meta( \get_the_ID(), 'ghc_promo_active', true);
                        ?>
                        <br>
                        <h1><?php the_title(); ?></h1>
                        <h2>Code: <?php echo $promo_code ?></h2>
                        <h3>Notes - </h3>
                        <div class="entry">
                        <?php the_content(); ?>
                        </div>
                        <h3>Details - </h3>
                        <h5>Quantity Used: <?php echo $promo_quantity.' of '.$promo_quantity_max ?></h5>
                        <h5>Expiration: <?php echo $promo_expiration ?></h5>
                        <h5>Promo is <?php echo ($promo_active) ? 'active' : 'inactive' ?> </h5>
                        <h5>Users acquired from this code:</h5>
                        <?php 
                        global $wpdb;
                        //get all users with this promo code
                        $rows = $wpdb->get_results($wpdb->prepare( 
                            "SELECT * FROM wp_usermeta WHERE meta_key = %s AND meta_value = %s",
                            'cn_promo_code', $promo_code
                        ));
                        if( $rows ) {
                            error_log(print_r($rows,true));
                            echo '<table class="return_payment_info"><tr><th>'. count($rows) .' Total Users</th></tr>';
                            echo '<tr><td>User ID:</td><td>User Name:</td><td>Email:</td><td>Registration Date:</td></tr>';
                            foreach($rows as $row){
                                $user = \get_user_by( 'id', $row->user_id);
                                $html = '<tr>';
                                $html.= '<td>'.$user->ID.'</td>';
                                $html.= '<td>'.$user->display_name.'</td>';
                                $html.= '<td>'.$user->user_email.'</td>';
                                $html.= '<td>'.$user->user_registered.'</td>';
                                $html.= '</tr>';
                                echo $html;
                            }
                            echo  '</table>';
                        }

                        ?>
                </div>
            </div>
        </div>
        <?php
        endwhile;
    endif;
    \wp_reset_postdata(); 
    } else {
        ?>
        <h1>This section is for site administrators only</h1>
        <a href="<?php echo \home_url() ?>"><button class="backhome"><span class="backarrow"></span>Back</button></a>
        <?php
    } 
} else {
    ?>
    <h1>This section is for site administrators only</h1>
    <a href="<?php echo \wp_login_url() ?>"><button class="backhome"><span class="backarrow"></span>Login</button></a>
    <?php
}
/*                                  
/*                                  
/*
/*/ Modules::footer();              /*------------------------------------------------------------------------
/*
/*-----------------------------------------------------------------------------------------------------------*/
Modules::close_page();