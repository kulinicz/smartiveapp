Zadanie Rekrutacyjne SmartiveApp
==============

Instalacja za pomocą jednej komendy. 
Wymagany zainstalowany docker-compose lokalnie
Dla przetestowania opcji AWS S3 należy uzupełnić plik .env

> bin/setup

W obecniej wersji są dostępne tylko katalogi w obrebie głównego katalogu
przykłady użycia: 

local source:
> bin/resize-images samples/source samples/output

S3
> bin/resize-images samples/source output_dir --storage=s3

run phpunit tests
> bin/cli ./vendor/bin/phpunit --bootstrap vendor/autoload.php src/tests
