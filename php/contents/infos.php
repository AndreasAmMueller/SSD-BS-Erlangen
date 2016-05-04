<?php

/**
 * infos.php
 *
 * @author     Andreas Mueller <webmaster@am-wd.de>
 * @copyright  (c) 2016 Andreas Mueller
 * @license    MIT - http://am-wd.de/?p=about#license
 */

$content = '
<h1>Die Schulsanitäter der Berufsschule Erlangen</h1>
<p>
	Der Schulsanitätsdienst stellt eine wichtige Komponente in der Erstversorgung von Patienten dar.
	Immer mal wieder passiert etwas in der Schule und meistens ist der letzte Erste-Hilfe Kurs doch schon etwas her.
	Um so wichtiger ist die Gruppe von ca. 10-20 Schülern, die sich freiwillig in dem Bereich fortbilden lassen und anschließend an der Schule zur Verfügung stehen.
	<br />
	Gegründet wurde das Sani-Team am SMV-Wochenende 2013/14.
</p>

<table class="table">
	<thead>
		<tr>
			<th>Schuljahr</th>
			<th>Was passierte?</th>
			<th>Artikel</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>2013/14</td>
			<td>
				<a href="doku/sanilogo2013.jpg" class="blank">Sani-Logo</a>
				und
				<a href="doku/saniflyer2013.pdf" class="blank">Flyer von Ruth und Kadda</a>
			</td>
			<td><a href="http://www.bs-erlangen.de/schulsanitaetsdienst-sucht-gruendungsmitglieder" class="blank">Schulsanitätsdienst sucht Gründungsmitglieder</a></td>
		</tr>
		<tr>
			<td>2014/15</td>
			<td>
				<a href="http://www.bs-erlangen.de/smv-geht-gut-organisiert-an-den-start" class="blank">SMV-WE mit dem Sani-Team: Ruth, Kadda, Max u. Sascha</a>
			</td>
			<td>
				<a href="http://www.bs-erlangen.de/smv-vollversammlung-in-nbg" class="blank">VV mit Sani-Team und Barbara (l.), Michael (r.), Matthias (u.)</a>
			</td>
		</tr>
		<tr>
			<td>2015/16</td>
			<td>
				Diese Seite geht online
			</td>
			<td></td>
		</tr>
	</tbody>
</table>

<p>
	Wir befinden uns noch immer in der Aufbau-Phase. Zu Schulanfang benötigen wir immer wieder neue Schüler,
	die eine Qualifikation in der Ersten-Hilfe vorweisen können, um einen funktionierenden Dienstplan zu erstellen.
	<br />
	Wir freuen uns auch auf Deine Unterstützung!
</p>
<p>
	Bei Interesse bitte im Sekretariat Name, Klasse, E-Mail Adresse und Telefonnummer hinterlassen oder eine E-Mail schreiben an <script type="text/javascript">document.write(["sani", "bs-erlangen"].join("@"));</script>.de.
</p>

<h3>Ansprechpartner</h3>
<table class="table">
	<thead>
		<tr>
			<th>Wer?</th>
			<th>Wo?</th>
			<th>Wie?</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Barbara Zenger</td>
			<td>Kaufmännisches Gebäude, Zimmer K110</td>
			<td><script type="text/javascript">document.write(["barbara.zenger", "bs-erlangen"].join("@"));</script>.de</td>
		</tr>
		<tr>
			<td>Michael Münch</td>
			<td>Gewerbliches Gebäude, Zimmer G026</td>
			<td><script type="text/javascript">document.write(["michael.muench", "bs-erlangen"].join("@"));</script>.de</td>
		</tr>
	</tbody>
</table>
';
$page->setContent($content);

$content = '
<h1>AED &ndash; Defis für die Berufsschule</h1>
<h4>Eine Spende der RV-Bank an den Förderverein</h4>
<p>
	Es gibt nun zwei AEDs an unserer Schule!
	Bei einem AED (<strong>a</strong>utomatisierter <strong>e</strong>xterner <strong>D</strong>efibrilator)
	handelt es sich um einen Defi, wie wir ihn für gewöhnlich kennen.
	Wenn man ihn öffnet, hat er zwei Klebeelektroden und spricht mit einem. Also genau so ein Gerät, wie man es im Erste-Hilfe Kurs kennengelernt hat.
	<br />
	Wer mehr wissen möchte, kann sich <a href="https://de.wikipedia.org/wiki/Automatisierter_Externer_Defibrillator" class="blank">hier</a> schlau machen.
</p>
<p>
	Die zwei Defibrillatoren hängen an folgenden Orten:
	<ul>
		<li>Kaufmännisches Gebäude neben dem Sanitätszimmer im Erdgeschoss</li>
		<li>Sporthalle im Sanitätszimmer, Personaleingang, erstes Zimmer links</li>
	</ul>
	Die beiden Standorte sind immer zugänglich.
</p>
';
$page->addContent($content);

$content = '
<h1>Sani-Homepage</h1>
<p>
	Die Idee der Seite stammt von Hans Daniel und Daniel Zeltner, die das Ganze als IT-Projekt für die Anwendungsentwickler
	in der 12. Klasse im Schuljahr 2014/15 gestartet haben.
	<br>
	Ein sogenanntes Refactoring und eine Design-Auffrischung fand durch <a href="http://am-wd.de" class="blank">Andreas Müller</a> statt.
</p>
<p>
	Sollten Fehler oder sonstige Probleme auftreten, schreibe bitte eine E-Mail mit detailierten Angaben (wichtig für die Reproduktion des Fehlers) an
	<br />
	Matthias Ebert, Gewerbliches Gebäude, Zimmer G030, <script type="text/javascript">document.write(["matthias.ebert", "bs-erlangen"].join("@"));</script>.de.
</p>
';
$page->addContent($content);

?>