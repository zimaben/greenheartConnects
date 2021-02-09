<?php
use gh_connects\theme\Modules as Modules;

#UTILITY FUNCTIONS FOR THIS PAGE
function check_for_video($postid = null){
    $video = false;
    if($postid){

        if(!$video) $video = get_post_meta( $postid, 'ghc_video_preview', true );
        if(!$video) $video = get_post_meta( $postid, 'ghc_stream_preview',true );
    }
    return $video;
}
function trim_trailing_markup($iframe){
    $endposition = strpos($iframe, '</iframe>');
    $trimmed = false;
    if($endposition){
        $trimmed = substr($iframe, 0, $endposition + 9);
    }
    return ($endposition) ? $trimmed : $iframe;
}
function return_vimeo($markup){
    $new_markup = false;
    $insertion = strpos( $markup, '<iframe ');
    if( $insertion !== false ){
        #so far so good now check that it's a vimeo embed
        $vimeocheck = strpos($markup, 'player.vimeo.com');
        if($vimeocheck !== false){
            #account for the character distance of <iframe (with space)
            $insertion = $insertion + 8;
            $mark_start = substr($markup, 0, $insertion);
            $mark_end = substr($markup, $insertion);
            $new_markup = trim_trailing_markup( $mark_start . 'class="vimeo" '.$mark_end );
        }

    }
    return ($new_markup) ? $new_markup : $markup;
}
function return_thumbnail($postid){
    $imgsrc = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ),'large_thumb_square', false)[0];
    if(!$imgsrc) $imgsrc = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ),'large',false)[0];
    if($imgsrc){
        $markup = '<img class="img-fluid w-100 m-30 w-60" src="'.$imgsrc.'" alt="Slide Image">';
    }
    return ($imgsrc) ? $markup : false;
}

#Arguments for Livestream query
#NOTE: if you uninstall NS Featured Posts remove these meta queries
$stream_args = array(  
    'post_type' => 'streams',
    'post_status' => 'publish',
    'posts_per_page' => 5, 
    'orderby' => 'meta_value', 
    'order' => 'ASC', 
    'meta_query' => array(
        array(
            'key' => 'ghc_stream_start',
            'value' => date('Y-m-d'),
            'compare' => '>=',
        ),
        array(
            'key'   => '_is_ns_featured_post',
            'value' => 'yes',
            'compare' => '='
        ),
    )

);

#Arguments for Video query
$video_args = array(  
    'post_type' => 'videos',
    'post_status' => 'publish',
    'posts_per_page' => 3, 
    'orderby' => 'post_date',
    'order'   => 'DESC',
    'suppress_filters' => true,
    'meta_query' => array(
        array(
            'key'   => '_is_ns_featured_post',
            'value' => 'yes',
            'compare' => '='
        ),
    )
);

$streamloop = get_posts( $stream_args );
$vidloop = get_posts( $video_args );

#Get First, Prev, & Next postIDs for Carousel Page
$firstvid = $streamloop[0]->ID;
$next = $streamloop[1]->ID;
$prev = $vidloop[0]->ID;

#Reverse order of the future posts to join chronologically with previous
$reverse_streams = (count($streamloop)) ? array_reverse($streamloop) : array();

$mainloop = array_merge((array)$reverse_streams, (array)$vidloop);

if(!empty($mainloop)) {
#get index of the active video in the combined object
$index_of_first = (count($streamloop)) ? count($streamloop) - 1 : count($mainloop) - 1;    


    ?>
    <div class="container-fluid">
        <div class="row welcome-module-bg">
            <div class="col col-12">
                <h1 class="mt-5 container"> <?php echo \get_the_title( $post->ID ) ?> </h1>
                <h4 class="pb-5 pt-5 container"><?php echo wp_filter_nohtml_kses( \get_the_content() ) ?></h4>
            </div>   
        </div>
        <div class="row my-5 pt-5">
            <div class="col col-12">
                <div id="homesplashCarousel" class="carousel slide carousel-fade pb-5" data-interval="10000"data-ride="carousel">
                    <ol class="carousel-indicators">
                    <?php 
                    $loopidx = 0;
                    foreach($mainloop as $control){
                        ?>
                        <li data-target="homesplashCarousel" data-slide-to="<?php echo $loopidx ?>" <?php echo ($loopidx === $index_of_first) ? 'class="active"' : ''; ?>>
                        </li>
                        <?php
                        $loopidx++;
                    }
                        ?>
                    </ol>
                    <div class="carousel-inner">
                    <?php 
                    $loopidx = 0;
                    foreach($mainloop as $this_slide){
                        ?>
                        <div data-slidenum="<?php echo $loopidx?>" class="carousel-item<?php echo ($loopidx ==$index_of_first) ? ' active' : ''?>">
                            <div class="h-100 container-fluid">
                                <!--
                                <div class="row">
                                    <div class="col col-12 col-md-6 justify-content-center">
                                        <h2 class="text-center p-0"><?php echo ($this_slide->post_type == 'streams') ? 'Upcoming Stream:' : 'Previous Video:'?>
                                        </h2>
                                    </div>
                                </div> -->
                                <div class="row h-100 d-flex justify-content-between">
                                    <div class="col col-12 col-md-6 d-flex flexcolumn justify-content-center align-items-center">
                                        <div class="videowrap">

                                            <?php 
                                            #embed logic here
                                            $is_preview = check_for_video($this_slide->ID);
                                            if($is_preview){
                                                $markup = return_vimeo($is_preview);
                                                echo $markup;
                                            } else {
                                                $img_markup = return_thumbnail($this_slide->ID);
                                                echo ($img_markup) ? $img_markup : '';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="ccol col-12 col-md-6 d-flex flex-column justify-content-center align-items-center pr-5-md">
                                        <h2><?php echo $this_slide->post_title ?></h2>
                                        <?php if($this_slide->post_type == 'streams') {
                                            $author = get_post_meta($this_slide->ID,'ghc_author_name', true);
                                        } else {
                                            $author = get_post_meta($this_slide->ID,'ghc_video_author', true);
                                        } 
                                        echo ($author) ? '<h5>'.$author.'</h5>' : '';
                                        ?>
                                        <div class="d-flex flex-row-reverse">
                                        <?php 
                                            if($this_slide->post_type == 'streams') {
                                                $this_date = 'Streams at: ';
                                                $this_date .= date("ga l, M j", strtotime( get_post_meta($this_slide->ID, 'ghc_stream_start', true) ));
                                            } else {
                                                $this_date = 'Aired on: ';
                                                $this_date .= date("l, M j", strtotime($this_slide->post_date));
                                            }
                                            ?>
                                            <div class="p-2"><?php echo $this_date ?></div>
                                        </div>
                                        <?php
                                            if($this_slide->post_type == 'streams') {
                                                $this_quote = get_post_meta($this_slide->ID,'ghc_author_bio', true);
                                                //$this_quote = get_the_excerpt( $this_slide->ID );
                                            } else {
                                                $this_quote = $this_slide->post_excerpt;
                                            }
                                            ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center w-100 p-5"><p class="mb-0"><?php echo Modules::maxWords($this_quote, 65, true) ?></p></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $loopidx++;
                    };
                ?>
                    </div>
                    <a class="carousel-control-prev" href="#homesplashCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#homesplashCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
        <!--
        <div class="row the-content m-5 p-5">
            <div class="col-12 col-md-6">
                <ul class="checkmarklist">
                    <li><a href="#" onclick="loginModal(event);return false;" data-modal-target="why-join">Why Join Greenheart Connects?</a></li>
                </ul>
            </div>
            <div class="col-12 col-md-6">
                <ul class="checkmarklist">
                    <li><a href="#" onclick="loginModal(event);return false;" data-modal-target="membership-levels">Membership Packages</a></li>
                </ul>
            </div>
        </div>
        <div class="row d-flex justify-content-between mt-5">
        <div class="col col-sm-5 col-md-4 col-lg-3 col-xl-2 col-6">
                <h4 class="text-center">Previous</h4>
                <ul class="thumbnails left">
                <?php 
                #Loop again for left/right thumbs
                $loopidx = 0;
                foreach($mainloop as $this_thumb){
                ?>
                    <li class="homeslide_thumb span12<?php echo ($this_thumb->ID == $prev) ? ' active' : '' ?>">
                        <a data-slide="<?php echo $loopidx ?>" onclick="goToHomeSlide(event);" href="#" class="thumbnail">
                        <picture>

                            <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $this_thumb->ID),'neon_avatar_large',false)[0]; ?>" class="img-fluid img-thumbnail" alt="thumbnail" />
                                <p><?php echo $this_thumb->post_title ?></p>
                        </picture>
                        </a>
                    </li>
                <?php
                $loopidx++;
                }
                ?> 
              
                </ul>
            </div>
            <div class="col col-sm-5 col-md-4 col-lg-3 col-xl-2 col-6">
                <h4 class="text-center">Next Episode</h4>
                <ul class="thumbnails right">
                <?php 
                    #Loop again for left/right thumbs
                    $loopidx = 0;
                    foreach($mainloop as $this_thumb){
                    ?>
                        <li class="homeslide_thumb span12<?php echo ($this_thumb->ID == $next) ? ' active' : '' ?>">
                            <a data-slide="<?php echo $loopidx ?>" onclick="goToHomeSlide(event);" href="#" class="thumbnail">
                            <picture>

                            <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $this_thumb->ID),'neon_avatar_large',false)[0]; ?>" class="img-fluid img-thumbnail" alt="thumbnail" />
                                <p><?php echo $this_thumb->post_title ?></p>
                            </picture>
                            </a>
                        </li>
                <?php
                $loopidx++;
                }
                ?>                
                </ul>
            </div>
        </div>
        -->
    </div>
    <?php
} else { error_log("No Videos or Livestreams for Splashpage"); }