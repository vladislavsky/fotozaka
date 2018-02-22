<?php
/*
    Template Name: Fotozakaz Albums Edit
*/
?>
<?php  global $current_user;
  get_currentuserinfo();
  if ($current_user->roles[0] == 'subscriber' || $current_user->roles[0] == NULL ) {
header("Location: http://www.fotozakaz.com.ua/"); /* Redirect browser */
exit;}?>
<?php get_header(); ?>


<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

		    <?php get_template_part( 'content', 'page' ); ?>
            <?php
            /*****************************************/
 
          $current_user_login = $current_user->user_login;
          echo '<div class="title">Ваше имя: '.$current_user_login.'</div>';
   
          echo '<form  method="post" name="front_end_view" >';
          echo '<label for="photo-session" class="photo-session-label">Выберите номер Фотосессии';
                select_num_photosession_current_user($current_user_login, $_POST['photosession_num']);
          echo '<input type="submit" name="Upload">';
          echo '</form><hr>';

          display_photo (  $current_user_login, $_POST['photosession_num'] );
       
            /******************************************/
?>
		<?php endwhile; // end of the loop. ?>
	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>


