<?php
// Template Name: Login

// set a redirect for after logging in
if ( isset( $_REQUEST['redirect_to'] ) )
	$redirect = $_REQUEST['redirect_to'];

if (!isset($redirect))
	$redirect = home_url();

$show_password_fields = apply_filters( 'show_password_fields_on_registration', true );
?>

<script type="text/javascript">
function CheckLength(name) {
    var password = document.getElementById(name).value;
    if (password.length < 6)
        alert('Your password should have miniumum 6 characters');
}
</script>
    
<div class="content">

	<div class="content_botbg">

		<div class="content_res">


			<div class="shadowblock_out">

				<div class="shadowblock">

<!-- 					<h1 class="dotted"><span class="colour"><?php _e( 'Login', APP_TD ); ?></span></h1> -->

					<div class="left-box">

					<div class="pad10"></div>

					<h1 class="dotted"><?php _e( 'Already Registered?', APP_TD ); ?></h1>
					<h1 class="textcolor"><?php _e( 'Login', APP_TD ); ?></h1>

					<?php do_action( 'appthemes_notices' ); ?>

					<p><?php _e( 'Please complete the fields below to login to your account.', APP_TD ); ?></p>

						<form action="<?php echo appthemes_get_login_url( 'login_post' ); ?>" method="post" class="loginform" id="login-form">

							<p>
								<label for="login_username"><?php _e( 'Username:', APP_TD ); ?></label>
								<input type="text" class="text required" name="log" id="login_username" value="<?php if (isset($posted['login_username'])) echo esc_attr($posted['login_username']); ?>" />
							</p>

							<p>
								<label for="login_password"><?php _e( 'Password:', APP_TD ); ?></label>
								<input type="password" class="text required" name="pwd" id="login_password" value="" />
							</p>

							<div class="clr"></div>

							<div id="checksave">

								<p class="rememberme">
									<input name="rememberme" class="checkbox" id="rememberme" value="forever" type="checkbox" checked="checked" />
									<label for="rememberme"><?php _e( 'Remember me', APP_TD ); ?></label>
								</p>

								<p class="submit">
									<input type="submit" class="btn_orange" name="login" id="login" value="<?php _e( 'Login &raquo;', APP_TD ); ?>" />
									<?php echo APP_Login::redirect_field(); ?>
									<input type="hidden" name="testcookie" value="1" />
								</p>

								<p class="lostpass">
									<a class="lostpass" href="<?php echo appthemes_get_password_recovery_url(); ?>" title="<?php _e( 'Password Lost and Found', APP_TD ); ?>"><?php _e( 'Lost your password?', APP_TD ); ?></a>
								</p>

								<?php do_action('login_form'); ?>

							</div>

						</form>

						<!-- autofocus the field -->
						<script type="text/javascript">try{document.getElementById('login_username').focus();}catch(e){}</script>

					</div><!-- /left-box -->
		
		
					<div class="right-box">
		
					<div class="pad10"></div>

					<h1 class="dotted"><?php _e( 'Not Registered Yet?', APP_TD ); ?></h1>
					<h1 class="textcolor"><?php _e( 'Register', APP_TD ); ?></h1>

					<?php //do_action( 'appthemes_notices' ); ?>

					<p><?php _e( 'Complete the fields below to create your free account. Your login details will be emailed to you for confirmation so make sure to use a valid email address. Once registration is complete, you will be able to submit your ads.', APP_TD ); ?></p>

						<?php if ( get_option('users_can_register') ) : ?>

							<form action="<?php echo appthemes_get_registration_url( 'login_post' ); ?>" method="post" class="loginform" name="registerform" id="registerform">

								<p>
									<label for="user_login"><?php _e( 'Username:', APP_TD ); ?></label>
									<input tabindex="1" type="text" class="text required" name="user_login" id="user_login" value="<?php if (isset($_POST['user_login'])) echo esc_attr(stripslashes($_POST['user_login'])); ?>" />
								</p>

								<p>
									<label for="user_email"><?php _e( 'Email:', APP_TD ); ?></label>
									<input tabindex="2" type="text" class="text required email" name="user_email" id="user_email" value="<?php if (isset($_POST['user_email'])) echo esc_attr(stripslashes($_POST['user_email'])); ?>" />
								</p>

								<?php if ( $show_password_fields ) : ?>
									<p>
										<label for="pass1"><?php _e( 'Password:', APP_TD ); ?></label>
										<input tabindex="3" type="password" class="text required" name="pass1" id="pass1" value="" autocomplete="off" />
									</p>

									<p>
										<label for="pass2"><?php _e( 'Password Again:', APP_TD ); ?></label>
										<input tabindex="4" type="password" class="text required" name="pass2" id="pass2" value="" autocomplete="off" />
									</p>

									<div class="strength-meter">
										<div id="pass-strength-result" class="hide-if-no-js"><?php _e( 'Strength indicator', APP_TD ); ?></div>
										<span class="description indicator-hint"><?php _e( 'Hint: The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers and symbols like ! " ? $ % ^ &amp; ).', APP_TD ); ?></span>
									</div>
								<?php endif; ?>

								<?php do_action('register_form'); ?>

								<div id="checksave">

									<p class="submit">
										<input tabindex="6" class="btn_orange" type="submit" name="register" id="register" value="<?php _e( 'Create Account', APP_TD ); ?>" />
									</p>

								</div>

								<input type="hidden" name="redirect_to" value="<?php echo esc_attr($redirect); ?>" />

								<!-- autofocus the field -->
								<script type="text/javascript">try{document.getElementById('user_login').focus();}catch(e){}</script>

							</form>

						<?php else : ?>

							<p><?php _e( '** User registration is currently disabled. Please contact the site administrator. **', APP_TD ); ?></p>

						<?php endif; ?>

			
						<div class="clr"></div>
		
					</div><!-- /right-box -->
				
					<div class="clr"></div>

				</div><!-- /shadowblock -->

			</div><!-- /shadowblock_out -->

		</div><!-- /content_res -->

	</div><!-- /content_botbg -->

</div><!-- /content -->
