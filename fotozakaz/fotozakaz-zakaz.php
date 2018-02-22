<?php
/*
    Template Name: Fotozakaz Zakaz
*/
?>
<?php  global $current_user;
  get_currentuserinfo();
  if ( $current_user->roles[0] == NULL ) {
header("Location: http://www.fotozakaz.com.ua/"); /* Redirect browser */
exit;}?>
<?php get_header(); ?>

<div id="primary" class="content-area">
	<div id="content"  class="site-content" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

		    <?php get_template_part( 'content', 'page' ); ?>
			<div id="login-wrapper">
				<?php if ($_POST == NULL) {?>
						<table class="enter-gallery">
							<div class="list-of-albums">
							<form method="post" >
								<tr>
									<td><label for="num_photosession">Номер фотосессии: </label></td>
								    <td><input type="text" name="num_photosession"></td>
								</tr>
								<tr>
									<td><label for="password_photosession">Пароль: </label></td>
									<td><input type="password" name="password_photosession"></td>
								</tr>
								<tr>
									<td><input type="submit" name="enter-to-gallery" value="Показать галерею"></td>
								</tr>
							</form>
							</div>
						</table>
				<?php }  
		        
			        if (!$is_owner_of_album && $_POST['enter-to-gallery'] !== NULL) {
	      				    if (!fotozakaz_check_password ($_POST['password_photosession'], $_POST['num_photosession'])) { 
	                                  echo '<div class="information-wrong">
	                                  Неправильный пароль</div>';
	                                  echo '<FORM><INPUT class="button-back" Type="button" VALUE="<< Вернуться назад" onClick="history.go(-1);return true;"></FORM>
	                                  ';
	          				} else {
	          						
					display_photo ( $current_user->user_login, $_POST['num_photosession']); 
									}
	     			} 
				?>
			</div>
			
	<?php endwhile; // end of the loop. ?>
		</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>