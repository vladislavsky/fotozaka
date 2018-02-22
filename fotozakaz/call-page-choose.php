



<?php

  global $current_user;
  get_currentuserinfo();

  $current_user_login = $current_user->user_login;
  
  var_dump($current_user->roles[0]);
  
  /*
  * @  $current_user_main_dir - main folder of the user
  * @   $file_path - full path for the photosession
  */                           
   $photosession_num_name_of_dir = $_POST["photosession_num"];                           
   $current_user_main_dir = ABSPATH .'wp-content/uploads/' . $current_user_login;                     

   $file_path = $current_user_main_dir .'/'. $photosession_num_name_of_dir.'/' ;
   
   

   display_photo ( $current_user_login, $photosession_num_name_of_dir );



?>