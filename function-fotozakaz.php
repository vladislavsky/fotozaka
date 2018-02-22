<?php




  /*
    @$array_of_files - array of files
    return  summ of sizes
  */
  function count_size_of_files($array_of_files)  {
          $array_size_of_files = $array_of_files['size'];
          
          if($array_size_of_files == NULL) { return false;}
              else { 
                          $size_of_files = 0;
                          foreach ($array_size_of_files as $size_of_file)  {
                        
                                   $size_of_files += $size_of_file;
                          }
                    }       
                                   
            return  $size_of_files;
  }



 function upload_photosession ( $array_of_files, $current_user_login, $photosession_num_name_of_dir ) { 
          /*      if size of files is not zero then go futher                     */
                                   
           $current_user_main_dir = ABSPATH .'wp-content/uploads/' . $current_user_login;                     

           $file_path = $current_user_main_dir .'/'. $photosession_num_name_of_dir.'/' ;

          if ( !count_size_of_files ( $array_of_files ) ) {  return false; } 

          else {
                    /* if count of files is not zero go futher    */
                          if (count( $array_of_files['name']) != 0) {
                                 /*  and less then 20                       */
                                 if (count( $array_of_files['name']) <20 ) { 
                                        /* and size of files less then 45 MB*/
                                         if ($size_of_files < 45000000) {
                                                 /*   request method was post          */
                                                  if( 'POST' == $_SERVER['REQUEST_METHOD']  ) 
                                                    {    /*   and current folder is not MAIN    */ 
                                                       if ( $file_path != $current_user_main_dir.'//') { 
                                                               if (!is_dir($current_user_main_dir)) {  mkdir( $current_user_main_dir );  }
                                                               if (!is_dir( $file_path ) ) { mkdir ( $file_path ); } 
                                                               foreach ($array_of_files['name'] as $key=>$value) 
                                                                  {     if (!move_uploaded_file( $array_of_files['tmp_name'][$key], $file_path . sanitize_file_name( $array_of_files['name'][$key] ))) 
                                                                        {echo "<br/><div>Какие-то проблемы при загрузке!</div><br/>";}
                                                                  }
                                                        } 
                                                  } else { echo '<div style=" color: #333;"> Укажите номер фотосессии.</div>'; }
                                          } else { echo "<div style='background-color: red; color: #fff;'> У Вас превышен лимит по объему загрузки 40Мб. Ваш объем составляет ". round( $size_of_files/1000000, 2) . "Мб! </div>";}
                     
                                 } else { echo "<div style='background-color: red; color: #fff;'> Вы не можете добавлять больше 19 файлов за один раз!</div>"; }
                           } else { echo "<div style='background-color: red; color: #fff;'> Добавьте файлы!</div>"; }
                }                  
  }


/**********************************************************************************************
***                     END code of UPLOADING                                               ***
***********************************************************************************************/



/**********************************************************************************************
***                     DISPLAY PHOTO                                                       ***
***********************************************************************************************/

function display_photo ( $current_user_login, $photosession_num_name_of_dir) {
     
     
     $photographer_name = get_photographer_name($photosession_num_name_of_dir);

     $is_owner_of_album =   get_photographer_name($photosession_num_name_of_dir) == $current_user_login;
 
    /*            MAIN Directory for the current PHOTOGRAPHER                   */
    $current_user_main_dir = ABSPATH .'wp-content/uploads/' . $photographer_name;                     

    /*            PATH FOR THE PHOTOSESSION                                     */
    $file_path = $current_user_main_dir .'/'. $photosession_num_name_of_dir.'/' ;

    /*            WEB ADDRESS for image to add                                   */
    $http_path = "http://fotozakaz.com.ua/wp-content/uploads/".$photographer_name."/".$photosession_num_name_of_dir."/";
    fotosession_info($photosession_num_name_of_dir);
 
    if (!$is_owner_of_album && $photosession_num_name_of_dir !== NULL) {$action_form_redirect = 'action="http://www.fotozakaz.com.ua/?page_id=495"';}
        else {$action_form_redirect = 'action="http://www.fotozakaz.com.ua/?page_id=530"';}
    /*   CHECKING for existing directory            */

            if( is_dir( $file_path ) && $photographer_name != NULL && $photosession_num_name_of_dir != NULL) { 
                 echo '<form '.$action_form_redirect.' method="post">';  
                 echo '<input type="hidden" name = "num_photosession" value='.$photosession_num_name_of_dir.'>';
                 echo '<input type="hidden" name = "name_photographer" value='.$photographer_name.'>';
                 $files = scandir( $file_path);   
                 
                 array_shift($files); 
                 array_shift($files); 
                 echo "<dir class='container-gallery'>";
                 for($i=0; $i<sizeof($files); $i++) {
                      if ($files[$i] != "bat"){
                            echo '<div class="wrapper-of-image">';
                            echo '<center><a class="fancybox gallery-photosession" href="'
                                .$http_path.$files[$i].'">'.'<img title ="'.$files[$i].'" class="fancyimg" src="'
                                .$http_path.$files[$i].'" /></a></center>';

                            echo '<div class="wrapper_of_input">';  
                            echo '<div class="wrapper_of_input_left">';
                            if (!$is_owner_of_album) {
                            echo '<label for="e_photo[]"> Купить <input type="checkbox" name="e_photo[]" value='
                                 .$files[$i].'></label><br/>';
                            echo '<label for="fotokniga[]"> Для фотокниги <input type="checkbox" name="fotokniga[]" value='
                                 .$files[$i].'></label><br/>';       
                               } else {
                                        echo '<center>'.$files[$i];
                                        echo ' <div style="color:tomato;">(delete <input style="border: 1px solid red;" type = "checkbox" 
                                                name="deletefiles[]" value="'.$files[$i].'"> )</div></center>'; 
                                        }         
                            echo '</div>';
                            echo '</div>';
                            echo '</div>'; 
                      } 
                  }
                  echo "</div>";
        
                  if (!$is_owner_of_album && $photosession_num_name_of_dir !== NULL) {echo '<input type="submit" name="submit-checked" value="Продолжить заказ">';} 
                  else {echo '<input type="submit" name="delete_photos" value="Удалить фотографии">';}
            }
      echo "</form>";
    }  


    function delete_photos(){
      
      $current_user_main_dir = ABSPATH .'wp-content/uploads/' . $_POST['name_photographer'];                     
      $file_path = $current_user_main_dir .'/'.$_POST['num_photosession'].'/' ;
      $list_of_failure = array();
      foreach ($_POST['deletefiles'] as $key=>$value) {
         
          if (!unlink($file_path.$value)) { echo "<p>Не могу удалить файл: ".$value."</p>"; array_push($list_of_failure, 'false'); }
      }
          if (!array_pop($list_of_failure)) {echo '<div class="thanks_for_shopping">Файлы были успешно удалены!</div>';}

    }
/**********************************************************************************************
***                     END code of DISPLAY PHOTO                                              ***
***********************************************************************************************/


  
/*
@param $photosession_num - number of photosession
return name of photographer
if we can't find anithing then return false

*/


  function get_photographer_name($photosession_num) {

       $photosessions_all = get_option(photograph_photosession_nums);

   
       for ($i=0; $i<= count($photosessions_all); $i++)  {
   
            if ($photosessions_all[$i]['num_photosession'] == $photosession_num) { return $photosessions_all[$i]['name_photographer']; }
       }
  return false;
  }
                  
  /*
   @param $num_photosession, $name_photographer, $description for adding this parameter to the option
   if $num_photosession exist then return false
   else true
  */

  function create_album_with_number_of_photosession ($num_photosession, $name_photographer, 
                                        $description, $password) {
      

            if (check_for_existing_num_photosession ($num_photosession)) {
                echo '<div style="background-color: blue; color: white;">Такой номер уже существует!</div>'; 
                return false;
            }
      
           $photosessions_all = get_option(photograph_photosession_nums);
           $photosessions_all[]=
                                ['num_photosession'=>$num_photosession, 
                                'name_photographer'=>$name_photographer, 
                                'description'=>$description,
                                'password' => $password];
            update_option('photograph_photosession_nums', $photosessions_all);

            return true;
  }


  /*
  @param name of photographer
  retur true if everithing is OK
  false if exist with this name

  */
  
  function add_user_info($name, $email, $phone, $password, $photographer = false) {
    
    $user_info = array();
    for ($i=0; $i<= count($user_info); $i++) {
        if ($name == NULL || $email == NULL || $phone == NULL || 
            $user_info['name'] == $name || $user_info['email'] == $email )
        {return false;}
    }
    $user_info[] = ['name' => $name, 
                    'email' => $email, 
                    'phone' => $phone, 
                    'password' => $password, 
                    'photographer' => $photographer];
    return true;

  }

  function list_of_albums($current_user, $delete_button_on_show = false, $save_button_on_show = false){
    $list_of_albums = get_option(photograph_photosession_nums);

    
        $i=0;
        foreach ($list_of_albums as $album) {
                if ($album['name_photographer'] == $current_user) {

                  echo '<div class = "list-of-albums"><form  method="post" name="maintain_album_'.$i.'">';
                  echo '<label for="num_photosession">Номер фотосессии: <input type="text" name="num_photosession" readonly value="'.$album['num_photosession'].'"></label>';

                  echo '<label for="description">Описание: <input type="text" name="description" value="'.$album['description'].'"></label>';

                  echo '<label for="password" >Пароль: <input class="input-password" type="text" name="password" value="'.$album['password'].'"></label>';
                  if ($delete_button_on_show) {
                                               echo '<input class="tiny-button" value="Удалить" type=submit name="submit_delete_album"><hr>';
                                             }
                  if ($save_button_on_show) {
                                               echo '<input class="tiny-button" value="Сохранить" type=submit name="submit_save_album"><hr>';
                                             }
                  echo '</form></div>';
                  $i++;
                }
        }
  }



  function add_new_album( $post, $current_user_login ){

    if($post != NULL) {
            create_album_with_number_of_photosession ($post['num_photosession'], $current_user_login, 
                                                      $post['description'], $post['password']);
    }
   
   
        echo '<hr><form  method="post" name="add_new_album">';


        echo '<hr><label for="num_photosession">Номер фотосессии: <input type="text" name="num_photosession" value=""></label>';

        echo '<label for="description">Описание: <input type="text" name="description" value=""></label>';

        echo '<label for="password">Пароль: <input type="text" name="password" value=""></label>
        <input class="tiny-button" value="Создать" type=submit name="submit_add_new_album">
        </form><hr/>';
  }
  function delete_album($num_of_album, $current_user){
    
     /*            MAIN Directory for the current PHOTOGRAPHER                   */
    $current_user_main_dir = ABSPATH .'wp-content/uploads/' . $current_user;                     

    /*            PATH FOR THE PHOTOSESSION                                     */
    $dir_path = $current_user_main_dir .'/'. $num_of_album.'/' ;

    $list_of_albums = get_option(photograph_photosession_nums);
    
    $new_list_of_albums = array();
    
    if (is_dir($dir_path)) { deleteDirectory($dir_path); $dir_exist = true; } 
        else {$dir_exist = false;}
    
        foreach ($list_of_albums as $album) {

              
              if ( 
                  $album['num_photosession']  != $num_of_album) {
                  $new_list_of_albums[] = $album;
                  

              } 
        }
        

    update_option(photograph_photosession_nums, $new_list_of_albums);
    return $dir_exist;
  }



  function list_of_albums_options($current_user){
      $list_of_albums = get_option(photograph_photosession_nums);
   
      echo '<hr><form  method="post" name="select_album">';
      echo '<select name="num_photosession" class="select-num-photosession">';
      $i=0;
          foreach ($list_of_albums as $album) {
            if ($album['name_photographer'] == $current_user) {
              echo '<option value="'.$album['num_photosession'].'">'.$album['num_photosession'].'</option>';
            }
          }  
      echo '</select>';
      echo '<input type="submit" name="submit" value="Продолжить">';
      echo '</form>';
  }
 


   function select_num_photosession_current_user($current_user, $num_of_album) {
       
        $list_of_albums = get_option(photograph_photosession_nums);
        echo '<select name="photosession_num" class="select-num-photosession">';
        $i=0;
            foreach ($list_of_albums as $album) {
              if ($album['name_photographer'] == $current_user) {
                  if ($num_of_album == $album['num_photosession']) {
                      echo '<option selected="selected" value="'.$album['num_photosession'].'">'.$album['num_photosession'].'</option>';
                  }  else {
                      echo '<option value="'.$album['num_photosession'].'">'.$album['num_photosession'].'</option>';
                  }
              }

            }  
        echo '</select>';
    }

    function check_for_existing_num_photosession ($num) {

            $photosessions_all = get_option(photograph_photosession_nums);

            foreach ($photosessions_all as $photosession) {

                    if ($num == $photosession['num_photosession'] ) {return true;}

            }
            return false;
    }



    function deleteDirectory($dir) {
          if (!file_exists($dir)) {
              return true;
          }

          if (!is_dir($dir)) {
              return unlink($dir);
          }

          foreach (scandir($dir) as $item) {
              if ($item == '.' || $item == '..') {
                  continue;
              }

              if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                  return false;
              }

          }

          return rmdir($dir);
    }

  function save_album ($num_of_album, $current_user) {
    
        $list_of_albums = get_option(photograph_photosession_nums);
        
        $new_list_of_albums = array();

        $saved = false;
        foreach ($list_of_albums as $album) {

                  
                  if ( $album['num_photosession']  != $num_of_album) {
                       $new_list_of_albums[] = $album;
                  }  else {
                           $new_list_of_albums[] = ['num_photosession'  => $album['num_photosession'], 
                                                    'name_photographer' => $album['name_photographer'], 
                                                    'description'       => $_POST['description'], 
                                                    'password'          => $_POST['password'], ] ;
                 $saved = true;          
                  }
         }
        update_option(photograph_photosession_nums, $new_list_of_albums);
    return $saved;
  }

        # This is a auxillary function for the next function
        function is_in_set($file_name, $list_of_files) {
          if ($file_name == NULL || $list_of_files == NULL) { return false; }
            foreach ( $list_of_files as $key => $value ) {
               if ( $file_name == $value ) { return true; }
            }
            return false;
          }

        /*
          Create an array of key names of chosen photo

        */
        function make_a_list_of_chosen_files( $e_photo, $fotokniga ) {
              
              $list_of_files = array();

              if ( $e_photo !== NULL ) { 

                    foreach ( $e_photo as $file_name ) { $list_of_files[] = $file_name; }
              }

              if ( $fotokniga !== NULL) { 
                    foreach ( $fotokniga as $file_name ) {
                    
                            if ( !is_in_set( $file_name, $list_of_files ) ) {
                                               
                                  $list_of_files[] = $file_name;
                            }
                    }
              }
              if ( $list_of_files == NULL) {return false;}
              return $list_of_files;
        }


        function converted_array( $e_photo, $fotokniga  ) {

          $list_of_files = make_a_list_of_chosen_files( $e_photo, $fotokniga );

          $array_of_choices = array();
              
              if ($list_of_files) {

                      foreach ( $list_of_files as $file ) {
                        $e_photo_print = false;
                        $fotokniga_print = false;
                        if ( is_in_set( $file, $_POST['e_photo'] ) )   {  $e_photo_print = true;}
                        if ( is_in_set( $file, $_POST['fotokniga'] ) ) {  $fotokniga_print = true;} 

                        $array_of_choices[$file] = [ 'e_photo'   => $e_photo_print, 
                                                     'fotokniga' => $fotokniga_print ] ;
                        
                      }
              }
          return $array_of_choices;
        }

        function fotosession_info($num_photosession){

             $photosessions_all = get_option(photograph_photosession_nums);

               for ($i=0; $i<= count($photosessions_all); $i++)  {

                    if ($photosessions_all[$i]['num_photosession'] == $num_photosession) 
                          { echo '<span style= "color: #FFA502;"><strong>Описание фотосессии: </strong></span>'.$photosessions_all[$i]['description'].'<br>';
                            echo '<span style= "color: #FFA502;"><strong>Номер Фотосессии: </strong></span>'.$photosessions_all[$i]['num_photosession'].'<br>';
                            echo '<span style= "color: #FFA502;"><strong>Фотограф: </strong></span>'.$photosessions_all[$i]['name_photographer'].'<hr>';

                            return true; }
               }
                     return false;

        }

      function fotozakaz_check_password ($password, $num_photosession) {

           $photosessions_all = get_option(photograph_photosession_nums);

               for ($i=0; $i<= count($photosessions_all); $i++)  {

                    if ($photosessions_all[$i]['num_photosession'] == $num_photosession) 
                          {if ($photosessions_all[$i]['password'] == $password) { return true; }
                    }
               }
      return false;
      }
      function fotozakaz_using_password ($num_photosession) {

           $photosessions_all = get_option(photograph_photosession_nums);

               for ($i=0; $i<= count($photosessions_all); $i++)  {

                    if ($photosessions_all[$i]['num_photosession'] == $num_photosession) 
                          {if ($photosessions_all[$i]['password'] == '') { return true; }
                    }
               }
      return false;
      }

/*<form method="post"><input type="hidden" name="option_name" value="'.$user_cart_option_name.
                                        '"><input type="submit" name="delete_option" value="Удалить заказ">';*/
      function display_orders ($orders) {
            if ($_POST['delete_option'] !== NULL ) {delete_option($_POST['option_name']);}
            if ($orders == NULL)  {return false;} 
              foreach( $orders as $num_of_photosessions=>$photosession) {
                  
                  foreach ($photosession as $name_of_user => $users) {
                          
                          foreach ($users as $user_had_an_order) {
                            echo display_one_order_remove_button ($user_had_an_order, $num_of_photosessions);
                          }
                  }
              }
      }

            function display_all_orders ($orders) {
            if ($_POST['delete_option'] !== NULL ) {delete_option($_POST['option_name']);}
            if ($orders == NULL)  {return false;} 
              foreach( $orders as $num_of_photosessions=>$photosession) {
                  
                  foreach ($photosession as $name_of_user => $users) {
                          
                          foreach ($users as $user_had_an_order) {
                            echo display_one_order_show_button ($user_had_an_order, $num_of_photosessions);
                          }
                  }
              }
      }


      function display_one_order_remove_button ($user_had_an_order, $num_of_photosessions) {
        
        $user_cart_option_name = 'user_cart_'.$num_of_photosessions.'_'.$user_had_an_order;
        $user_cart = get_option($user_cart_option_name);
        
        if ($user_cart['have_been_done'] == NULL) { $user_cart['have_been_done'] = false; 
            update_option($user_cart_option_name, $user_cart); }
        if($_POST['user_cart_option_name'] == $user_cart_option_name && $_POST['have_been_done'] == 'Выполнено') { 
                  $user_cart['have_been_done'] = true;
                  update_option($user_cart_option_name, $user_cart);
          }

        $output = '';
        if ($user_cart['have_been_done']) {return false;}
            /*var_dump( $_POST['user_cart_option_name'], " | ", $user_cart_option_name);*/
        $button_remove_show = display_button_remove_order ($num_of_photosessions, $user_cart_option_name);
        echo display_one_order( $user_cart, $user_had_an_order, $num_of_photosessions, $button_remove_show );
      return $output;

      }

      function display_one_order_show_button ($user_had_an_order, $num_of_photosessions) {
        
        $user_cart_option_name = 'user_cart_'.$num_of_photosessions.'_'.$user_had_an_order;
        $user_cart = get_option($user_cart_option_name);
        if ($user_cart['have_been_done'] == NULL) { $user_cart['have_been_done'] = false; 
            update_option($user_cart_option_name, $user_cart); }
        if($_POST['user_cart_option_name'] == $user_cart_option_name && $_POST['have_been_done'] == 'Показать Ордер') { 
                  $user_cart['have_been_done'] = false;
                  update_option($user_cart_option_name, $user_cart);
          }

        $output = '';
        if ($user_cart['have_been_done']) {
              $button_remove_show = display_button_show_order ($num_of_photosessions, $user_cart_option_name);
            } else { $button_remove_show = '';}
        echo display_one_order( $user_cart,$user_had_an_order, $num_of_photosessions, $button_remove_show);
      return $output;

      }

function display_button_remove_order ($num_of_photosessions, $user_cart_option_name) {
      $output = '';
      $output .= '<form method="post" name='.$num_of_photosessions.'>';
      $output .= '<input type="submit" name="have_been_done" value="Выполнено">';
      $output .= '<input type="hidden" name='.'user_cart_option_name'.
                          ' value='.$user_cart_option_name.'>';
      $output .= '</form>';
      return $output;

}

function display_button_show_order ($num_of_photosessions, $user_cart_option_name) {
      $output = '';
      $output .= '<form method="post" name='.$num_of_photosessions.'>';
      $output .= '<input type="submit" class="button_have_been_done" name="have_been_done" value="Показать Ордер">';
      $output .= '<input type="hidden" name='.'user_cart_option_name'.
                          ' value='.$user_cart_option_name.'>';
      $output .= '</form>';
      return $output;

}

function filename_without_extention ($string) {
  $string_new ='';
  
    $end_of_extention = true;
    $i=strlen($string)-3;
    while ( $end_of_extention && $i!==0) {
      
           if ($string[$i] == '.' ) {
                 $end_of_extention = false;
            }
            $i--;
    }
    for ($index = 0; $index <= $i; $index++) {
        $string_new .= $string[$index];
    }
    return $string_new;
}

function display_one_order ( $user_cart, $user_had_an_order, $num_of_photosessions, $button_remove_show ){
$bat_file =":: Copyright Mikhailovsky Sergey <http://AStudio.in.ua/>

:: Permission is hereby granted, free of charge, to any person obtaining
:: a copy of this software through downloading on fotozakaz.com.ua 
:: and associated documentation files (the
:: \"Software\"), to deal in the Software without restriction, including
:: without limitation the rights to use, copy, no modify, no merge, no publish,
:: no distribute, no sublicense, and/nor sell copies of the Software:

:: The above copyright notice and this permission notice shall be
:: included in all copies or substantial portions of the Software.

REM THE SOFTWARE IS PROVIDED \"AS IS\", WITHOUT WARRANTY OF ANY KIND,
REM EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
REM MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
REM NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
REM LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
REM OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
REM WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
md ".$num_of_photosessions."
md ".$num_of_photosessions."\\".sanitize_file_name($user_had_an_order)."
mode con: cp select=65001\r\n";
 if ( $user_cart['submitted-val']) { 
        
        $output .='<table class= "user_order">';
        $output .='<caption>Заказ пользователя: ';
        $output .= $user_had_an_order.', Дата: '.$user_cart['date'].', Фотосессия №: '.$num_of_photosessions.', ';
        if ($user_cart['tel'] != '') { $output .='Телефон: '.$user_cart['tel'].', ' ;}
        $output .='e-mail: '.$user_cart['email'].'</caption>';
        $output .='<tr><th class="naimenovanie">Наименование </th><th> Название файлов</th><th class="kolichestvo">Количество</th></tr>';
      if ($user_cart['e_photo']) {
        foreach ($user_cart['e_photo'] as $key => $value) {
                $e_photo_string .=  $value.', '; 
                
                $file_for_bat = filename_without_extention($value);
                $bat_file .= 'copy "'.$file_for_bat.'.*" "'.$num_of_photosessions.
                              "\\".sanitize_file_name($user_had_an_order)."\\".$file_for_bat.' -- Электронная версия --.*" '."\r\n"; 
        }
        $output .= '<tr><td>Электронная версия</td><td>'.$e_photo_string.'</td><td>'.count($user_cart['e_photo']).'</td></tr>';
      }
      
      if ($user_cart['fotokniga'] !== NULL) {
        foreach ($user_cart['fotokniga'] as $key => $value) {
                $fotokniga_string .=  $value.', ';
                 
                $file_for_bat = filename_without_extention($value);
                $bat_file .= 'copy "'.$file_for_bat.'.*" "'.$num_of_photosessions.
                             "\\".sanitize_file_name($user_had_an_order)."\\".$file_for_bat.' -- В альбоме --.*" '."\r\n"; 
        }
        $output .= '<tr><td>Фотокнига</td><td>'.$fotokniga_string.'</td><td>'.count($user_cart['fotokniga']).'</td></tr>';
      }

        $total_amount = 0;
        foreach ($user_cart['1015'] as $key => $value) {
                
                        foreach ($value as $name_of_file => $amount) {
                              $total_amount+=$amount;
                              if ($amount != 0) {   
                                    $_1015_string .=  $name_of_file.'(Количество: '.$amount.'), '; 
                                    
                $file_for_bat = filename_without_extention($name_of_file);
                $bat_file .= 'copy "'.$file_for_bat.'.*" "'.$num_of_photosessions.
                             "\\".sanitize_file_name($user_had_an_order)."\\".$file_for_bat.' -- 10x15 -- '.$amount.'шт.*" '."\r\n"; 
                              }
                

                        }
        }
        if ($total_amount != 0){ 
                $output .= '<tr><td>Печать фото 10х15 </td><td>'.$_1015_string.'</td><td>'.$total_amount.'</td></tr>';
        }

        $total_amount = 0;
        foreach ($user_cart['1318'] as $key => $value) {
                
                        foreach ($value as $name_of_file => $amount) {
                              $total_amount+=$amount;
                              if ($amount != 0) {   
                                    $_1318_string .=  $name_of_file.'(Количество: '.$amount.'), '; 
                $file_for_bat = filename_without_extention($name_of_file);
                $bat_file .= 'copy "'.$file_for_bat.'.*" "'.$num_of_photosessions.
                             "\\".sanitize_file_name($user_had_an_order)."\\".$file_for_bat.' -- 13x18 -- '.$amount.'шт.*" '."\r\n"; 
                              }
                

                        }
        }
        if ($total_amount != 0){ 
                $output .= '<tr><td>Печать фото 13х18 </td><td>'.$_1318_string.'</td><td>'.$total_amount.'</td></tr>';
        }

        $total_amount = 0;
        foreach ($user_cart['1522'] as $key => $value) {
                
                        foreach ($value as $name_of_file => $amount) {
                              $total_amount+=$amount;
                              if ($amount != 0) {   
                                    $_1522_string .=  $name_of_file.'(Количество: '.$amount.'), '; 
                $file_for_bat = filename_without_extention($name_of_file);
                $bat_file .= 'copy "'.$file_for_bat.'.*" "'.$num_of_photosessions.
                             "\\".sanitize_file_name($user_had_an_order)."\\".$file_for_bat.' -- 15x22 -- '.$amount.'шт.*" '."\r\n"; 
                              }
                

                        }
        }
        if ($total_amount != 0){ 
                $output .= '<tr><td>Печать фото 15х22 </td><td>'.$_1522_string.'</td><td>'.$total_amount.'</td></tr>';
        }

        $total_amount = 0;
        foreach ($user_cart['2030'] as $key => $value) {
                
                        foreach ($value as $name_of_file => $amount) {
                              $total_amount+=$amount;
                              if ($amount != 0) {   
                                    $_2030_string .=  $name_of_file.'(Количество: '.$amount.'), '; 
                $file_for_bat = filename_without_extention($name_of_file);
                $bat_file .= 'copy "'.$file_for_bat.'.*" "'.$num_of_photosessions.
                             "\\".sanitize_file_name($user_had_an_order)."\\".$file_for_bat.' -- 20x30 -- '.$amount.'шт.*" '."\r\n";  
                              }
                

                        }
        }
        if ($total_amount != 0){ 
                $output .='<tr><td>Печать фото 20х30 </td><td>'.$_2030_string.'</td><td>'.$total_amount.'</td></tr>';
        }                          

$link = '';

                  global $current_user;
                  get_currentuserinfo();
                  $current_photographer_main_dir = ABSPATH .'wp-content/uploads/' . $current_user->user_login;                     
                  $http_path = "http://fotozakaz.com.ua/wp-content/uploads/".$current_user->user_login."/"
                               .$num_of_photosessions."/bat/".$num_of_photosessions."--".$user_had_an_order.".bat";

                  $file_path_dir = $current_photographer_main_dir .'/'. $num_of_photosessions.'/';
                  $file_path = $file_path_dir.'bat/' ;
                  
                  if (is_dir($file_path_dir)) {
                    if(!is_dir($file_path)) {mkdir($file_path);}
                  $fp = fopen($file_path.$num_of_photosessions."--".$user_had_an_order.".bat", "w+"); // Открываем файл в режиме записи 
                  

                  $mytext = $bat_file; // Исходная строка
                  $test = fwrite($fp, $mytext); // Запись в файл
                  if (!$test) echo 'Ошибка при записи в файл.';
                  fclose($fp); //Закрытие файла
                  $link = '<a href ="'.$http_path.'">Скачать Файл Автоматизации</a>';
                  } else {$link = ' | Эта фотосессия была удалена!'; 
                  $user_cart_option_name = 'user_cart_'.$num_of_photosessions.'_'.$user_had_an_order;
                  $delete_button_text = '<form method="post"><input type="hidden" name="option_name" value="'.$user_cart_option_name.
                                        '"><input type="submit" name="delete_option" value="Удалить заказ">';
                  }

        $output .= '<tr><td class="download_button">Итого сумма: </td><td class="download_button">'.$user_cart['total'].'грн. '.$link.$delete_button_text.
                   '</td><td>'
                   .$button_remove_show.'</td></tr>';


        $output .='</table>';

        return $output;
  }

}


?>
