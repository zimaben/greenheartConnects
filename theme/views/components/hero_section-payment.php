<main id="primary" class="site-main home">
    <!-- Module Home Hero -->
    <div id="home-hero" class="container paymentneeded">
        <div class="row">
            <div class="col-12">
            <h2 class="nextstream text-center">Payment Information</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-8 offset-lg-2 payfore">
                <div>To see new live streaming content please enter your payment information below. Thanks!.</div>
                <?php $payform_id = \get_option('ghc_payment_form_id'); ?>
                <div> <?php  echo do_shortcode('[gravityform id="'.$payform_id.'" ajax="true"]'); ?> </div>
            </div>
        </div><!-- end row -->   
    </div> 
    <!-- END Home Hero -->
</main>   
