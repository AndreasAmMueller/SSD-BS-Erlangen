# Schulsanitätsdienst

## Kurzbeschreibung

Der Schulsanitätsdienst der Berufsschule Erlangen ist auf Grund von Tages- und Blockunterricht recht schwer zu organisieren.

Damit die Lehrer und das Sekretariat es leichter haben die Sanis zu koordinieren, existiert diese Seite.

Hier können die Sanitäter verwaltet und für den Dienst eingeteilt werden. Auf der Startseite kann dann der aktuelle Dienstplan dann eingesehen werden.

## Technologien / Frameworks

- PHP 5.6 (7.0 ready)
- CSS 3
- jQuery 2.2.3 (JavaScript)
- HTML 5
- Bootstrap 3.3.6
 
## Installation

- Eine Datenbank mit folgenden Anforderungen erstellen
  * Engine: `InnoDB`
  * Character set: `utf8`
  * Collation: `utf8_bin`
- Der Datenbankbenutzer der Seite benötigt mindestens die Rechte
  * `SELECT`
  * `INSERT`
  * `UPDATE`
  * `DELETE`
- Den Quellcode in das Webverzeichnis legen (Bei _git clone_ nicht die submodules vergessen!)
- Die Datenbank mittels der Skripte im Ordner `/sql` anlegen
  * Es werden dabei das Schuljahr 2015/16 geschrieben
  * und ein Administrator (admin@example.com) angelegt. Passwort: `p@ssw0rd`
- Die Datenbankverbindung schreiben
  * `config.php.example` kopiern/umbenennen in `config.php`
  * Datei öffnen und die Parameter `$config['db_*']` ausfüllen
- Seite aufrufen, einloggen und _mind._ das __Passwort und E-Mail Adresse ändern__
- In den Einstellungen das Schuljahr und die Ferien setzen
- __Fertig__
