<?php
use gh_connects\theme\Modules as Modules; 
use gh_connects\theme\classes\Condenser as Condenser;
require_once GreenheartConnects::get_plugin_path('theme/views/classes/condenser-class.php');

Modules::open_page();
/*/-------------------------------------------------------------------------------------------------------
/*
/*/ Modules::top_logo();                      Modules::top_menu();           $userState = Modules::top_avatar();
/*
/*---------------------------------------------------------------------------------------------------------
/*
/*
/*/

if($userState){
    if($userState->cn_status === 'paid'){

if ( have_posts() ) : while ( have_posts() ) : the_post(); 
?>
<div id ="home-hero" class="container">
    <div class="row">
    <div class="col-12">
            <a href="<?php echo \home_url() ?>"><button class="backhome"><span class="backarrow"></span>Back</button></a>
        </div>
        <div class="col-12">
        <h1 class="pagetitle frontpage"><?php echo \get_the_title(\get_the_ID()) ?></h1>
        </div>
        <div class="col-12 pagecontent">
            <?php echo \get_the_excerpt(\get_the_ID() ); ?>
        </div>
        <div class="col-2 author-wrap">
            <?php  $imgurl = \wp_get_attachment_image_src( \get_post_thumbnail_id( \get_the_ID(),'medium',false))[0];?>
            <div class="author-bg" style="background:url(<?php echo $imgurl ?>) center/cover;">

            </div>
        </div>
        <div class="col-2 date-wrap">
            <div class="date-bg">
                <div class="datewrap">
                    
                    <span class="month"><?php echo \get_the_date( 'M', \get_the_ID() );?></span>
                    <span class="day"><?php echo \get_the_date( 'd', \get_the_ID() );?></span>           
                    <span class="time">Streamed on:</span>
                </div>
            </div>
        </div>
        <div class="col-8 infobody">
            <div class="infowrap">
                
                <div class="info-accordion"><span class="hamburger-expand"></span>
                    <div class="info-excerpt"><?php echo Condenser::limitWords( \get_post_meta( \get_the_ID(), 'ghc_author_bio', 20 )) ?></div>
                    
                </div>
                <div class="info-row">
                    

                </div>
            </div>
        </div>
    </div><!-- end row -->     
</div><!-- end container -->



<div id ="stream-hero" class="container">
    <div class="row">
    <div class="col-12 col-md-8 offset-md-2 video-center">
        <?php
        #$type = \get_post_meta( \get_the_ID(), 'ghc_video_type', true);
        $type = 'embed';
        $path = \get_post_meta( \get_the_ID(), 'ghc_video_path', true);
        if( $type && strtolower($type) == 'embed'){ 
            echo \get_post_meta( \get_the_ID(), 'ghc_video_path', true );
        } elseif ($type && strtolower($type) == 'file') {
            #DO FILE HERE
        } else{
            if( GreenheartConnects::$debug ){
                error_log('VIDEO URL NOT PROPERLY SET ON VIDEO POST: '.\get_the_ID() );
            }
        }
        ?>
        </div>
    </div>
</div>
<?php
               
    endwhile;
endif;
wp_reset_postdata(); 

?>


         
<?php
/*
/*
/*
/*                                  
/*                                 
/*/ Modules::single_col($userState);  
/*                                  
/*                                 
/*/ Modules::single_comments($userState);                                         
/*                                  
/*/

 } else {
        //payment form here
        require_once GreenheartConnects::get_plugin_path('theme/views/components/hero_section-unpaid.php');
    }
} else {
    echo '<br><br><br>';
    echo '<h5 style="text-align:center;">Please <a href="/login/?action="login">login</a> to see our archive of videos.</h5>';
}


/*
/*/ Modules::footer();              /*------------------------------------------------------------------------
/*
/*-----------------------------------------------------------------------------------------------------------*/
Modules::close_page();