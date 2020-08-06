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
					<span class="privacy"><a href="/#/modal/consent/tab/privacy">Privacy policy</a></span>
				</div>
			</div><!--END COL --> 
			<div class="col-12 col-lg-4">
            	<ul class="social follow clearfix">
					<li class="facebook" data-toggle="tooltip" data-placement="top" title="" data-original-title="
							<ul>
								<li><a target=&quot;_blank&quot; href=&quot;https://www.facebook.com/GreenheartTravel/&quot;>Greenheart Travel</a></li>
								<li><a target=&quot;_blank&quot; href=&quot;https://www.facebook.com/GreenheartInternational/?ref=aymt_homepage_panel&quot;>Greenheart Exchange</a></li>
								<li><a target=&quot;_blank&quot; href=&quot;https://www.facebook.com/shopgreenheart/&quot;>Greenheart Shop</a></li>
							</ul>
						">
						<img src="https://greenheart.org/wp-content/plugins/q-theme-international/library/theme/css/images/widget/follow/social_facebook.svg" alt="facebook">
					</li>
					<li class="instagram" data-toggle="tooltip" data-placement="top" title="" data-original-title="
							<ul>
								<li><a target=&quot;_blank&quot; href=&quot;https://www.instagram.com/greenheart_travel/&quot;>Greenheart Travel</a></li>
								<li><a target=&quot;_blank&quot; href=&quot;https://www.instagram.com/greenheartexchange/&quot;>Greenheart Exchange</a></li>
								<li><a target=&quot;_blank&quot; href=&quot;https://www.instagram.com/sobremesabygreenheart/&quot;>Sobremesa by Greenheart</a></li>
							</ul>
						">
						<img src="https://greenheart.org/wp-content/plugins/q-theme-international/library/theme/css/images/widget/follow/social_instagram.svg" alt="instagram">
					</li>
					<li class="twitter" data-toggle="tooltip" data-placement="top" title="" data-original-title="
						<ul>
							<li><a target=&quot;_blank&quot; href=&quot;https://twitter.com/greenhrttravel?lang=en&quot;>Greenheart Travel</a></li>
							<li><a target=&quot;_blank&quot; href=&quot;https://twitter.com/greenheartex?lang=en&quot;>Greenheart Exchange</a></li>
						</ul>
						">
						<img src="https://greenheart.org/wp-content/plugins/q-theme-international/library/theme/css/images/widget/follow/social_twitter.svg" alt="twitter">
					</li>
					<li class="youtube" data-toggle="tooltip" data-placement="top" title="" data-original-title="
						<ul>
							<li><a target=&quot;_blank&quot; href=&quot;https://www.youtube.com/channel/UChzUTo_2ji7B9Hp73da6QVw&quot;>Greenheart Travel</a></li>
							<li><a target=&quot;_blank&quot; href=&quot;https://www.youtube.com/channel/UCOl7gui9Vhl9c_ERAKg0sDw&quot;>Greenheart Exchange</a></li>
						</ul>
						">
						<img src="https://greenheart.org/wp-content/plugins/q-theme-international/library/theme/css/images/widget/follow/social_youtube.svg" alt="youtube">
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