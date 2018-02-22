<?
global  $wpdb;
session_start();

$date_key = md5(date('F j, Y'));
 // $wpdb->show_errors(); 
if ($_SESSION[$date_key.'_registration']< 500){
	if(!empty($_POST['username_check'])){
		$_SESSION[$date_key.'_registration']++;
		if (username_exists( $_POST['username_check'])){
			echo 1;
		}
		else {
			echo 0;
		}
		die();
	}

	if(!empty($_POST['email_check'])){
		$_SESSION[$date_key.'_registration']++;
		if(filter_var($_POST['email_check'], FILTER_VALIDATE_EMAIL)){

			if (email_exists( $_POST['email_check'])) {
				echo 1; 
			} 
			else{
				echo 0;
			}
		}
		else {
			echo 1;
		}
		die();
	}
}
$login = sanitize_user($_POST['login']);
$email = sanitize_email($_POST['email']);
$password = $_POST['password'];

if ( 	$_POST['password'] == $_POST['password-confirm'] 
	&& !empty($login) 
	&& !empty($email) 
	&& !empty($password)
	){
	if ($_SESSION[$date_key.'_user_registration'] < 10){

		if (!empty($_POST['register-button'])){
			

			$userdata = array(
				'user_email'	=> 	$email,
				'user_login'  	=>  $login,
				'role' 		  	=>  'contributor',
				'user_pass'   	=>  $_POST['password'] 
				);

			$registered_user_id = wp_insert_user( $userdata ) ;
			
			$redirection_path = '?page_id=478';
			$admin_email = 'lisichka2222@rambler.ru';
			$admin_email = '7875478@ukr.net';
			$site_name = ltrim(ltrim( site_url(), 'http://'), 'www.');
			$subject =  'Вы зарегистрированы!';

			if ( ! is_wp_error( $registered_user_id ) ) {
				$_SESSION[$date_key.'_user_registration']++;

				$headers = array('Content-Type: text/html; charset=UTF-8');
				$headers[] = 'From:'.$site_name.' <'.$admin_email.'>' . "\r\n";
				$message=
				'<h1>Фотозаказ</h1><br>'.
				'Логин:'.$login.'<br>'.
				'Пароль:'.$password.'<br>'
				;

				$sending_error = wp_mail( $email, $subject, $message, $headers );

				$creds = array();
				$creds['user_login'] = $login;
				$creds['user_password'] = $password;
				$creds['remember'] = true;
				$user = wp_signon( $creds, false );
				if ( is_wp_error($user) ){
					echo $user->get_error_message();
				}
				else{
					header("Location: ".site_url().$redirection_path);
				}

			}
		}
	}
}
?>
<?get_header()?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<div class="container">
	<header class="entry-header">
		<h1 class="entry-title main-page-title">Вход в галерею</h1>
	</header>

	<div class="row">
		<div class="col-xs-12 col-md-4">
			<div class="lwa-login-form-wrapper main-page-login-wrapper">
				<?if (!is_user_logged_in()) :?>
				<h3 class="lwa-login-form-header">Логин</h3>
				<?endif;?>
				<? login_with_ajax();?>

				<?if (!is_user_logged_in()) :?>
				<hr>
				
				<h3 class="registration-title">Регистрация</h3>
				<?if ($_SESSION[$date_key.'_registration']<500) :?>
				<form action="" method="post">
					
					<div class="form-group suffix-box">
						<input id="email" class="form-control" type="email" placeholder="Email *" name="email">
						<label  class="suffix-no-label email-indicator"><i class="fa fa-refresh fa-spin"></i><i class="fa fa-times"></i><i class="fa fa-check"></i><i class='add-eye-equal-space'></i></label>

					</div>
					<div class="form-group suffix-box">
						<input id="login" class="form-control " type="text" placeholder="Имя пользователя *" name="login">
						<label  class="suffix-no-label login-indicator"><i class="fa fa-refresh fa-spin"></i><i class="fa fa-times"></i><i class="fa fa-check"></i><i class='add-eye-equal-space'></i></label>

					</div>
					<div class="form-group suffix-box">
						<input id="password" class="form-control input-password" type="password" placeholder="Пароль" name="password">
						<label  class="suffix-no-label password-indicator"><i class="fa fa-times"></i><i class="fa fa-check"></i> <i id="show-password" class="fa fa-eye"><input type="checkbox" class="hidden"></i></label>
					</div>	
					<div class="form-group">
						<input id="password-confirm" class="form-control input-password" type="password" placeholder="Подтверждение пароля" name="password-confirm">
					</div>
					<div id="register-button-wrapper">
						<input id='register-button' name='register-button' type="submit" class="btn btn-lg btn-primary" style="    text-transform: capitalize;" value="Зарегистрировать" >
					</div>

				</form>
				<style>
					.lwa-modal input[type="file"]:hover, .lwa-modal #wp-submit:hover, .lwa-modal .button-back:hover{
						background-color: #FFA502;
					}
					.lwa-modal input[type="file"], .lwa-modal #wp-submit, .lwa-modal .button-back{
						background-color: #FFA502;
					}
					.lwa-modal #user_login, .lwa-modal #user_pass, .lwa-modal #user_email {
						width: 260px;
						display: block;
						padding: 10px 20px;
					}
					.main-page-login-wrapper #register-button, .main-page-login-wrapper #lwa_wp-submit{
						background-color: transparent;
						border:1px solid #FFF;
						opacity: 1;
						color: #FFF;
						font-weight: lighter;
					}
					.lwa-login-form-header{
						margin-top: 0;
					}
					.main-page-login-wrapper  .lwa-links-register {
						display: none;
					}
					.main-page-login-wrapper #lwa_wp-submit{
						border:1px solid #FFF;
						color: #fff;
					}
					.main-page-login-wrapper input[type=text], .main-page-login-wrapper input[type=email], .main-page-login-wrapper input[type=password]{
						padding-left: 10px;
					}
					#register-button:hover, .main-page-login-wrapper #lwa_wp-submit:hover{
						color: #FFF;
						background-color: #777;
					}
					.main-page-login-wrapper #register-button-wrapper{
						text-align: center;
					}
					.main-page-login-wrapper .lwa-login-form-header, 
					.main-page-login-wrapper .registration-title  {
						color: #FFF;
						text-align: center;
					}
					.main-page-login-wrapper .lwa-username-label,
					.main-page-login-wrapper .lwa-submit-links a,
					.main-page-login-wrapper .lwa-submit-links{
						color: #FFF;
					}
					.main-page-login-wrapper .lwa-password-label{
						color: #FFF;
					}
					.main-page-title{
						padding: 18px;
					}
					.lwa-login-form-wrapper{
						padding: 15px;
						background-color: #FFA502;
						margin-top: 10px;
					}
					a:hover{
						text-decoration: 	none;
					}
					input[type="text"], input[type=password] {
						border-radius: 0;
						border: 1px solid #FFA502;
						margin: 0 0px; 
						width: 100%; 
					}
					.suffix-box {
						position: relative;
					}
					.form-group {
						margin-bottom: 15px;
					}
					.form-control {
						display: block;
						width: 100%;
						height: 34px;
						padding: 6px 12px;
						font-size: 14px;
						line-height: 1.42857143;
						color: #555;
						background-color: #fff;
						background-image: none;
						border: 1px solid #ccc;
						border-radius: 4px;
						-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
						box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
						-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
						-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
						transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
					}
					.suffix-no-label{
						width: auto !important;
						padding-right: 12px;
						position: absolute;
						right: 0;
						top: 7px;
						vertical-align: middle;
						color: #b2b2b2;
						font-weight: 600;
						cursor:pointer;
					}
					.hidden{
						display: none;
					}	
					.suffix-no-label .fa-check{
						color:#00C900;
						display:    none;   
					}
					.suffix-no-label .fa-times{
						color:red;
						display:    none;   
					}
					.suffix-no-label .fa-refresh{
						display:    none; 
					}
					.suffix-no-label .show-inline{
						display: inline-block;
					}
					.suffix-no-label .hide{
						display: none;
					}
					.add-eye-equal-space{
						width: 25px;
					}
					.main-page-text-info{
						color: #333;
						line-height: 1.5;
						font-size: 16px;
						margin-top: 15px;
					}
				</style>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
				<script>
					;(function($){	
						var loginChecked = false;
						var emailChecked = false;
						var passChecked = false;

						var loginValue = '';
						var emailValue = '';
						var passValue = '';

						var $login = $('#login');
						var $email = $('#email');
						var $pass =  $('#password');
						var $passConfirm =  $('#password-confirm');
						var $button =  $('#register-button');

						$login.change(function(){
							ajaxLoginCheck();
							console.log(loginChecked);
						});

						$email.change(function(){
							ajaxEmailCheck();
							console.log(loginChecked);
						});

						$pass.change(function(){

							checkPass();
							console.log(passChecked);

						});

						$passConfirm.change(function(){

							checkPass();
							console.log(passChecked);

						});













						$('#show-password').click(function(){
							if ($('#show-password > input').is(':checked')) {
								$('.input-password').attr('type', 'text');
							} else {
								$('.input-password').attr('type', 'password');
							}
						});

						$('#password-confirm').focusout(function(event) {
							checkPass();
						});
						$('#password').focusout(function(event) {
							checkPass();
						});

						$('.input-password').hover(function(){}, function(){
							checkPass();
						})
						function checkPass(){
							if ($.trim($('#password').val()) !='' && $.trim($('#password-confirm').val()) != '' ){
								if ($.trim($('#password-confirm').val()) != ''){ 
									if ( $.trim($('#password').val()) == $.trim($('#password-confirm').val()) ) {
										$('.password-indicator .fa-times').removeClass('show-inline');
										$('.password-indicator .fa-check').addClass('show-inline');
										passChecked = true;
									}
									else{
										$('.password-indicator .fa-times').addClass('show-inline');
										$('.password-indicator .fa-check').removeClass('show-inline');
										passChecked = false;
									}
								}
								else{
									$('#register-button').attr('disabled', 'disabled');
									passChecked = false;
									$('.password-indicator .fa-check').removeClass('show-inline');
									$('.password-indicator .fa-times').removeClass('show-inline');

								}
							}
							else { 
								$('#register-button').attr('disabled', 'disabled');
								passChecked = false;
								$('.password-indicator .fa-check').removeClass('show-inline');
								$('.password-indicator .fa-times').removeClass('show-inline');

							}
						}

						function ajaxLoginCheck(){
							if( $.trim($('#login').val()).length ){
								$.ajax({
									url: "",
									method: "POST",

									beforeSend:(function(){
										$('.login-indicator .fa-refresh').addClass('show-inline');
									}),

									data: { username_check: $.trim($('#login').val()) }
								})
								.done(function( msg ) {

									$('.login-indicator .fa-refresh').removeClass('show-inline');
									if (msg != 1 ){
										loginChecked = true;
										$('.login-indicator .fa-times').removeClass('show-inline');
										$('.login-indicator .fa-check').addClass('show-inline');
										$('#register-button').removeAttr('disabled');
									}
									else{
										$('.login-indicator .fa-times').addClass('show-inline');
										$('.login-indicator .fa-check').removeClass('show-inline');
										$('#register-button').attr('disabled', 'disabled');
										loginChecked = false;
									}
								}); }
								else {
									$('.login-indicator .fa-times').removeClass('show-inline');
									$('.login-indicator .fa-check').removeClass('show-inline');
									loginChecked = false;
								}
							}
							$('#login').focusout(function(event) {
								ajaxLoginCheck();
							});
							function isEmail(email) {
								var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
								return regex.test(email);
							} 

							function ajaxEmailCheck(){
								if( $.trim($('#email').val()).length ){
									$.ajax({
										url: "",
										method: "POST",

										beforeSend:(function(){
											$('.email-indicator .fa-refresh').addClass('show-inline');
										}),

										data: { email_check: $.trim($('#email').val()) }
									})

									.done(function( msg ) {
										console.log(msg);

										$('.email-indicator .fa-refresh').removeClass('show-inline');

										console.log(msg);
										if (msg != 1 ){
											$('.email-indicator .fa-times').removeClass('show-inline');
											$('.email-indicator .fa-check').addClass('show-inline');
											$('#register-button').removeAttr('disabled');
											emailChecked = true;

										}
										else{
											$('.email-indicator .fa-times').addClass('show-inline');
											$('.email-indicator .fa-check').removeClass('show-inline');
											$('#register-button').attr('disabled', 'disabled');
											emailChecked = false;

										}
									});
								}
								else{
									$('.email-indicator .fa-check').removeClass('show-inline');
									$('.email-indicator .fa-times').removeClass('show-inline');
									emailChecked = false;

								}
							}

							$('#email').focusout(function(event) {

								ajaxEmailCheck();

							});

							$('#register-button-wrapper').hover(function() {
								console.log(emailChecked);
								console.log(loginChecked);
								console.log(passChecked);

								if ( emailChecked == true && loginChecked == true && passChecked == true  ){
									$('#register-button').removeAttr('disabled');

								}
								else {
									$('#register-button').attr('disabled', 'disabled');

								}
							});
						})(jQuery);
					</script>
					<?else:?>
					<h2>Вы превысили количество попыток!</h2>
					<?endif;?>
					<?endif;?>

				</div>
			</div>
			<div class="col-xs-12 col-md-8 text-justify main-page-text-info">
					<?=get_post(627)->post_content?>
				
			</div>

		</div>
	</div>
	<?get_footer()?>