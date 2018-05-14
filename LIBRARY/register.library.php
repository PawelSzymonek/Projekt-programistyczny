<?php

if((strlen($_POST['username']) >= 5 && strlen($_POST['username']) <= 20) &&
   (strlen($_POST['password']) >= 5 && strlen($_POST['password']) <= 25) &&
    (preg_match('/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i', $_POST['email']))) {
    
    $um = new UserManager;
    
    $res = $um->CreateUser($_POST); //przesłanie tablicy do metody CreateUser w klasie UserManager
    
    if($res) {
         $um->LogIn($_POST['username'], $_POST['password']); //zarejestrowano, logujemy użytkownika
         header("Location: ".$_SERVER['HTTP_REFERER']); //przekierowanie
    } else {
        
        die("Utworzenie użytkownika nie było możliwe!"); //przekierowanie na stronę błędu
        
    }
    
} else {
    die("DOSTĘP DO TEJ STRONY ZOSTAŁ ZABLOKOWANY!"); //wejście poza formularzem
}

?>