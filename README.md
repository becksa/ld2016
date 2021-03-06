# LD2016
![Linux Day Torino 2016](https://raw.githubusercontent.com/0iras0r/ld2016/master/2016/static/linuxday-64.png)

Materiale per il Linux Day Torino 2016.

## TO-DO
* Sito dell'evento (OK!)
* Gestione prenotazioni CoderDojo (OK!)
* Questionario sull'evento
* Materiale informativo per ruoli interni
* Registrazione talk (SEMBRA-OK!)

## Installazione sito web
Il sito web vuole permettere l'indipendenza dei temi grafici dei vari anni di ogni Linux Day Torino, centralizzandone le informazioni.

È utilizzata la combinazione PHP+MySQL/MariaDB usando il framework Boz-PHP.

### Preparazione
Su un sistema Debian `stable`:

    apt-get install apache2 mariadb-server php5 php5-mysql libapache2-mod-php5 php-gettext libjs-jquery libjs-leaflet libmarkdown-php
    a2enmod rewrite
    service apache2 reload

### File
Clonare i file di questo progetto direttamente nella `DocumentRoot` del proprio `VirtualHost` di Apache.

    cd /var/www/linuxday
    git clone [questo repo] .

In seguito copiare il file `htaccess.txt` in `.htaccess`.

Il sito può rimanere tranquillamente in sola lettura per l'utente Apache:

    chown root:www-data -R /var/www/linuxday
    chmod o=            -R /var/www/linuxday

### URL
Se il sito ha una cartella diversa dalla root, ricordarsi di variare l'`.htaccess` in concordanza:

    # /.htaccess:
    RewriteBase /ldto2016

E ricordarsi di aggiornare la relativa costante:

    # /load.php:
    define('ROOT', '/ldto2016');

### Database
Creare un database e importare `database-schema.sql`.

Creare il file `load.php` (vedere l'esempio `load-sample.php`) inserendovi le credenziali del database.

Si può applicare un prefisso alle tabelle, specificandolo nella variabile `$prefix` del file `load.php`.

### Framework
Posizionare il framework Boz-PHP:

    # apt-get install bzr
    bzr branch lp:boz-php-another-php-framework /usr/share/boz-php-another-php-framework

Se il framework viene posizionato in un altro posto, modificare oppurtunatamente `load.php`.

### API
Creare il file `includes/api/config.php` copiandolo da `includes/api/config-sample.php`.

Le API possono generare il file `includes/api/schedule.xml` che contiene l'elenco dei talk/eventi in formato XML (che alcuni chiamano Pentabarf, ma non è il formato Pentabarf, non ha nemmeno un nome in particolare).

Il file viene generato ogni volta che si esegue `includes/api/tagliatella.php`. La tagliatella restituisce un codice HTTP 204 se è andato tutto bene, o un 500 e qualche uncaught exception se ci sono stati errori.
È possibile far restituire l'XML direttamente da `includes/api/tagliatella.php` modificando le impostazioni in `includes/api/config.php`.

## Multilingua
Il sito è multilingua grazie al pacchetto GNU Gettext (`php-gettext`). Riassumere il fowkflow di GNU Gettext in poche righe confonderebbe soltanto, quindi passiamo al sodo.

* Per cambiare una stringa italiana, cambiala dal database o dal codice sorgente

Quando poi hai deciso di voler tradurre il progetto così com'è:

    # Exporting database strings to source code
    cd ./2016/l10n/
    php ./mieti.php > ./trebbia.php
    cd -

    # Export source code to GNU Gettext template (.pot)
    php ./2016/l10n/localize.php ./2016

    # Export GNU Gettext template (.pot) in files for Poedit (.po)
    php ./2016/l10n/localize.php ./2016

A questo punto sfodera Poedit e traduci tutti i .po che desideri.

I file `.po` sono situati nella directory `2016/l10n/`.

Per vedere il risultato in funzione (indovina un po'?):

    # Compile Poedit files (.po) to binary GNU Gettext files (.mo)
    php ./2016/l10n/localize.php ./2016

### Cambiare lingua
Il sito effettua content negotiation controllando la lingua accettata dal browser web (l'header `Accept-Language`) o eventuali richieste `GET`/`POST`/`COOKIE` con il parametro `l=$lingua` (`en`, `it`, ecc.). La lingua italiana è predefinita.

### Aggiunta lingua
Copiare il template GNU Gettext `2016/l10n/linuxday.pot` in un nuovo file `.po` nel nuovo percorso di lingua (e.g.: `./$ANNO/l10n/ru_RU.utf8/LC_MESSAGES/linuxday.po`) e modificare quest'ultimo con Poedit. Registrare la lingua in Boz-PHP modificando `./2016/load.php` e rieffettuare i passi della sezione [multilingua](#multilingua).

## Backend
Per poter accedere al backend occorre generare l'hash della password nel database (`user`.`user_password`). Il nome utente sara il proprio `user_uid`.

Per generare l'hash della password conviene abilitare momentaneamente queste due opzioni nel file `/load.php`:

    define('DEBUG', 1);
    define('SHOW_EVERY_SQL', 1);

In questo modo, una volta effettuato il login da `2016/login.php` specificando una password inventata, si otterrà l'hash da inserire nel database.

## Esportazione database
**Nota**: a differenza del codice sorgente il database è da considerarsi **read-only** ed è **molto meglio contattare il webmaster** invece che variarne i contenuti direttamente.

In ogni caso:

    # Exporting one-row per data
    mysqldump --extended-insert=FALSE linuxday2016 > database-schema.sql

    # Stripping e-mail addresses
    sed "s/'[a-z\.\-]*@[a-z\.\-]*'/NULL/g" -i database-schema.sql

    # Stripping passwords (now are SHA1 salted)
    sed "s/'[a-f0-9]\{40\}'/NULL/g" -i database-schema.sql

## Contributi
Ogni contributo avviene sotto i termini di una licenza compatibile con la licenza in calce. L'autore di un nuovo file ricopia l'intestazione della licenza da un file esistente. Autori/contributori si firmano nell'intestazione del file creato/modificato (o della parte creata/modificata) come detentori del diritto d'autore.

## Licenza
Salvo ove diversamente specificato, il progetto appartiene ai contributori di Linux Day Torino ed è distribuito sotto licenza [GNU Affero General Public License](https://www.gnu.org/licenses/agpl-3.0.html). Eccezione soprattutto per alcuni loghi dei vari partner, che appartengono ai legittimi proprietari e sono concessi in licenza esclusiva a Linux Day Torino.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.
