<?php
/*
    Template Name: Fotozakaz Albums Add Photo
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
   
                                 
   
          echo '<form  method="post" enctype="multipart/form-data" name="front_end_upload" >';
          echo '<label for="photo-session" class="photo-session-label">Введите номер Фотосессии';
                select_num_photosession_current_user($current_user_login, $_POST['photosession_num']);
          
          echo '<input type="file" class="tiny-button" name="list_of_added_files[]"  multiple="multiple" >';
           
          echo '<input type="submit" name="Upload">';
           
          echo '</form>';
 
 
    upload_photosession ( $_FILES['list_of_added_files'], $current_user_login , $_POST['photosession_num'] )  ;     
    display_photo (  $current_user_login, $_POST['photosession_num'] );

    


            /******************************************/
?>
		<?php endwhile; // end of the loop. ?>
	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>


