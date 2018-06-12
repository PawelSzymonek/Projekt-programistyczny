<?php

class UserManager {
    
    //utworzenie zmiennych
    protected $login;
    protected $password;
	protected $mail;
    protected $id;
    
    public function LogIn($LOGIN, $PASSWORD) { //przyjmujemy w formularza login i hasło
        
        $this->login = $LOGIN; //przypisanie loginu
        $this->password = $PASSWORD; //przypisanie hasła
        
        
        
        if(self::isExist() && count(self::isExist()) > 0) { //sprawdzenie czy metoda isExist wyszukała użytkownika - wyszukała, logujemy
             $id = self::getIdByUsername(); //pobranie id użytkownika
             $this->id = $id; //przypisanie id
           
            
            self::log_in(); //ustawienie sesji
            return $this->login;
            
        } else {
            
            return false; //nie znaleziono takiego użytkownika
            
        }
        
    }
    
    protected function isExist() { //sprawdzenie czy użytkownik o podanej kombinacji nazwy i hasła istnieje
        
        $arr = DatabaseManager::selectBySQL("SELECT * FROM users WHERE username='".$this->login."' LIMIT 1");
        
        if($arr) {
            foreach($arr as $row) {
                if(password_verify($this->password, $row['password'])){
                   return $arr; //zwrócenie tablicy 
                } else {
                    return false;
                }
            }
        }    
    }
    
        protected function getIdByUsername() { //pobieranie id użytkownika mając jego nick
            
            $array = DatabaseManager::selectBySQL("SELECT * FROM users WHERE username='".$this->login."' LIMIT 1");
            foreach($array as $key) {
                $id = $key['id'];
            }
            return $id; //zwracamy id
            
        }
        
    
    protected function log_in() { //utworzenie sesji
        
        $_SESSION['uid'] = $this->id;
        $_SESSION['logged'] = true;
        
    }
    
    public function LogOut() { //wylogowanie
        
        $_SESSION['uid'] = false; //ustwienie sesji na false
        $_SESSION['logged'] = false; //ustwienie sesji na false
        
        session_destroy(); // zniszczenie sesji
        
    }
    
    
    public function CreateUser($POST) { //rejestracja użytkownika
        
        if(isset($POST) && is_array($POST)) { //sprawdzenie czy została przesłana tablica i czy jest tablicą
          
            
            $res = DatabaseManager::insertInto("users", array("username"=>addslashes($POST['username']), 
                                                              "password"=>password_hash($POST['password'], PASSWORD_BCRYPT),
                                                              "email"=>addslashes($POST['email']))); //dodanie użytkownika do bazy danych
            
            $res2 = DatabaseManager::insertInto("stats", array(
                                                "hp"=>100, 
                                                "attack"=>20,
                                                "defense"=>10,
                                                "gold"=>200,
                                                "points"=>0,
                                                )); //dodanie użytkownika do bazy danych                                   
                                               
            if($res && $res2) { 
                return true; //powodzenie, zwracamy true
            } else {
                return false; //niepowoedzenie, zwracamy false
            }
            
        } else {
            
            return false; // zwracamy false
            
        }       
    }  
    
    
    public function getUsernameById($ID) { //pobieranie nazwy użytkownika mając jego id
            
            $array = DatabaseManager::selectBySQL("SELECT username FROM users WHERE id='".$ID."'");
            foreach( (array)$array as $user) {
                $username = $user['username'];
            }
            return $username; //zwracamy id
            
    }
    
    
}

?>