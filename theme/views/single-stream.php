<?php
use gh_connects\theme\Modules as Modules; 
use gh_connects\theme\classes\Condenser as Condenser;
require_once GreenheartConnects::get_plugin_path('theme/views/classes/condenser-class.php');

Modules::open_page();
/*/-------------------------------------------------------------------------------------------------------
/*
/*/ Modules::top_logo();                      Modules::top_menu();           $userState = Modules::top_avatar();
/*
/*---------------------------------------------------------------------------------------------------------
/*
/*
/*/
if($userState){
    if($userState->cn_status === 'paid'){

if ( have_posts() ) : while ( have_posts() ) : the_post(); 
?>
<div id ="home-hero" class="container">
    <div class="row">
        <div class="col-12">
            <a href="<?php echo \home_url() ?>"><button class="backhome"><span class="backarrow"></span>Back</button></a>
        </div>
        <div class="col-12">
        <h1 class="pagetitle frontpage"><?php echo \get_the_title(\get_the_ID())?></h1>
        </div>
        <div class="col-12 pagecontent">
            <?php echo \get_the_content(\get_the_ID() ); ?>
        </div>
        <div class="col-2 author-wrap">
            <?php  $imgurl = \wp_get_attachment_image_src( \get_post_thumbnail_id( \get_the_ID(),'medium',false))[0];?>
            <div class="author-bg" style="background:url(<?php echo $imgurl ?>) center/cover;">

            </div>
        </div>
        <div class="col-2 date-wrap">
            <div class="date-bg">
                <div class="datewrap">
                    <?php  
                    $starttime = new DateTime( \get_post_meta( \get_the_ID() , 'ghc_stream_start', true ) );?>
                    <span class="month"><?php echo $starttime->format('M');?></span>
                    <span class="day"><?php echo $starttime->format('d');?></span>
                    <span class="time"><?php echo $starttime->format('h:i a');?></span>

                </div>
            </div>
        </div>
        <div class="col-8 infobody">
            <div class="infowrap">
                
                <div class="info-accordion"><span class="hamburger-expand"></span>
                    <div class="info-excerpt"><?php echo  \get_post_meta( \get_the_ID(), 'ghc_author_bio', true ) ?></div>
                </div>
                <div class="info-row">
                    <div class="author d-none d-md-block"><?php echo \get_post_meta( \get_the_ID(), 'ghc_author_name', true ); ?></div>
                    <div class="timeto d-none d-md-block">Livestream Starts in <?php
                            $now = new \DateTime("now", new \DateTimeZone('America/Chicago'));
                            $start = new \DateTime( \get_post_meta( \get_the_ID(), 'ghc_stream_start', true ), new \DateTimeZone('America/Chicago') ); 
                            $secs_diff = date_timestamp_get($start) - date_timestamp_get($now);
                            $days = floor($secs_diff / 86400 );
                            $new_secs = Modules::return_remaining_seconds_days($secs_diff);
                            $hours = floor($new_secs / 3600);
                            $new_secs = Modules::return_remaining_seconds_hours($new_secs);
                            $mins = floor($new_secs / 60 );
                            $secs = Modules::return_remaining_seconds_mins($new_secs);
                            $zoomid = \get_post_meta( \get_the_ID(), 'ghc_zoom_meeting_id', true);

                            echo '<span id="timewrap" data-seconds="'.$secs_diff.'" data-action="final_countdown_simple">';
                            echo ( $days ) ? '<span id="hero_closest_days">'.$days.'</span> Days, ' : '<span id="hero_closest_days"></span>';
                            echo ( $hours) ? '<span id="time2stream">'.$hours.':' : '<span id="time2stream">';
                            echo ( $mins ) ? $mins.':' : '0:';
                            echo $secs.'</span>';           
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end row -->     
</div><!-- end container -->



<div id ="stream-hero" class="container">
    <div class="row">
        <div class="col-12 hero-bg">
            <div class="info-row">
                    
                    <?php
                        echo \get_post_meta( \get_the_ID(), 'ghc_stream_embed_code', true );
                    ?>  
            </div>
            <?php 
            #\do_shortcode( '[zoom_api_link meeting_id="'.$zoomid.'" class="zoom-meeting-window" id="zoom-meeting-window" title="Your Page Title" countdown-title="Meeting starts in" width="" height=""]'); 
            ?>
        </div>
    </div>
</div>
<?php
               
    endwhile;
endif;
wp_reset_postdata(); 

?>


         
<?php
/*
/*
/*
/*
/*----------------------------------------------------------------------------------------------------------
/*
/*/ 
/*                                  
/*                                 
/*/ Modules::single_col($userState);  
/*                                  
/*                                 
/*/ Modules::single_comments($userState); 
### for livestream only we will include our real-time-chat application ###
$RTC_enabled = (int)\get_option( 'do_realtime_comments' );
if($RTC_enabled === 1){
?><!-- OUR RTC APP HERE -->
<script src="<?php echo GreenheartConnects::get_plugin_url('library/src/es6/global/realtimechat.js')?>"></script>
<script src="<?php echo GreenheartConnects::get_plugin_url('library/src/es6/global/ajaxcomment.js')?>"></script>
<?php
    } else { ?> <script src="<?php echo GreenheartConnects::get_plugin_url('library/src/es6/global/ajaxcomment.js')?>"></script> <?php }
 } else {
    //payment form here
    require_once GreenheartConnects::get_plugin_path('theme/views/components/hero_section-unpaid.php');
}
} else {
echo '<br><br><br>';
echo '<h5 style="text-align:center;">Please <a href="/login/?action="login">login</a> to see our archive of videos.</h5>';
}
/*                                  
/*                                  
/*
/*/ Modules::footer();              /*------------------------------------------------------------------------
/*
/*-----------------------------------------------------------------------------------------------------------*/
Modules::close_page();