<?php
# Um das Projekt komplett in Betrieb nehmen zu können, muss diese Datei in config.php umbenannt werden

## Webmail Settings
$webmail_host = "localhost";
$webmail_user = "info@email.de";
$webmail_pw = "passwort";

## Gaesteserver Modul
$gaesteserver_mail = "Hi <nick>,

du hattest auf www.maxlan.de angegeben, du w&uuml;rdest einen Server
zur Maxlan mitbringen.
Folgende Einstellungen solltest du schon VOR der LAN einstellen.
Auf der LAN kannst du den Server beim Support im Orgabereich abgeben, er
wird dann in den Serverbereich gestellt. (Deswegen sollte der Server
nat&uuml;rlich &uuml;bers Netzwerk ferngesteuert werden)

IP:     <ip>
Subnetz:  255.255.240.0
Gateway:  10.10.1.1
1.DNS:    10.10.1.253
2.DNS:    10.10.1.1
WINS:     10.10.1.253
Computername: <name>
Arbeitsgruppe:  LAN

Diese Einstellungen M&Uuml;SSEN eingehalten werden, ansonsten wird der Port
gesperrt!

Wenn Steam auf der Kiste laufen soll, bitte unbedingt VOR der LAN updaten!

Ansonsten viel Spa&szlig; auf der maxlan :)


Dein maxlan-Team
(automatisch generierte Mail)";
?>
