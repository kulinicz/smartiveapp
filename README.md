Instalacja za pomocą jednej komendy. 
Wymagany zainstalowany docker-compose lokalnie
Dla przetestowania opcji AWS S3 należy uzupełnić plik .env

> bin/setup

W obecniej wersji są dostępne tylko katalogi w obrebie głównego katalogu
przykłady użycia: 

> bin/resize-images samples/source samples/output
> bin/resize-images samples/source --storage=s3 --bucket=smartiveapp

run phpunit tests
> ./vendor/bin/phpunit --bootstrap vendor/autoload.php src/tests
