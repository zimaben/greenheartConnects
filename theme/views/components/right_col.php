<?php
use gh_connects\theme\classes\Condenser as Condenser;
?>
<div class="col-lg-6 col-12 rightcol">
    <!-- Module Right Col -->
    <h3 class="previous">Previous Streams</h3>
    <div class="inner-wrap">
        <?php                 
            $args = array(  
                'post_type' => 'videos',
                'post_status' => 'publish',
                'posts_per_page' => -1, 
                'order' => 'ASC',
            );
            $loop = new \WP_Query( $args ); 

            while ( $loop->have_posts() ) : $loop->the_post(); 

            $imgurl = \wp_get_attachment_image_src( \get_post_thumbnail_id( \get_the_ID(),'medium',false))[0];
            ?>
            <div class="item container-fluid">
                <div class="row">
                    <div class="col-2 image-bg">
                        <div class="image" style="background:url('<?php echo $imgurl; ?>') center/cover;">
                        </div>
                    </div>
                    <div class="col-8">
                        <h4 class="streamtitle"><?php
                        echo '<a href="'.\get_post_permalink(\get_the_ID()).'">'.Condenser::limitWords(\get_the_title(\get_the_ID()), 10 ) ?></a></h4>
                        <span class="author"><?php echo \get_post_meta( \get_the_ID(), 'ghc_author_name', true ); ?></span>   
                        <span class="excerpt"><?php echo Condenser::limitWords( \get_the_excerpt(\get_the_ID()), 10); ?></span>   
                    </div>
                    <div class="col-2 date-bg">
                        <div class="date">
                            <?php $starttime = new DateTime( \get_post_meta( \get_the_ID() , 'ghc_stream_start', true ) );?>
                            <span class="month"><?php echo $starttime->format('M');?></span>
                            <span class="day"><?php echo $starttime->format('d');?></span>
                            <span class="time"><?php echo $starttime->format('h:i a');?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php                  
        endwhile;
        wp_reset_postdata(); 
        ?>
            </div>
        </div>


                </div><!-- END MAIN INDEX ROW -->
            </div><!-- END MAIN INDEX CONTAINER -->
        </div><!-- END MAIN INDEX -->
    </div><!--END COLUMN RIGHT CONTAINER MODULE --> 
</main><!-- #main -->
