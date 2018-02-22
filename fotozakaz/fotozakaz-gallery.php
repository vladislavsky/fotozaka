<?php
/*
    Template Name: Fotozakaz page
*/

get_header(); ?>



<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

		    <?php get_template_part( 'content', 'page' ); ?>
            <?php require_once ('call-page-choose.php');?>

		<?php endwhile; // end of the loop. ?>
	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>


