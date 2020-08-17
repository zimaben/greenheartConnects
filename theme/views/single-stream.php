<?php
use gh_connects\theme\Modules as Modules; 

Modules::open_page();
/*/-------------------------------------------------------------------------------------------------------
/*
/*/ Modules::top_logo();                      Modules::top_menu();           $userState = Modules::top_avatar();
/*
/*---------------------------------------------------------------------------------------------------------
/*
/*
/*/

if ( have_posts() ) : while ( have_posts() ) : the_post(); 
?>
<div id ="home-hero" class="container">
    <div class="row">
        <div class="col-12">
        <h1 class="pagetitle frontpage"><?php echo \get_the_title(\get_the_ID())?></h1>
        <button class="backhome" onclick="goHome(event);return false;"><span class="backarrow"></span>Back</button>
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
                    <div class="info-excerpt"><?php echo \get_the_excerpt(\get_the_ID()) ?></div>
                </div>
                <div class="info-row">
                    <div class="author"><?php echo \get_post_meta( \get_the_ID(), 'ghc_author_name', true ); ?></div>
                    <div class="timeto">Livestream Starts in <?php
                            $now = new \DateTime("now", new \DateTimeZone('America/Chicago'));
                            $start = new \DateTime( \get_post_meta( \get_the_ID(), 'ghc_stream_start', true ) );   
                            $secs_diff = date_timestamp_get($start) - date_timestamp_get($now);
                            $days = floor($secs_diff / 86400 );
                            $new_secs = Modules::return_remaining_seconds_days($secs_diff);
                            $hours = floor($new_secs / 3600);
                            $new_secs = Modules::return_remaining_seconds_hours($new_secs);
                            $mins = floor($new_secs / 60 );
                            $secs = Modules::return_remaining_seconds_mins($new_secs);
                            $zoomid = \get_post_meta( \get_the_ID(), 'ghc_zoom_meeting_id', true);

                            echo '<span id="timewrap" data-seconds="'.$secs_diff.'" data-action="final_countdown">';
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
            <?php 
            \do_shortcode( '[zoom_api_link meeting_id="'.$zoomid.'" class="zoom-meeting-window" id="zoom-meeting-window" title="Your Page Title" countdown-title="Meeting starts in" width="" height=""]'); 
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
/*                                  
/*                                  
/*
/*/ Modules::footer();              /*------------------------------------------------------------------------
/*
/*-----------------------------------------------------------------------------------------------------------*/
Modules::close_page();