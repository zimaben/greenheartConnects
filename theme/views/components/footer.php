<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _greenheart
 */

?>
</div>
<footer>
	<div class="container-fluid footer">
		<div class="row">
			<div class="col-12 col-lg-8 footer-info">
				<span class="anchor" data-scroll="footer"></span>  
				<div class="contact">
					<span class="address">712 N Wells St. Chicago, IL 60654, USA</span>
					<span class="phone">Tel: 1 (312) 944-2544</span>
					<span class="phone">Toll-Free in the U.S: 1 (866) 224-0061</span>
					<!--<span class="email"><i class="fa fa-envelope"></i>Contact</span>-->
					<span class="privacy"><a href="/privacy-policy">Privacy policy</a></span>
				</div>
			</div><!--END COL --> 
			<div class="col-12 col-lg-4">
            	<ul class="social follow clearfix">
					<li class="facebook">
						<a href="https://www.facebook.com/GreenheartInternational" title="facebook" target="_blank">	
							<img src="https://greenheart.org/wp-content/plugins/q-theme-international/library/theme/css/images/widget/follow/social_facebook.svg" alt="facebook">
						</a>
					</li>
					<li class="instagram">
						
						<a href="https://www.instagram.com/greenheartexchange/" title="instagram" target="_blank">
						<img src="https://greenheart.org/wp-content/plugins/q-theme-international/library/theme/css/images/widget/follow/social_instagram.svg" alt="instagram">
						</a>
					</li>
					<li class="twitter">	
						<a href="https://twitter.com/GreenheartEX" title="twitter" target="_blank">
						<img src="https://greenheart.org/wp-content/plugins/q-theme-international/library/theme/css/images/widget/follow/social_twitter.svg" alt="twitter">
						</a>
					</li>
					<li class="youtube" >
						<a href="https://www.youtube.com/user/GreenheartIntl" title="youtube" target="_blank">			
						<img src="https://greenheart.org/wp-content/plugins/q-theme-international/library/theme/css/images/widget/follow/social_youtube.svg" alt="youtube">
					</a>
					</li>
					<li class="linkedin">
						<a href="https://www.linkedin.com/company/279581/admin/" title="Linkedin" target="_blank">
							<img src="https://greenheart.org/wp-content/plugins/q-theme-international/library/theme/css/images/widget/follow/social_linkedin.svg" alt="linkedin">
						</a>
					</li>
				</ul>
			</div>	
		</div>
	</div>
    <div class="q-clear"></div>
	<div class="modal-warehouse">
	<div id="pleaselogin">
		<h3>Please Log In</h3>
		<h5>To get access to our latest live streaming content</h5>
		<a href="<?php echo wp_login_url()?>">Log In</a>
	</div>
	<div id="payment">
		<div class="container-fluid payment-bg">
			<div class="row">
				<div class="col-12 col-md-8 col-lg-6 offset-lg-6 offset-md-4">
				<?php  echo do_shortcode('[gravityform id="1" ajax="true"]'); ?>
					<br>
					<br>
					<div>(develoment only)<br>
						<script>
									
								const cheatMetaKeys = async(e) => {
								var formData = new FormData();
								formData.append('userID', e.target.dataset.userid);

								var request = new XMLHttpRequest();
								let url = ajaxurl + '?action=cheatMetaKeys';
								request.open('POST', url);
								request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
								let data =encodeURIComponent( 'userID' ) + '=' + encodeURIComponent( e.target.dataset.userid );
								request.send(data);

								}
						</script>
						<button class="btn btn-large" data-userID="<?php echo get_current_user_id() ?>" onclick="cheatMetaKeys(event)">Cheat The Meta Keys</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</footer>

<?php wp_footer(); ?>