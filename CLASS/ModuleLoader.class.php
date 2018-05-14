<?php

class ModuleLoader {

    static public function load($MODULE) {

        switch($MODULE) {

            case 'open':
            echo '
                <!DOCTYPE html>
                <html>
                      <head>
                        <title>Piraci</title>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

                        <link href="css/bootstrap.css" rel="stylesheet" media="screen">
                        <link href="css/style.css" rel="stylesheet" media="screen">
                        <link href="css/responsive.css" rel="stylesheet" media="screen">
                        <link href="css/smoke.min.css" rel="stylesheet" media="screen">

                      </head>
                  <body>
            ';
            break;



            case 'navbar':
            if(isset($_SESSION['uid'])){
                $button = '<button type="button" class="navbar-toggle button_nav" data-toggle="collapse" data-target="#moje-menu">
                                            <span class="sr-only">Nawigacja</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>';

                $menu = '<ul class="nav navbar-nav navbar-right" id="ul_nawigacja">
                                            <li class="active"><a href="statystyki">Statystyki</a></li>
                                            <li><a href="praca">Praca</a></li>
                                            <li><a href="sklep">Sklep</a></li>
                                            <li><a href="walka">Walka</a></li>
                                            <li><a href="ranking">Ranking</a></li>
                                            <li><a href="logout">Wyloguj</a></li>
                                        </ul>';
            } else {
                $button = '';
                $menu = '';
            }

            echo '

              <header id="menu" class="navbar-fixed-top">
                    <div class="container">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <nav class="navbar navbar-inverse" role="navigation" id="pasek_nawi">
                                <div class="container-fluid">
                                    <div class="navbar-header">
                                        '.$button.'
                                        <div id="logo">
                                            <h1>Piraci</h1>
                                        </div>

                                    </div>
                                    <div class="collapse navbar-collapse" id="moje-menu">
                                        '.$menu.'
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </header>

            ';
            break;


            case 'js':
                echo '
                     <script src="js/jquery-2.0.3.min.js"></script>
                    <script src="js/bootstrap.min.js"></script>
                    <script src="js/smoke.min.js"></script>
                    <script src="js/wlasny.js"></script>
                ';
            break;


            case 'statystyki':
             $select = DatabaseManager::selectBySQL("SELECT * FROM stats WHERE id={$_SESSION['uid']}");
             foreach($select as $arr) {
                $stats = $arr;
             }

            echo '
                <section class="content statystyki">
                    <div class="container">
                    	 <h2>Statystyki:</h2>

                         <div class="row">

                            <ul class="ul" id="u_'.$_SESSION['uid'].'">
                                <li><img src="images/punkty.png">Punkty: <span>'.$stats['points'].'</span></li>
                                <li><img src="images/serce.png">Życie: <span>'.$stats['hp'].'</span> <button class="add" name="hp" type="button"><img src="images/dodaj.png"></button></li>
                                <li><img src="images/miecz.png">Atak: <span>'.$stats['attack'].'</span> <button class="add" name="attack" type="button"><img src="images/dodaj.png"></button></li>
                                <li><img src="images/tarcza.png">Obrona: <span>'.$stats['defense'].'</span> <button class="add" name="defense" type="button"><img src="images/dodaj.png"></button></li>
                                <li><img src="images/zloto.png">Złoto: <span id="zloto">'.$stats['gold'].'</span> </li>
                            </ul>
                            <div id="result"></div>
                    	 </div>

                         <div class="row">
                            <h2>Ekwipunek:</h2>';
                            $select2 = DatabaseManager::selectBySQL("SELECT * FROM items WHERE uid={$_SESSION['uid']}");

                            if($select2) {
                                foreach($select2 as $item) {
                                 ModuleLoader::loadItemsEquipment($item['name'], $item['defense'], $item['attack'], $item['is_equipped'], $item['id']);
                                }
                            }
                             else {
                                echo '<h3>Nie masz żadnych przedmiotów.</h3>';
                             }

                    	echo '</div>

                    </div>
                </section>
            ';
            break;

            case 'praca':
            $select = DatabaseManager::selectBySQL("SELECT * FROM work WHERE uid={$_SESSION['uid']}");

            if(!$select){
                $form = '
                            <form>
                               <input type="number" name="hours" class="hours"
                               min="1" max="8" step="1" value="1"> <br />
                              <button type="button" id="u_'.$_SESSION['uid'].'">Pracuj</button>
                            </form>';
            }
            else {
                $form = '';
            }

            echo '

             <section class="content praca">
                    <div class="container">
                    	 <h2>Praca:</h2>

                         <div class="row">
            				'.$form.'
                    	 ';

                             ModuleLoader::load('timer');

            echo '
                        </div>

                   </div>
             </section>
            ';
            break;

            case 'timer':

                $select = DatabaseManager::selectBySQL("SELECT * FROM work WHERE uid={$_SESSION['uid']}");

                if($select) {

                    foreach($select as $arr) {
                       $work = $arr;
                    }

                    $akt = time();
                    $finish = $work['finish_date'];
                    $wynik = $finish - $akt;

                    echo '<script>

                            var seconds = '.$wynik.';
                            function timer() {
                                var days        = Math.floor(seconds/24/60/60);
                                var hoursLeft   = Math.floor((seconds) - (days*86400));
                                var hours       = Math.floor(hoursLeft/3600);
                                var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
                                var minutes     = Math.floor(minutesLeft/60);
                                var remainingSeconds = seconds % 60;
                                if (remainingSeconds < 10) {
                                    remainingSeconds = "0" + remainingSeconds;
                                }

                                if (minutes < 10) {
                                    minutes = "0" + minutes;
                                }
                                document.getElementById("countdown").innerHTML = hours + ":" + minutes + ":" + remainingSeconds;
                                if (seconds == 0) {
                                    clearInterval(countdownTimer);
                                    document.getElementById("countdown").innerHTML = "Zakończono! Odśwież stronę.";
                                } else {
                                    seconds--;
                                }
                            }
                            var countdownTimer = setInterval("timer()", 1000);
                            </script>

                            	<span id="countdown" class="timer"></span>
                            ';
                        }

                        else {
                            echo '';
                        }
            break;



            case 'sklep':

            echo '
                <section class="content sklep" id="u_'.$_SESSION['uid'].'">
                    <div class="container">
                    	 <h2>Sklep:</h2>
                         <div class="row">';

            			   ModuleLoader::loadItemsShop('rapier', 75);
                           ModuleLoader::loadItemsShop('płaszcz', 50);
                    	 echo '</div>

                    </div>
                </section>
            ';

            break;

            case 'walka':
            echo '
                <section class="content walka">
                    <div class="container">
                    	 <h2>Walka:</h2>

                         <div class="row">
                        <div class="col-xs-10 col-sm-6 col-md-6 col-lg-4 col-lg-offset-4 col-md-offset-3 col-sm-offset-3 col-xs-offset-1">
                         <ul class="user_list" id="u_'.$_SESSION['uid'].'">
                         ';

            			$select_users = DatabaseManager::selectBySQL("SELECT * FROM stats WHERE id!='".$_SESSION['uid']."'");

                            if($select_users) {
                                foreach($select_users as $user) {
                                 $username = UserManager::getUsernameById($user['id']);
                                 ModuleLoader::loadUserList($user['id'], $username, $user['points'], true, false);
                                }
                            }

                 echo ' </ul></div></div>

                    </div>
                </section>
            ';
            break;


            case 'ranking':
            echo '
                <section class="content ranking">
                    <div class="container">
                    	 <h2>Ranking:</h2>

                         <div class="row">

                         <div class="col-xs-10 col-sm-6 col-md-6 col-lg-4 col-lg-offset-4 col-md-offset-3 col-sm-offset-3 col-xs-offset-1">
                          <ul class="user_list">
                          ';

                   $select_users = DatabaseManager::selectBySQL("SELECT * FROM stats ORDER BY points desc");

                             if($select_users) {
                                $i = 1;
                                 foreach($select_users as $user) {
                                  $username = UserManager::getUsernameById($user['id']);
                                  ModuleLoader::loadUserList($user['id'], $username, $user['points'], false, $i);
                                  $i++;
                                 }
                             }

                  echo ' </ul></div></div>

                    </div>
                </section>
            ';
            break;

            case 'home':
            echo '
                <section class="content ranking">
                    <div class="container">
                    	 

                         <div class="row">

            				
                    	 </div>


            ';
            break;


      
            case 'rejestracja':
            echo '
                <div class="row rejestracja">
                        <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-lg-offset-3 col-md-offset-3 col-sm-offset-2">
                            <h2>Zarejestruj:</h2>
            				<form action="register/" method="POST" class="home_form">

                                <label for="username">Nazwa użytkownika</label>
                                <input id="username" type="text" name="username">
                                <p class="komunikat"></p>

                                <label for="password">Hasło</label>
                                <input id="password" type="password" name="password">
                                <p class="komunikat"></p>

                                <label for="email">Email</label>
                                <input id="email" type="email" name="email">
                                <p class="komunikat"></p>

                                <input id="zarejestruj" type="submit" name="register" value="Zarejestruj">
                            </form>
                        </div>

                </div>
            ';
            break;

            case 'logowanie':
            echo '
                <div class="row logowanie">
                             <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-lg-offset-3 col-md-offset-3 col-sm-offset-2">
                            <h2>Zaloguj:</h2>
            				<form action="login/" method="POST" class="home_form">

                                <label for="login">Nazwa użytkownika</label>
                                <input id="login" type="text" name="login"> <br/>

                                <label for="haslo">Hasło</label>
                                <input id="haslo" type="password" name="password"> <br/>

                                <input type="submit" name="zaloguj" value="Zaloguj">
                            </form>
                    </div>

                </div>
            ';
            break;



            default;
            break;

        }

    }

    static public function loadItemsShop($item, $cena) {
            echo '
                <div class="item">

                <div class="col-xs-1 col-sm-2 col-md-3 col-lg-3">

                </div>


                <div class="col-xs-5 col-sm-4 col-md-3 col-lg-3">
                    <img src="images/sklep_'.$item.'.png" class="img-responsive">
                </div>

                <div class="col-xs-5 col-sm-4 col-md-3 col-lg-3">
                    <button class="sklep_'.$item.'">Kup <i>'.$item.' ('.$cena.')</i></button>
                </div>


                <div class="col-xs-1 col-sm-2 col-md-3 col-lg-3">

                </div>

                </div>
            ';
    }

    static public function loadItemsEquipment($item, $defense, $attack, $is_equipped, $item_id) {
            echo '
                <div class="item">

                <div class="col-xs-1 col-sm-2 col-md-3 col-lg-3">

                </div>


                <div class="col-xs-5 col-sm-4 col-md-3 col-lg-3">
                    <img src="images/sklep_'.$item.'.png" class="img-responsive">
                </div>

                <div class="col-xs-5 col-sm-4 col-md-3 col-lg-3">';

                if($is_equipped == 0) {
                    echo '<button name="'.$item_id.'" class="equip_'.$item.'">Załóż <i>'.$item.' ('.$attack.' AT, '.$defense.' OB)</i></button>';
                }
                else {
                    echo '<button name="'.$item_id.'" class="takeoff_'.$item.'">Zdejmij <i>'.$item.' ('.$attack.'AT, '.$defense.'OB)</i></button>';
                }

                echo '</div>

                <div class="col-xs-1 col-sm-2 col-md-3 col-lg-3">

                </div>

                </div>
            ';
    }

    static public function loadUserList($uid, $username, $points, $button, $rank) {
            echo '<li>';
                if($rank) {
                    echo $rank.'. ';
                }
                echo $username.' ('.$points.' pkt.)';
                if($button) {
                  echo '<button type="button" class="attack" name="'.$uid.'">Zaatakuj</button>';
                }
                echo '</li>';

    }

}

?>
