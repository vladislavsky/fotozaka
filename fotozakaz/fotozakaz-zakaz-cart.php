<?php
/*
    Template Name: Fotozakaz Zakaz Cart
*/
?>
<?php  global $current_user;
  get_currentuserinfo();
  if ( $current_user->roles[0] == NULL ) {
header("Location: http://www.fotozakaz.com.ua/"); /* Redirect browser */
exit;}
	
	/*if ($_POST['e_photo'] == NULL && $_POST['fotokniga'] == NULL) {header('Location: ' . $_SERVER['HTTP_REFERER']);}*/

?>
<?php get_header(); ?>

<div id="primary" class="content-area">
	<div id="content"  class="site-content" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

		    <?php get_template_part( 'content', 'page' ); ?>
			<div id="login-wrapper">
				<div class="list-of-albums">
 				<form method="post" action="http://www.fotozakaz.com.ua/?page_id=498" >

					
		
				
				<?php   



					/*$user_cart = get_option('user_cart');*/
					$user_cart = array();
					$current_user_name = $current_user->user_login;

					$current_user_main_dir = ABSPATH .'wp-content/uploads/' . $_POST['num_photosession'] ;                     

				    /*            PATH FOR THE PHOTOSESSION                                     */
				    $file_path = $current_user_main_dir .'/'. $_POST['num_photosession'].'/' ;

				    /*            WEB ADDRESS for image to add                                   */
				    $http_path = "http://fotozakaz.com.ua/wp-content/uploads/".$_POST['name_photographer']."/".$_POST['num_photosession']."/";

			        $converted_array = converted_array ($_POST['e_photo'], $_POST['fotokniga']  );
					
					$list_of_chosen_files = make_a_list_of_chosen_files($_POST['e_photo'], $_POST['fotokniga']  );
					

					echo '<table class="zakaz-table">
							   
								   <tr id="table-header">
									    <th>Изображение</th>
									    <th>Наименование фотографии</th>
									    <th>Действие над снимком</th>
									    <th>Дополнительные заказы</th>
								   </tr>';
					echo '<input type="hidden" name="num_photosession" value='.$_POST['num_photosession'].'>';			  
					if ($list_of_chosen_files) {
						$price = get_option('fotozakaz_photographer_price_'.$_POST['name_photographer']);
						if ($price['fotokniga'] !== 0) { '- '.$price['fotokniga']."грн./шт.";} else {$price_fotokniga = "";}
						
							foreach ($list_of_chosen_files as $file) {
									
									$e_photo_print = $converted_array[$file]['e_photo'];
									$e_photo_print_checked ='';
									$fotokniga_print = $converted_array[$file]['fotokniga'];
									$fotokniga_print_checked ='';
									
									if ($e_photo_print) {$e_photo_print_checked = 'checked';}
									if ($fotokniga_print) {$fotokniga_print_checked = 'checked';}

									if ( $e_photo_print || $fotokniga_print) {
												echo '<tr><td><center style ="dispaly: flex;"><a class="fancybox gallery-photosession" href="'.$http_path.$file.'">
												                      <img title ="Кликните для увеличения" class="fancyimg" src="'.$http_path.$file.'" />
												                      </a></center>
								                      	  </td>';
												echo     '<td>'.$file.'</td>';
												echo     '<td><div><label>В электронном виде
												                 <input type="checkbox" class="check-choose" name="e_photo[]'.$file.'" value = '.$file.' '.$e_photo_print_checked.'>
												                <br>'.$price['e_photo'].' грн.<br><br></label>
												              </div>
												              <div><label>В альбоме
												                 <input type="checkbox" class="check-choose" name="fotokniga[]'.$file.'" value = '.$file.' '.$fotokniga_print_checked.'>
												               '.$price_fotokniga.'</label>
												              </div>
														  <td>	  <div><label>Напечатать фотографию размером:</label><br>
																	  	 <label>10*15
														                   <input class="input-format-photo" type="number" min=0 value=0 name="1015[]['.$file.']">
														                  - '.$price['1015'].' грн./шт.</label><br>
																		 <label>13*18
														                   <input class="input-format-photo" type="number"  value=0 min=0 name="1318[]['.$file.']">
														                  - '.$price['1318'].' грн./шт.</label><br>
														                 <label>15*22
														                   <input class="input-format-photo" type="number"  value=0 min=0 name="1522[]['.$file.']">
														                  - '.$price['1522'].' грн./шт.</label><br>
														                 <label>20*30
														                   <input class="input-format-photo" type="number"  value=0 min=0 name="2030[]['.$file.']">
														                  - '.$price['2030'].' грн./шт.</label>
												              </div>

												                 ';

												echo      '</td>';
												
												echo '</tr>';
												
												
									}
									
							}
					}
							
					?>
	
  

                    </table>

					<?php



				?>
			
			<input type="submit" name="go_to_order">
		</form> 
		</div></div>
	<?php endwhile; // end of the loop. ?>
		</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>