<?php
use gh_connects\theme\Modules as Modules; 
use gh_connects\theme\classes\Condenser as Condenser;
use \gh_connects\admin\AuthNet as AuthNet;
require_once GreenheartConnects::get_plugin_path('theme/views/classes/condenser-class.php');

Modules::open_page_norobots();
/*/-------------------------------------------------------------------------------------------------------
/*
/*/ Modules::top_logo();                      Modules::top_menu();           $userState = Modules::top_avatar();
/*
/*---------------------------------------------------------------------------------------------------------
/*
/*
/*/
$showpage = false;
if($userState){
    $showpage = true;
} else {
      $public_page_checkstatus = get_post_meta(\get_the_ID(), 'public_page_checkstatus', true);
        if($public_page_checkstatus == "true") $showpage = true;
}
if($showpage){
?>
    <article class="container" id="post-<?php echo \get_the_ID(); ?>" <?php \post_class(); ?>>
        <div class="row">
            <div class="col-12">
                <div class="header entry-header">
                  <h1 class="entry-title"><?php echo \get_the_title(); ?> </h1>
                </div><!-- .entry-header -->

                <?php $pageimg = \get_the_post_thumbnail_url( \get_the_ID(), 'full'); ?>

                <div class="entry-content">
                    <div class="pageimg_wrap">
                        <img src="<?php echo $pageimg ?>" />
                    </div>
                    <?php
                   $content = apply_filters( 'the_content', \get_the_content( \get_the_ID() ) );
                   echo $content;
                    ?>
                </div><!-- .entry-content -->
            </div>
        </div>
    </article><!-- #post-<?php the_ID(); ?> -->

<?php
} else {
    ?>
    <article class="container" id="post-<?php echo \get_the_ID(); ?>" <?php \post_class(); ?>>
        <div class="row">
            <div class="col-12">
                <div id ="home-hero" class="container-fluid loggedout">
                <div class="row">
                    <div class="hero-bg">
                        <div class="container hero-fg">      
                            <div class="row justify-content-center justify-content-lg-start">
                                <div class="col-12 flex-column hero-excerpt justify-content-center align-items-center">
                                    <div id="pleaselogin">
                                        <h1>Please Log In</h1>
                                        <h5>Thanks for your interest in Greenheart Connects. To get access to our latest live streaming content</h5>
                                        <a href="<?php echo wp_login_url()?>">Log In</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
    <?php
}
/*                                  
/*                                  
/*
/*/ Modules::footer();              /*------------------------------------------------------------------------
/*
/*-----------------------------------------------------------------------------------------------------------*/
Modules::close_page();