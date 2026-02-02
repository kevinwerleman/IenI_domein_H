# Databases Omgeving

[![Open in Gitpod](https://gitpod.io/button/open-in-gitpod.svg)](https://vnr-csg.github.io/IenI_domein_H/setup/gitpod-redirect.html)

## Omgeving openen 

### Gitpod 

Open deze omgeving in Gitpod door op de bovenstaande knop te drukken of (als dat niet werkt) naar `https://gitpod.io/#<url van repository>` te gaan.
 
### GitHub Codespaces    
   
Maak een nieuwe codespace aan door op de 'Code' knop te drukken.
           
## Bestandsindeling
       
### Leerling  

Leerlingen hebben toegang tot de standaarddatabases en kunnen zelf databases importeren en exporteren via de `databases` map.
Nieuwe websites kunnen worden gemaakt door een map onder `public` aan te maken.
Verder is er toegang tot het lesmateriaal dat door de docent wordt voorbereid.

### Docent
   
Docenten kunnen lesmateriaal voorbereiden door het in de `lesmateriaal/public` map te plaatsen. 
Ook kunnen standaard databases worden toevoegd die automatisch worden ingeladen door ze in de `lesmateriaal/standaard-databases` map te plaatsen.

## MySQL

De MySQL database is toegankelijk via het serveradres `127.0.0.1`.
Er zijn twee database gebruikers beschikbaar onder het standaardwachtwoord `password`:
- `user` met alle rechten
- `readonly` met readonly (alleen-lezen) rechten

Een voorbeeld van PHP-code om verbinding te maken met MySQL:
```php
$mysqli = new mysqli("127.0.0.1", "user", "password", "databasenaam");
```

De databases kunnen beheerd worden met phpMyAdmin die is geconfigueerd om automatisch in te loggen.

## Versies

De Docker image is gebaseerd op Ubuntu LTS 22.04 LTS met PHP 8.1 en MySQL 8.0.


