<div class="container">
    <div class="row">
        <div class="col-12">
            <div id="comments" class="comments-area">
                <?php
                    global $postid;
                    $postid = \get_the_ID();
                ?>
                <ul class="commentlist">
                    <?php    
                        //Gather comments for a specific page/post 
                        $comments = \get_comments(array(
                            'post_id' => $postid,
                            'status' => 'approve' 
                        ));

                        //Display the list of comments
                        \wp_list_comments(array(
                            'per_page' => 10, //Allow comment pagination
                            'reverse_top_level' => false //Show the latest comments at the top of the list
                        ), $comments);
                        
                    ?>
                </ul>
                
                <?php \comment_form(); ?>
            </div><!-- #comments -->
        </div>
    </div>
</div>