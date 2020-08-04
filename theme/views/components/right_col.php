            <div class="col-lg-8 col-12 main-index">
                <h2>Latest Streams</h2>
                <div class="container">
                    <div class="row">
                    <?php                 
                    $args = array(  
                        'post_type' => 'videos',
                        'post_status' => 'publish',
                        'posts_per_page' => -1, 
                        'order' => 'ASC',
                    );
                    $loop = new \WP_Query( $args ); 
   
                    while ( $loop->have_posts() ) : $loop->the_post(); 
                        $imgurl = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID(),'medium',false))[0];
                        error_log($imgurl);
                        error_log( print_r( get_post_thumbnail_id( get_the_ID(),'medium',false), true));
                        ?>
                        <div class="item col-6">
                            <div class="cardwrap">
                                
                                <div data-link="<?php echo get_post_meta( get_the_ID(), 'ghc_video_path', true ) ?>" onclick="do_video_modal(event);" class="item-bg-img" style="background:url(<?php echo $imgurl ?>) center/cover;"></div>
                                <div><?php echo get_post_meta( get_the_ID(), 'ghc_video_type', true ); ?></div>
                            
                                <a href="<?php echo get_post_meta( get_the_ID(), 'ghc_video_path', true ) ?>">
                                    <h4 class="item-title"><?php echo get_the_title(get_the_ID()) ?></h4>
                                </a>
                                <div class="container nopad">
                                    <div class="row no-gutters">
                                        <div class="col-2">
                                            <img class="avatar small" src="<?php echo get_template_directory_uri()?>/library/dist/css/img/speaker-avatar.jpg)?>">
                                        </div>
                                        <div class="col-9 thumb-description">
                                            <?php echo get_the_excerpt( get_the_ID() ) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php                  
                    endwhile;
                    wp_reset_postdata(); 
                    ?>
                    
                    </div>
                </div><!-- END MAIN INDEX ROW -->
            </div><!-- END MAIN INDEX CONTAINER -->
        </div><!-- END MAIN INDEX -->
    </div><!--END COLUMN RIGHT CONTAINER MODULE --> 
</main><!-- #main -->
