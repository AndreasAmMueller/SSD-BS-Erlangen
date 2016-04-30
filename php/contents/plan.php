<?php

$content = '
<h1><span class="fa fa-heartbeat"></span> Schulsanitätsdienst Berufsschule Erlangen</h1>
<h2>Alarmkette</h2>
			<p>
				Sekretariat der Berufsschule Erlangen: <a href="tel:+4991315338480">09131 533 848 0</a> (immer anrufen)
				<br />
				Im Normalfall alarmiert das Sekretariat den Schulsanitätsdienst nach Dienstplan (siehe unten) und/oder den Notruf 112 (bei Bedarf)
			</p>
			
			<h2>Dienstplan</h2>
			<form class="form-inline">
				<div class="form-group">
					<label class="sr-only" for="week">Dienstwoche</label>
					<select class="form-control" name="week" id="week">
						<option value="1">1</option>
						<option value="2">2</option>
					</select>
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-bs-outline">Auswählen</button>
				</div>
			</form>
			
			<table class="table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Mo</th>
						<th>Di</th>
						<th>Mi</th>
						<th class="active">Do</th>
						<th>Fr</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Andreas</td>
						<td><span class="fa fa-check"></span></td>
						<td></td>
						<td><span class="fa fa-check"></span></td>
						<td class="active"></td>
						<td><span class="fa fa-check"></span></td>
					</tr>
					<tr>
						<td>Florian</td>
						<td></td>
						<td><span class="fa fa-check"></span></td>
						<td></td>
						<td class="active"><span class="fa fa-check"></span></td>
						<td></td>
					</tr>
				</tbody>
			</table>
';

$page->setContent($content);

//$page->setContent('<pre>'.print_r($_SESSION, 1).'</pre>');

?>