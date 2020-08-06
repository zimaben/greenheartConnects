<?php
use gh_connects\theme\Modules as Modules;
?>
<main id="primary" class="site-main home">
    <!-- Module Home Hero -->
    <?php
    $title = get_the_title($this->nearest_stream_id);
    $img = wp_get_attachment_image_src( get_post_thumbnail_id( $this->nearest_stream_id),'large',false)[0];
    $excerpt = \get_the_excerpt( $this->nearest_stream_id );
    $authorname = \get_post_meta( $this->nearest_stream_id, 'ghc_author_name', true ); 

    if ($secs_diff < 300){

        
        ?>
        <div id ="home-hero" class="container-fluid">
            <div class="row">
                <div class="hero-bg">
                    <?php 
                    error_log('DOING THE SHORTCODE');
                    do_shortcode( '[zoom_api_link meeting_id="87315124321" class="zoom-meeting-window" id="zoom-meeting-window" title="Your Page Title" countdown-title="Meeting starts in" width="" height=""]'); 
                    ?>
                </div>
            </div>
        </div>
        <?php 
    } else { 

      
        ?>
        <div id ="home-hero" class="container-fluid">
            <div class="row">
                <div class="hero-bg" style="background:url(<?php echo $img?>) center/cover;">
                    <div class="container hero-fg">
                        <h1 class="pagetitle frontpage white"><?php echo $title?></h1>
                        <div class="row justify-content-center justify-content-lg-start">
                            <div class="col-lg-7 col-8 hero-excerpt">
                                <h3 class="white"><?php echo $excerpt?></h3>
                            </div>
                        </div>
                        <div class="row h-100 justify-content-between hero-details">
                            <div class="col-3 my-auto"> 
                                <div class="speaker-avatar" style="background:url(<?php echo get_template_directory_uri()?>/library/dist/css/img/speaker-avatar.jpg) center/cover;">
                                </div>
                            </div>
                            <div class="col-3 col-md-4 my-auto">
                                <h3 class="speaker white">- <?php echo $authorname ?></h3>
                                <h5 class="speaker-blurb white">Speaker summary, blurb, or title</h5>
                            </div>
                            <div class="col-6 col-lg-6 justify-content-end my-auto hero-cta">
                                <button class="btn btn-large hero-cta">add to calendar</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                            
                                <h4 class="timeto white">Livestream Starts in <?php
                                    echo '<span id="timewrap" data-seconds="'.$this->secs_diff.'" data-action="final_countdown">';
                                    echo ( $this->days2livestream ) ? '<span id="hero_closest_days">'.$this->days2livestream.'</span> Days, ' : '<span id="hero_closest_days"></span>';
                                    echo ( $this->hours2livestream ) ? '<span id="hero_closest_hours">'.$this->hours2livestream.'</span> Hours, ' : '<span id="hero_closest_hours"></span>';
                                    echo ( $this->mins2livestream ) ? '<span id="hero_closest_mins">'.$this->mins2livestream.'</span> Minutes, ' : '<span id="hero_closest_mins"></span>';
                                    echo '<span id="hero_closest_seconds">'.$this->secs2livestream.'</span> Seconds'; 
                                    echo '</span>';                               
                                ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Home Hero -->
        <?php
    }



