<?php
use gh_connects\theme\Modules as Modules;
use gh_connects\theme\classes\Condenser as Condenser;

?>
<main id="primary" class="site-main home">
    <h1 class="hometitle"><?php echo get_the_title(get_the_ID()); ?></h1>
    <!-- Module Home Hero -->
    <div class="container">
        <div class="row">
            <div class="col-12 homecontent">
                <?php echo get_the_content(get_the_ID()); ?>
            </div>
        </div>
    </div>

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
                   # do_shortcode( '[zoom_api_link meeting_id="'.$this->zoomid.'" class="zoom-meeting-window" id="zoom-meeting-window" title="Your Page Title" countdown-title="Meeting starts in" width="" height=""]'); 
                    ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
        <?php 
    } else { 

      
        ?>
        <div id ="home-hero" class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="nextstream">Next Stream</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-2 author-wrap">
                    <div class="author-bg" style="background:url(<?php echo $img?>) center/cover;">

                    </div>
                </div>
                <div class="col-2 date-wrap">
                    <div class="date-bg">
                        <div class="datewrap">
                            <span class="month"><?php echo $this->startDate->format('M');?></span>
                            <span class="day"><?php echo $this->startDate->format('d');?></span>
                            <span class="time"><?php echo $this->startDate->format('h:i a');?></span>

                        </div>
                    </div>
                </div>
                <div class="col-8 infobody">
                    <div class="infowrap">
                        <h1 class="pagetitle frontpage"><?php echo $title ?></h1>
                        <div class="info-accordion"><span class="hamburger-expand"></span>
                            <div class="info-excerpt"><?php echo $excerpt ?></div>
                        </div>
                        <div class="info-row">
                            <!--<div class="author d-none d-md-block"><?php echo $authorname ?></div> -->
                            <div class="timeto d-none d-md-block">Livestream Starts in <?php
                                    echo '<span id="timewrap" data-seconds="'.$this->secs_diff.'" data-action="final_countdown">';
                                    echo ( $this->days2livestream ) ? '<span id="hero_closest_days">'.$this->days2livestream.'</span> Days, ' : '<span id="hero_closest_days"></span>';
                                    echo ( $this->hours2livestream ) ? '<span id="time2stream">'.$this->hours2livestream.':' : '<span id="time2stream">';
                                    echo ( $this->mins2livestream ) ? $this->mins2livestream.':' : '0:';
                                    echo $this->secs2livestream.'</span>';           
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end row -->     
        </div><!-- end container -->

        <div class="container">
            <div class="row">
        <?php
    }



