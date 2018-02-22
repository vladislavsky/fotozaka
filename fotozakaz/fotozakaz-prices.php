<?php
/*
    Template Name: Fotozakaz Prices
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
          /*var_dump('<pre>', $_POST, '</pre>');*/
          $name_photographer = $current_user->user_login;
          if ($_POST['change-prices'] !== NULL) {

                $price['e_photo'] = $_POST['e_photo'];
                $price['fotokniga'] = $_POST['fotokniga'];
                $price['1015'] = $_POST['1015'];
                $price['1318'] = $_POST['1318'];
                $price['1522'] = $_POST['1522'];
                $price['2030'] = $_POST['2030'];

                update_option('fotozakaz_photographer_price_'.$name_photographer, $price);
          }
                $price = get_option('fotozakaz_photographer_price_'.$name_photographer);
          
          echo '<form method="post">';
          echo '<table>';
          echo '<tr><th>Наименование</th><th>Цена</th></tr>';
          echo '<tr><td><label for="e_photo">В электронном виде:</label>
                    </td><td><input type="number" name="e_photo"min="0" value='.$price['e_photo'] .'></td></tr>';
          echo '<tr><td><label for="fotokniga">Альбом:</label>
                    </td><td><input type="number" name="fotokniga" min="0" value='.$price['fotokniga'] .'></td></tr>';
          echo '<tr><td><label for="1015">Фото-печать 10х15:</label>
                    </td><td><input type="number" name="1015" min="0" value='.$price['1015'] .'></td></tr>';
          echo '<tr><td><label for="1318">Фото-печать 13х18:</label>
                    </td><td><input type="number" name="1318" min="0" value='.$price['1318'] .'></td></tr>';
          echo '<tr><td><label for="1522">Фото-печать 15х22:</label>
                    </td><td><input type="number" name="1522" min="0" value='.$price['1522'] .'></td></tr>';
          echo '<tr><td><label for="2030">Фото-печать 20х30:</label>
                    </td><td><input type="number" name="2030" min="0" value='.$price['2030'] .'></td></tr>';
          
          echo '</table>';
          echo '<input type="submit" name="change-prices" value="Сохранить">';
          echo '</form>';
            /***************************************************************/
            ?>
		<?php endwhile; // end of the loop. ?>
	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>


