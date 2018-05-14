<?php


if(isset($_POST['login']) && isset($_POST['password'])) {
    
    $um = new UserManager;
    
    if($um->LogIn($_POST['login'], $_POST['password'])) { //przekazanie do metody LogIn w klasie UserManager loginu i hasła
        
        header("Location: ".$_SERVER['HTTP_REFERER']); //przekierowanie do gry
        
    } else {
        
        die ("Nieprawidłowa nazwa użytkownika lub hasło."); //nieprawidłowe dane
        
    }
    
} else {
    
    die("DOSTĘP DO TEJ STRONY ZOSTAŁ ZABLOKOWANY!"); //wejście bez formularza
    
}

?>