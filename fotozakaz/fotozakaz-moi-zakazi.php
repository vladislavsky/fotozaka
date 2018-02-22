<?php
/*
    Template Name: Fotozakaz Moi Zakazi
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
            /***************************************************************/
            $orders = get_option('orders_'.$current_user->user_login);
            
            display_orders ($orders);  

            
            

            /***************************************************************/
            ?>
		<?php endwhile; // end of the loop. ?>
	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>


