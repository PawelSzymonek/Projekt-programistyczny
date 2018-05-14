<?php

if((isset($_POST['user']) && isset($_POST['type']))
                          && ($_POST['type'] == 'hp' ||
                              $_POST['type'] == 'attack' ||
                              $_POST['type'] == 'defense')
                          ) {
    
   $um = new GameManager;
      
      $id = ltrim($_POST['user'], 'u_'); 
      $res = $um->updateStats($id, $_POST['type']); //przesłanie tablicy do metody CreateUser w klasie UserManager
      
      if($res) {
          echo "success";
          exit;
      } else {     
          return false; 
          exit;   
      }
 
    
} else {
    die("DOSTĘP DO TEJ STRONY ZOSTAŁ ZABLOKOWANY!"); //wejście poza formularzem
}

?>