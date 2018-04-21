<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://taylrr.co.uk/
 * @since      1.0.0
 *
 * @package    Taylrr_Insta_Posts
 * @subpackage Taylrr_Insta_Posts/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div id="wrap">
	<h1>Insta Posts Settings</h1>
	<form method="post" action="options.php">
		<?php
			settings_fields( 'taylrr-insta-posts-settings-group' );
			do_settings_sections( $_GET['page'] ? $_GET['page'] : 'taylrr-insta-posts' );
			submit_button();
		?>
		<script id="autocomp-template" type="text/html">
			<div class="autocomp">
				<div class="autocomp-point"></div>
				<div class="autocomp-list-wrap">
					<div class="autocomp-list">
						<a class="autocomp-list-item" href="{{user_link}}">
							<div class="autocomp-user">
								<img class="autocomp-user-image" src="{{user_image}}">
								<div class="autocomp-user-info-wrap">
									<div class="autocomp-user-info">
										<span class="autocomp-user-info-username">{{username}}</span>
										<!--div class="verifiedBadgeSmall"></div-->
									</div>
									<span class="autocomp-user-info-name">{{user_name}}</span>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</script>
	</form>
	<h1>How to use</h1>
	<hr>
	<p>To start using the Insta Posts plugin, simply</p>
	<ul class="ul-disc">
		<li>Add the Insta Posts widget to your sidebar from the <a href="<?php echo get_admin_url(null,'widgets.php'); ?>">widget settings</a>, and/or</li>
		<li>Use the <code>[insta_posts]</code> shortcode wherever you would like to display images from your instagram feed.<br>
		All customisable behaviour available within the widget options is also available to alter the behaviour of the shortcode, just pass the desired values to the following within the shortcode
			<ul class="ul-square">
				<li><code>num</code> -- The total number of images to display, maximum is 12</li>
				<li><code>row</code> -- The number of images to display in one row</li>
				<li><code>padding</code> -- The spacing between each image in a row and between rows</li>
				<li><code>user</code> -- You can even set the user for each shortcode individually, if specified this username will take precedent over the default user saved in the setting above, if it is not the default user is always assumed.</li>
			</ul>
		For example, the default behaviour would be <code>[insta_posts num=6 row=2 padding=5]</code>.
		</li>
	</ul>
</div>