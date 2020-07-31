<!-- Module Layout -->
<?php 
# $duration = get_post_meta( $this->nearest_stream_id, 'ghc_stream_length', true ); 
# $datestamp = get_post_meta( $this->nearest_stream_id , 'ghc_stream_start', true );
?>
<div id="gh-page" class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-12 leftcolwrap">
            <!-- Module Left Col -->
            <div class="inner-wrap">
                <h4 class="details">Details</h4>
                <section>
                    <h5>DATE AND TIME</h5>
                    <p><?php 
                    $dt = new DateTime( get_post_meta( $this->nearest_stream_id , 'ghc_stream_start', true ) );
                    echo $dt->format('D, F d - g:i'); ?> (CST)</p>
                </section>
                <section>
                    <h5>Estimated Stream Length</h5>
                <p><?php echo get_post_meta( $this->nearest_stream_id, 'ghc_stream_length', true ) ?> Minutes</p>
                </section>  
                <section>
                    <h5>More Sections as Needed</h5>
                    <p>
                        <ul>
                            <li>Content</li>
                            <li>Goes Here</li>
                        </ul> 
                    </p>
                    <div class="body-cta">
                        <button class="btn btn-large">add to calendar</button>
                    </div>     
                </section>
            </div>
        </div>
            