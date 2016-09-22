# Schulsanitätsdienst

## Kurzbeschreibung

Der Schulsanitätsdienst der Berufsschule Erlangen ist auf Grund von Tages- und Blockunterricht recht schwer zu organisieren.

Damit die Lehrer und das Sekretariat es leichter haben die Sanis zu koordinieren, existiert diese Seite.

Hier können die Sanitäter verwaltet und für den Dienst eingeteilt werden. Auf der Startseite kann dann der aktuelle Dienstplan dann eingesehen werden.

_Hinweis:_ Für das Repository wurde [Git LFS](https://git-lfs.github.com/) aktiviert und benutzt (`vendor/data`).

## Technologien / Frameworks

- PHP 5.6 (7.0 ready)
- CSS 3
- jQuery 2.2.3 (JavaScript)
- HTML 5
- Bootstrap 3.3.6
 
## Installation

- Den Quellcode aus dem Verzeichnis `src` in das Webverzeichnis legen (Nicht die submodules vergessen!)
- Die SQLite Datenbank `vendor/#ssd.db` in das Webverzeichnis legen
- Die Schreibrechte auf `#ssd.db` und den Ordner, in dem die Datei liegt, prüfen und ggf. setzen
  * Es ist bereits das Schuljahr 2015/16
  * und ein Administrator (admin@example.com) angelegt. Passwort: `p@ssw0rd`
- Die Datenbankverbindung schreiben
  * `config.php.example` kopiern/umbenennen in `config.php`
  * Datei öffnen und Änderungen eintragen (soweit gewünscht und benötigt)
- Seite aufrufen, einloggen und _wenigstens_ das __Passwort und E-Mail Adresse ändern__
- In den Einstellungen das Schuljahr und die Ferien setzen
- __Fertig__

## Docker Image

### Build
Es besteht nun auch die Möglichkeit ein Docker Image zu erstellen, welches dann die gesamte Seite hostet.    
Hierfür muss zunächst [Docker](https://docs.docker.com/) installiert sein.    
Anschließend kann das Docker Image erzeugt werden. Unter Linux oder Mac OS X kann einfach das Script `build-docker.sh` genutzt werden.

```
$ docker build -f vendor/Dockerfile -t ssdbserlangen:latest .
```

### Ausführen

Ausgeführt wird das Ganze anschließend in üblicher Docker manier:

```
$ docker run --name ssd-bs-erlangen -d -p 80:80 ssdbserlangen:latest
```

Zerlegen wir den Befehl einmal kurz:
- `docker` Unser Docker Programm
- `run` Docker soll nun also etwas ausführen
- `--name ssd-bs-erlangen` der Name des Containers soll _ssd-bs-erlangen_ heißen
- `-d` der Container soll im Hintergrund (deattached) laufen
- `-p 80:80` der Port 80 des Hosts soll auf Port 80 des Containers gemapped werden (_&lt;host port&gt;:&lt;container port&gt;_)
- `ssdbserlangen:latest` nutze für den Container die aktuellste (latest) Version des Image _ssdbserlangen_
- Nun läuft der Container im Hintergrund und ist über HTTP erreichbar. Der Status des Containers kann mittels `docker ps` eingesehen werden
