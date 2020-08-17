<!-- Module Layout -->
<?php 
# $duration = get_post_meta( $this->nearest_stream_id, 'ghc_stream_length', true ); 
# $datestamp = get_post_meta( $this->nearest_stream_id , 'ghc_stream_start', true );
?>

    <div class="col-md-6 col-12 leftcol">
        <!-- Module Left Col -->
        <div class="inner-wrap">
        <h3 class="upcoming">Upcoming Streams</h3>
        <?php                 
            $args = array(  
                'post_type' => 'streams',
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
                        echo '<a href="'.\get_post_permalink(\get_the_ID()).'">'.\get_the_title(\get_the_ID()) ?></a></h4>
                        <span class="author"><?php echo \get_post_meta( \get_the_ID(), 'ghc_author_name', true ); ?></span>   
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
            