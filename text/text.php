<?php

if (isset($_SERVER['HTTP_REFERER'])) {
    $back = '<p><a href="' . $_SERVER['HTTP_REFERER'] . '" class="back">Powrót</a></p>';
} else {
    $back = null;
}

$text[0] = 'Wypełnij wszystkie pola formularza.' . $back;
$text[1] = 'Zalogowałeś się pomyślnie. Za chwile nastąpi przekierowanie.';
$text[2] = 'Brak podanego użytkownika w bazie danych.' . $back;
$text[3] = 'Niepoprawny adres E-mail.' . $back;
$text[4] = 'Podany login jest już zajety.' . $back;
$text[5] = 'Podany E-mail jest już zajęty.' . $back;
$text[6] = 'Zarejestrowano pomyślnie. Teraz możesz się zalogować.';
$text[7] = 'Rejestracja zakończyła się niepomyślnie. Spróbuj ponownie za jakiś czas.' . $back;
$text[8] = 'Bledne potwierdzenie hasla';
$text[9] = 'Aktywacja konta przebiegla pomyslnie. Zostales automatycznie zalogowany';
$text[10]= 'Blad podczas aktywacji, skontaktuj sie z supportem';
$text[11]= 'Przed zalogowaniem potwierdz swoj email, link weryfikacjy zostal wyslany na email uzyty przy rejestracji';
$text[12]= 'Podcast dodano pomy�lnie';
$text[13]= 'Wystapil blad podczas dodawania podcastu';
$text[14]= 'Utw�r jest za d�ugi - maxymalny czas trwania pliku mp3 wynosi 130 minut - lub jest w jako�ci gorszej ni� 320kb/s';
$text[15]= 'Za male rozmiary grafiki (min 400x400)';
$text[16]= 'Niepoprawny format pliku. Dozwolone s� 2 formaty: MP3 i JPG';
$text[17]= 'Za du�e rozmiary grafiki (max 1000x1000)';
?>