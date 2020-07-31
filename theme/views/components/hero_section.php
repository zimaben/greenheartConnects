<main id="primary" class="site-main home">
    <!-- Module Home Hero -->
    <?php
    $title = get_the_title($this->nearest_stream_id);
    $img = wp_get_attachment_image_src( get_post_thumbnail_id( $this->nearest_stream_id),'large',false)[0];
    $start = new DateTime( get_post_meta( $this->nearest_stream_id , 'ghc_stream_start', true ));     
    $time_diff = $start->diff( new DateTime() );
                    /*  days = $time_diff->d
                        hours = $time_diff->h
                        mins = $time_diff->i
                        seconds = $time_diff->s
                    */
    $excerpt = get_the_excerpt( $this->nearest_stream_id );
    $authorname = get_post_meta( $this->nearest_stream_id, 'ghc_author_name', true ); 

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
                                echo ( $time_diff->d > 0) ? '<span id="hero_closest_days">'.$time_diff->d.' Days,</span>' : '<span id="hero_closest_days"></span>';
                                echo ( $time_diff->h > 0) ? '<span id="hero_closest_hours">'.$time_diff->h.' Hours,</span>' : '<span id="hero_closest_hours"></span>';
                                echo ( $time_diff->i > 0) ? '<span id="hero_closest_mins">'.$time_diff->i.' Minutes,</span>' : '<span id="hero_closest_mins"></span>';
                                echo ( $time_diff->s > 0) ? '<span id="hero_closest_seconds">'.$time_diff->h.' Seconds</span>' : '<span id="hero_closest_secons"></span>';                                
                            ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Home Hero -->

