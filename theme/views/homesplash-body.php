<?php
    $stream_args = array(  
        'post_type' => 'streams',
        'post_status' => 'publish',
        'posts_per_page' => 5, 
        'orderby’ => 'title', 
        'order’ => 'ASC', 
    );

    $streamloop = new WP_Query( $args ); 

        $video_args = array(  
        'post_type' => 'videos',
        'post_status' => 'publish',
        'posts_per_page' => 3, 
        'orderby' => 'date',
        'order'   => 'DESC',
        'suppress_filters' => true,
    );

    $vidloop = new WP_Query( $args ); 

?>


<div class="container-fluid mt-5">
    <div class="row">
        <div class="col col-12">
            <div id="homesplashCarousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#homesplashCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#homesplashCarousel" data-slide-to="1"></li>
                    <li data-target="#homesplashCarousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div data-slidenum="0" class="carousel-item active">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col col-12 col-md-6 justify-content-center">
                                    <h2 class="text-center">Upcoming Stream:</h2>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-between">
                                <div class="col col-12 col-md-6 justify-content-center align-items-center">
                                    <img class="d-block m-30 w-60" src="holder.js/500x500?theme=vine" alt="First slide">
                                </div>
                                <div class="col col-12 col-md-6 justify-content-center align-items-center pr-5">
                                    <h2>Re-earth Initiative: making the climate movement accessible to all – Xiye Bastida & Joseph Wilkanowski</h2>
                                    <div class="d-flex flex-row-reverse">
                                        <div class="p-2">February 18, 2021</div>
                                    </div>
                                    <blockquote class="blockquote">
                                        <p class="mb-0">Xiye Bastida is a climate activist and one of the leader organizers for Fridays for Future youth climate strike movement.</p>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-slidenum="1" class="carousel-item">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col col-12 col-md-6 justify-content-center">
                                    <h2 class="text-center">Upcoming Stream:</h2>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-between">
                                <div class="col col-12 col-md-6 justify-content-center align-items-center">
                                    <img class="d-block m-30 w-60" src="holder.js/500x500?theme=vine" alt="First slide">
                                </div>
                                <div class="col col-12 col-md-6 justify-content-center align-items-center pr-5">
                                    <h2>The importance of serving your community – Rich Zacaroli & Ian Kelly</h2>
                                    <div class="d-flex flex-row-reverse">
                                        <div class="p-2">March 18, 2021</div>
                                    </div>
                                    <blockquote class="blockquote">
                                        <p class="mb-0">Ian Kelly is the Ambassador (ret.) in Residence at Northwestern University. Previously, he spent 33 years in the State Department as a Foreign Service officer.</p>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-slidenum="2" class="carousel-item">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col col-12 col-md-6 justify-content-center">
                                    <h2 class="text-center">Upcoming Stream:</h2>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-between">
                                <div class="col col-12 col-md-6 justify-content-center align-items-center">
                                    <img class="d-block m-30 w-60" src="holder.js/500x500?theme=vine" alt="First slide">
                                </div>
                                <div class="col col-12 col-md-6 justify-content-center align-items-center pr-5">
                                    <h2>Fair Trade in Action with Local Women’s Handicrafts sewing collective in Nepal.</h2>

                                    <div class="d-flex flex-row-reverse">
                                        <div class="p-2">January 21, 2021</div>
                                    </div>
                                    <blockquote class="blockquote">
                                        <p class="mb-0">Nasreen is a 20-something who doesn’t know her exact age. Girls’ births are not recorded in her village on the India-Nepal border, nor are girls allowed to go to school.</p>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
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
    <div class="row the-content">
        <div class="col-6">
            <ul class="checkmarklist">
			    <li><a href="#" onclick="loginModal(event);return false;" data-modal-target="why-join">Why Join Greenheart Connects?</a></li>
            </ul>
        </div>
        <div class="col-6">
        <div class="col-6">
            <ul class="checkmarklist">
                <li><a href="#" onclick="loginModal(event);return false;" data-modal-target="membership-levels">Membership Packages</a></li>
            </ul>
        </div>
        </div>
    </div>
    <div class="row d-flex justify-content-between mt-5">
        <div class="col col-4 col-sm-3 col-md-2">
            <h4>Previous</h4>
            <ul class="thumbnails left">
                <li class="homeslide_thumb span12">
                    <a data-slide="0" onclick="goToHomeSlide(event);" href="#" class="thumbnail">
                    <picture>
                        <!--<source srcset="holder.js/300x150?theme=vine" type="image/svg+xml"> -->
                        <img src="holder.js/300x150?theme=vine" class="h-50 img-fluid img-thumbnail" alt="thumb">
                        <p>Re-earth Initiative: making the climate movement accessible to all – Xiye Bastida & Joseph Wilkanowski</p>
                    </picture>
                    </a>
                </li>
                <li class="homeslide_thumb span12">
                    <a data-slide="1" onclick="goToHomeSlide(event);" href="#" class="thumbnail">
                    <picture>
                        <!--<source srcset="holder.js/300x150?theme=vine" type="image/svg+xml">-->
                        <img src="holder.js/300x150?theme=vine" class="h-50 img-fluid img-thumbnail" alt="thumb">
                        <p>The importance of serving your community – Rich Zacaroli & Ian Kelly</p>
                        
                    </picture>
                    </a>
                </li>  
                <li class="homeslide_thumb span12 active">
                    <a data-slide="2" onclick="goToHomeSlide(event);" href="#" class="thumbnail">
                    <picture>
                        <!--<source srcset="holder.js/300x150?theme=vine" type="image/svg+xml">-->
                        <img src="holder.js/300x150?theme=vine" class="h-50 img-fluid img-thumbnail" alt="thumb">
                        <p>Fair Trade in Action with Local Women’s Handicrafts sewing collective in Nepal</p>               
                    </picture>
                    </a>
                </li>             
            </ul>
        </div>
        <div class="col col-2">
            <h4>Next Episode</h4>
            <ul class="thumbnails right">
                <li class="homeslide_thumb span12">
                    <a data-slide="0" onclick="goToHomeSlide(event);" href="#" class="thumbnail">
                    <picture>
                        <!--<source srcset="holder.js/300x150?theme=vine" type="image/svg+xml">-->
                        <img src="holder.js/300x150?theme=vine" class="h-50 img-fluid img-thumbnail" alt="thumb">
                        <p>Re-earth Initiative: making the climate movement accessible to all – Xiye Bastida & Joseph Wilkanowski.</p>
                    </picture>
                    </a>
                </li>
                <li class="homeslide_thumb span12 active">
                    <a data-slide="1" onclick="goToHomeSlide(event);" href="#" class="thumbnail">
                    <picture>
                        <!--<source srcset="holder.js/300x150?theme=vine" type="image/svg+xml">-->
                        <img src="holder.js/300x150?theme=vine" class="h-50 img-fluid img-thumbnail" alt="thumb">
                        <p>The importance of serving your community – Rich Zacaroli & Ian Kelly</p>
                    </picture>
                    </a>
                </li>  
                <li class="homeslide_thumb span12">
                    <a data-slide="2" onclick="goToHomeSlide(event);" href="#" class="thumbnail">
                    <picture>
                        <!--<source srcset="holder.js/300x150?theme=vine" type="image/svg+xml">-->
                        <img src="holder.js/300x150?theme=vine" class="h-50 img-fluid img-thumbnail" alt="thumb">
                        <p>Fair Trade in Action with Local Women’s Handicrafts sewing collective in Nepal</p>
                        
                    </picture>
                    </a>
                </li>             
            </ul>
        </div>
</div>