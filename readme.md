# Katalog Albumów - Piotr Kurczab

Zestaw startowy oparty jest na [The perfect kit starter for a Symfony 4 project with Docker and PHP 7.2](https://medium.com/@romaricp/the-perfect-kit-starter-for-a-symfony-4-project-with-docker-and-php-7-2-fda447b6bca1).


## Wymagania

* Zainstaluj [Docker](https://www.docker.com/products/docker-desktop) oraz [Docker Compose](https://docs.docker.com/compose/install) na swoim komputerze

## Instalacja

* (opcjonalnie) Dodaj

```bash
127.0.0.1   symfony.local
```

do swojego pliku `host`.

* Uruchom `build-env.sh` (lub `build-env.ps1` na Windowsie)

* Wejdź do kontenera PHP:

```bash
docker-compose exec php bash
```

* Aby zainstalować Symfony LTS wewnątrz kontenera, wykonaj:

```bash
cd app
rm .gitkeep
git config --global user.email "you@example.com"
symfony new ../app --version=lts --webapp
chown -R dev.dev *
composer install
```

* Aby ustawić migracje i fixtury danych:

```bash
bin/console doctrine:migrations:migrate
bin/console doctrine:fixtures:load
```

## Adresy URL i porty kontenerów

* Adres projektu:

```bash
http://localhost:8000
```

* MySQL:

  * wewnątrz kontenera: host `mysql`, port `3306`
  * na zewnątrz kontenera: host `localhost`, port `3307`
  * hasła i nazwa bazy znajdują się w `docker-compose.yml`

* djfarrelly/maildev dostępny jest w przeglądarce na porcie `8001`

* xdebug dostępny jest zdalnie na porcie `9000`

* Połączenie z bazą danych w pliku `.env` Symfony:

```yaml
DATABASE_URL=mysql://symfony:symfony@mysql:3306/symfony
```

## Przydatne komendy

* `docker-compose up -d` – uruchom kontenery
* `docker-compose down` – zatrzymaj kontenery
* `docker-compose exec php bash` – wejdź do kontenera PHP

---
