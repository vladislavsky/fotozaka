<?php
/*
    Template Name: Fotozakaz Albums Delete
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
            <?php /***************************************************************/

   $current_user_login = $current_user->user_login;
   echo '<div class="title">Ваше имя: '.$current_user_login.'</div>';
   
   $photosession_num_name_of_dir = $_POST["photosession_num"];                           
   $current_user_main_dir = ABSPATH .'wp-content/uploads/' . $current_user_login;                     

   $file_path = $current_user_main_dir .'/'. $photosession_num_name_of_dir.'/' ;
 
   if ($_POST['submit_delete_album']) {delete_album($_POST['num_photosession'], $current_user_login);} 
   var_dump($_POST);
     
   list_of_albums($current_user_login, true);



            /***************************************************************/
?>
		<?php endwhile; // end of the loop. ?>
	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>


