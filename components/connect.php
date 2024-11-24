<?php
   $db_host = "buglunlj56o7epyffth8-mysql.services.clever-cloud.com":
   $db_name = 'buglunlj56o7epyffth8';
   $db_user_name = 'uw4lxedjnj3pfjwp';
   $db_user_pass = 'MNpAQwaif8tQiMKloABA';

  // $conn = new PDO($db_name, $db_user_name, $db_user_pass);
   $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user_name, $db_user_pass);

   function create_unique_id(){
      $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
      $rand = array();
      $length = strlen($str) - 1;

      for($i = 0; $i < 20; $i++){
         $n = mt_rand(0, $length);
         $rand[] = $str[$n];
      }
      return implode($rand);
   }

?>
