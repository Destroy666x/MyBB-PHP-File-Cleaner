<?php

/*
Nazwa: Oczyszczacz plików PHP
Autor: Destroy666
Wersja: 1.0
Informacje: Plugin dla skryptu MyBB, zakodowany dla wersji 1.8.x (może działać w 1.6.x/1.4.x po drobnych zmianach).
Pomaga w znajdowaniu i oczyszczaniu wadliwych plików PHP, które mają BOM lub zbędne białe znaki i mogą powodować problemy MyBB takie jak niepoprawny obrazek captcha.
2 nowe ustawienia
Licencja: GNU GPL v3, 29 June 2007. Więcej informacji w pliku LICENSE.md.
Support: oficjalne forum MyBB - http://community.mybb.com/mods.php?action=profile&uid=58253 (nie odpowiadam na PM, tylko na posty)
Zgłaszanie błędów: mój github - https://github.com/Destroy666x

© 2015 - date("Y")
*/

$l['PHP_files_cleaner'] = 'Oczyszczacz plików PHP';
$l['PHP_files_cleaner_info'] = 'Pomaga w znajdowaniu i oczyszczaniu wadliwych plików PHP, które mają BOM lub zbędne białe znaki i mogą powodować problemy MyBB takie jak niepoprawny obrazek captcha.';
$l['PHP_files_cleaner_check'] = 'Sprawdź pliki';

$l['PHP_files_cleaner_settings'] = 'Ustawienia dla pluginu "Oczyszczacz plików PHP".';
$l['PHP_files_cleaner_leading'] = 'Początkowe białe znaki i BOM';
$l['PHP_files_cleaner_leading_desc'] = 'Zezwól na czyszczenie początkowych białych znaków i znacznika kolejności bajtów (Byte Order Mark, BOM)?';
$l['PHP_files_cleaner_trailing'] = 'Końcowe białe znaki i ?>';
$l['PHP_files_cleaner_trailing_desc'] = 'Zezwól na czyszczenie końcowych białych znaków i zamykającego tagu PHP (?>)?';

$l['PHP_files_cleaner_nothing_enabled'] = 'Żadna funkcjonalność nie jest włączona w opcjach pluginu.';
$l['PHP_files_cleaner_nothing_chosen'] = 'Nie wybrano żadnego pliku PHP do zmodyfikowania.';
$l['PHP_files_cleaner_nothing_found'] = 'Nie znaleziono żadnego pliku PHP wymagającego zmian.';
$l['PHP_files_cleaner_nonchangeable'] = 'Następujące pliki nie mogły zostać zmodyfikowane przez brak uprawnień odczytu/zapisu: {1}';
$l['PHP_files_cleaner_success'] = 'Wszystkie wybrane pliki zostały pomyślnie zmodyfikowane.';
$l['PHP_files_cleaner_filename'] = 'Ścieżka i nazwa pliku';
$l['PHP_files_cleaner_clean'] = 'Oczyść pliki';