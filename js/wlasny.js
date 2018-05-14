$(document).ready(function() {
 
 //walidacja formularzy
   
 function walidacjaFormularza(id, ilosc_znakow, komunikat_ok, komunikat_blad, wzor){
    $(id).on('blur', function() {
		var input = $(this);
        
        if (typeof wzor != 'undefined') { //uznajemy że jest to email i nie sprawdzamy innych warunków
           var email = wzor.test(input.val()); 
           
           if(email){
    			input.removeClass("invalid").addClass("valid");
    			input.next('.komunikat').text(komunikat_ok).removeClass("blad").addClass("ok");
		   }
    		else {
    			input.removeClass("valid").addClass("invalid");
    			input.next('.komunikat').text(komunikat_blad).removeClass("ok").addClass("blad");
    		}
           return;
        } 
        
        var input_length = input.val().length;
        
		if(input_length >= ilosc_znakow[0] && input_length <= ilosc_znakow[1]){
			input.removeClass("invalid").addClass("valid");
			input.next('.komunikat').text(komunikat_ok).removeClass("blad").addClass("ok");
		}
		else{
			input.removeClass("valid").addClass("invalid");
			input.next('.komunikat').text(komunikat_blad).removeClass("ok").addClass("blad");
			
		}
});   
 }
 
walidacjaFormularza('#username', [5,20], "Wprowadzono poprawną nazwę użytkownika.", "Nazwa użytkownika musi mieć od 5 do 20 znaków.");
walidacjaFormularza('#password', [5,25], "Wprowadzono poprawne hasło!", "Hasło musi mieć od 5  do 25 znaków.");
walidacjaFormularza('#email', [1,255], "Wprowadzono poprawny email!", "Podany email jest nieprawidłowy.", /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i);


$('#zarejestruj').click(function(event){
			var username = $('#username');
			var password = $('#password');
            var email = $('#email');
			
			
			if(username.hasClass('valid') && password.hasClass('valid') && email.hasClass('valid')){
				alert("Wprowadzono poprawne dane!");	
			}
			else {
				event.preventDefault();
				alert("Uzupełnij wszystkie pola!");	
			}
});

zmienna = $('.ul').attr('id'); //pobranie id
 
$('.add').on('click', function() {
    var element = $(this);
    var uid = zmienna; //przekazanie do zmiennej uid id użytkownika
    var stat = $(element).attr('name'); //pobranie atrybutu name, który mówi o typie aktualizacji
    var posting = $.post( 'updateStats/', { user: uid, type: stat } ); //wysłanie żądania z parametrami uid i stat
   
    posting.done(function( data ) { //po zakończeniu żądania
        if(data != false){  //dodano statystykę
            var add_stat = parseInt($(element).parent().find('span').text()); //pobranie aktualnej statystyki
            var take_gold = parseInt($('#zloto').text()) - add_stat * 2; //obliczenie wydanego złota
            $(element).parent().find('span').text(add_stat + 1); //aktualizacja poziomu statystyki
            $('#zloto').text(take_gold); //aktualizacja poziomu złota
            $.smkAlert({text:'Pomyślnie dodano punkt!', type:'success'}); //wyświetlenie komunikatu o powodzeniu
        }
        else {
            $.smkAlert({text:'Nie masz wystarczająco złota!', type:'danger'}); //wyświetlenie komunikatu o niepowodzeniu
        }
  });   
 });
 
 zmienna2 = $('.praca button').attr('id'); //pobranie id
 
 $('.praca button').on('click', function() {
    var element = $(this);
    var uid = zmienna2; //przekazanie do zmiennej uid id użytkownika
    var hours = $('.hours').val();   
    var posting = $.post( 'toWork/', { user: uid, time: hours } ); //wysłanie żądania z parametrami uid i stat
   
    posting.done(function( data ) { //po zakończeniu żądania
        if(data != false){  //dodano statystykę
            location.reload();
        }
        else {
            
            $.smkAlert({text: 'Możesz pracować od 1 do 8 godzin.', type:'danger'}); //wyświetlenie komunikatu o niepowodzeniu
        }
  });   
 });
 
 zmienna3 = $('.sklep').attr('id'); //pobranie id
 
 function buyItem(UID, NAME, PRICE, DEFENSE, ATTACK){
        $('.sklep_' + NAME).on('click', function() {
        var element = $(this);
       
        var posting = $.post( 'buyItem/', { user: UID, name: NAME, price: PRICE, defense: DEFENSE, attack: ATTACK } ); //wysłanie żądania z parametrami
       
        posting.done(function( data ) { //po zakończeniu żądania
            if(data != false){  //dodano przedmiot
                $.smkAlert({text: 'Kupiono przedmiot.', type:'success'}); //wyświetlenie komunikatu o powodzeniu
            }
            else {
                
                $.smkAlert({text: 'Nie masz wystarczająco złota.', type:'danger'}); //wyświetlenie komunikatu o niepowodzeniu
            }
      });   
     });
 }
 
 buyItem(zmienna3, 'rapier', 75, 0, 10);
 buyItem(zmienna3, 'płaszcz', 50, 5, 0);
 
 
 function manageItem(UID, NAME, ACTION){
        $('.' + ACTION + '_' + NAME).on('click', function() {
        var element = $(this);
        var ITEM_ID = element.attr('name');
        var posting = $.post( 'ekwipunek/', { user: UID, name: NAME, action: ACTION, id: ITEM_ID } ); //wysłanie żądania z parametrami
       
        posting.done(function( data ) { //po zakończeniu żądania
            if(data != false){  //powodzenie
                location.reload();
            }
            else {
                
                $.smkAlert({text: 'Nie możesz tego teraz zrobić.', type:'danger'}); //wyświetlenie komunikatu o niepowodzeniu

    
            }
      });   
     });
 }
 
  manageItem(zmienna, 'rapier', 'equip');
  manageItem(zmienna, 'płaszcz', 'equip');
 
  manageItem(zmienna, 'rapier', 'takeoff');
  manageItem(zmienna, 'płaszcz', 'takeoff');
  
  session_id = $('.user_list').attr('id'); //pobranie id

        $('.attack').on('click', function() {
        var element = $(this);
        var defender_id = $(this).attr('name');
        
        var posting = $.post( 'battle/', { attacker: session_id, defender: defender_id } ); //wysłanie żądania z parametrami
       
        posting.done(function( data ) { //po zakończeniu żądania
            if(data.trim() == 'zwyciestwo'){ 
                $.smkAlert({text: 'Zwyciężyłeś. Zdobyto 10 punktów.', type:'success'}); //wyświetlenie komunikatu - zwycięstwo
            }
            else if(data.trim() == 'remis') {              
                $.smkAlert({text: 'Remis. Nie zdobyłeś żadnych punktów.', type:'success'}); //wyświetlenie komunikatu - remis
            }
            else if(data.trim() == 'porazka') {              
                $.smkAlert({text: 'Przegrałeś. Tracisz 5 punktów.', type:'danger'}); //wyświetlenie komunikatu - porażka
            }
            else {
                $.smkAlert({text: 'Nie możesz teraz zaatakować tego gracza.', type:'danger'}); //wyświetlenie komunikatu o niepowodzeniu
            }
      });   
    
 });

         
});