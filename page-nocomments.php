<?php
/*
      Template Name: Fotozakaz Page NoComments
*/
?>
<?php

get_header(); ?>

<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', 'page' ); ?>

			
		<?php endwhile; // end of the loop. ?>


	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->
<br><br><br><br><br><br><br><br><br><br><br>
<?php get_sidebar(); ?>

<?php get_footer(); ?>