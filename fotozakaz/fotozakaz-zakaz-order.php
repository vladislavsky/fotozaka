<?php
/*
    Template Name: Fotozakaz Zakaz Order
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
 				<form method="post" >
 				<?php 

					$name_photographer = get_photographer_name( $_POST['num_photosession'] );
					$current_user_name = $current_user->user_login;
  				 	$num_photosession = $_POST['num_photosession'];
  				if ($_POST['agree'] == NULL) {

					update_option('user_cart_'.$num_photosession.'_'.$current_user_name, $_POST);


 					fotosession_info($_POST['num_photosession']);
 					  
 				 
 	
					$price = get_option('fotozakaz_photographer_price_'.$name_photographer);
					

									echo '<table>
										 <tr><th>Наименование</th><th>Цена</th><tr>';
								  	$i = 1; $count_of_e_photo = count($_POST['e_photo']);
								  	if ($count_of_e_photo !== 0) {
								  			$total_e_photo = $count_of_e_photo * $price['e_photo'];
										  	echo '<tr><td>В электронном виде за '.$count_of_e_photo.'шт.:<br>';	
		   								  	echo '(';
							
												foreach ($_POST["e_photo"] as $key=>$val){
													echo $val;
													if ($i < $count_of_e_photo){echo ", ";} else {echo "";}
													$i++;
												}
										  	echo ')</td><td>'.$total_e_photo.'грн</td></tr>';
									}
									$i = 1; $count_of_fotokniga = count($_POST['fotokniga']);
							    	if ($count_of_fotokniga !== 0) {
											$total_fotokniga = $count_of_fotokniga*$price['fotokniga'];
									    	echo '<tr><td> В фотокниге за '.$count_of_fotokniga.'шт.:<br>';	
			   								echo '(';
								
													foreach ($_POST["fotokniga"] as $key=>$val){
														echo $val;
														if ($i < $count_of_fotokniga){echo ", ";} else {echo "";}
														$i++;
													}
											echo ')</td><td>'.$total_fotokniga.'грн</td></tr>';
									}
									
									if (count($_POST['1015']) !== 0) {
									$total_1015 = 0;
											foreach ($_POST["1015"] as $key=>$val){
												foreach ($val as $key2 => $value) {
													if ($value <0) {$value = 0;}
													$total_1015 += $value;
												}
											}
									}
	   								
					   				if (count($_POST['1318']) !== 0) {
					   				$total_1318 = 0;
											foreach ($_POST["1318"] as $key=>$val){
												foreach ($val as $key2 => $value) {
													if ($value <0) {$value = 0;}
													$total_1318 += $value;
												}
											}
									}
									
									if (count($_POST['1522']) !== 0) {
											$total_1522 = 0;
													foreach ($_POST["1522"] as $key=>$val){
														foreach ($val as $key2 => $value) {
															if ($value <0) {$value = 0;}
															$total_1522 += $value;
														}
													}
							    	}

							    	if (count($_POST['2030']) !== 0) {
									    	$total_2030 = 0;
													foreach ($_POST["2030"] as $key=>$val){
														foreach ($val as $key2 => $value) {
															if ($value <0) {$value = 0;}
															$total_2030 += $value;
														}
													}
  									}
							    	if ($total_1015 !== 0) { echo '<tr><td>(10x15), за '.$total_1015.'шт.:</td><td>'.$total_1015*$price['1015'].'грн</td></tr>';	}
							    	if ($total_1318 !== 0) { echo '<tr><td>(13x18), за '.$total_1318.'шт.:</td><td>'.$total_1318*$price['1318'].'грн</td></tr>';}
									if ($total_1522 !== 0) { echo '<tr><td>(15x22), за '.$total_1522.'шт.:</td><td>'.$total_1522*$price['1522'].'грн</td></tr>';}
									if ($total_2030 !== 0) { echo '<tr><td>(20x30), за '.$total_2030.'шт.:</td><td>'.$total_2030*$price['2030'].'грн</td></tr>';}
  									
  									$total = $total_e_photo   
  									       + $total_fotokniga 
  									       + $total_1015      * $price['1015']
  									       + $total_1318      * $price['1318']
  									       + $total_1522      * $price['1522']
  									       + $total_2030      * $price['2030'];
									   
  									echo '<tr style="color:#FFA502;"><td><strong>Итого: </strong></td><td><strong>'.$total. 'грн.</strong></td></tr>';

  									echo '</table>';


  									echo '<table class=user-information>';
  									echo '<form method = "post">';
  									echo '<tr><th colspan = "2">Заполните необходимые поля:</th></tr>';
  									echo '<tr><td>Ваше имя: </td><td class="name-of-user">'.$current_user->display_name.'('.$current_user_name.')'.'</td></tr>';
  									echo '<tr><td><label for="tel">* Телефон :</label></td><td><input type ="text" name="tel" id="tel" class="redClass" placeholder = "380487777777" required onchange="RemoveClass(redClass);"></td></tr>';	
  									echo '<tr><td><label for="email">* E-mail :</label></td><td><input type ="text" name="email" value='.$current_user->user_email.'></td></tr>';
  									echo '<tr><td><label for="agree">Я,'.$current_user->display_name.', подтверждаю заказ:</td><td> 
  									      <input type ="submit" class="submit-order"  name="agree" value="Подтверждаю"></label>';
  									echo '<input type="hidden" name="submitted-val" value="false">';
  									echo '<input type="hidden" name="total_amount" value='.$total.'>';
  									echo '<input type="hidden" name="num_photosession" value='.$_POST['num_photosession'].'></td></tr>';
  									echo '</table>';
  									echo '* - Обязательные поля.';	

  									

  				 } else { 
  				 		
  				 		$new_order = get_option('orders_'.$name_photographer);
  				 		
  				 		
  				 		if ( !is_in_set($current_user_name, $new_order[$num_photosession]['name_of_customer']) ) {
  				 				
  				 				$new_order[$num_photosession]['name_of_customer'][] = $current_user_name;
  				 		}		
  				 	
						update_option('orders_'.$name_photographer, $new_order);
						$submitted_order = get_option('user_cart_'.$num_photosession.'_'.$current_user_name) ;
						$submitted_order['submitted-val'] =true;
						$submitted_order['total']=$_POST['total_amount'];
						$submitted_order['tel'] = $_POST['tel'];
						$submitted_order['email'] = $_POST['email'];
						$submitted_order['date'] = date_i18n('j F Y');
						
						update_option('user_cart_'.$num_photosession.'_'.$current_user_name, $submitted_order);

						/*display_one_order ($submitted_order, $current_user_name, $num_of_photosessions);*/
						$user_meta = get_user_by('login', $name_photographer );

						mail($user_meta->user_email, "Ваши новые заказы", "У Вас новый заказ! 	http://www.fotozakaz.com.ua/?page_id=507");
  				 		
  				 		echo '<a class="button-back-spasibo" href="http://www.fotozakaz.com.ua/"><div class="thanks_for_shopping">Спасибо за заказ!</div></a>';
  				 		echo '<a class="button-back" href="http://www.fotozakaz.com.ua/">Вернуться назад</a>';
  				 		}

?>	
                
			
		</form> 
		</div></div>
	<?php endwhile; // end of the loop. ?>
		</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->
<script>


</script>
<?php get_sidebar(); ?>

<?php get_footer(); ?>