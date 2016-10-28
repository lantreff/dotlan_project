<?php

$a = 'shortbarbit';
$b = 'shortbarbit';
$c = 'shortbarbit';
$d = 'shortbarbit';
$e = 'shortbarbit';
$f = 'shortbarbit';


$a1 = 'shortbarlink';
$b1 = 'shortbarlink';
$c1 = 'shortbarlink';
$d1 = 'shortbarlink';
$e1 = 'shortbarlink';
$f1 = 'shortbarlink';

	
if(!$_GET['action'] && !$_GET['page'])
{
	$c = 'shortbarbitselect';
	$c1 = 'shortbarlinkselect';
}
if($_GET['action'] == 'historie')
{
	$d = 'shortbarbitselect';
	$d1 = 'shortbarlinkselect';
}
$output .= "	<a name='top' ></a>
					<table class='msg2' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
						<a href='{BASEDIR}admin/projekt'>Projekt</a>
							&raquo;
						<a href='".$dir."'>$MODUL_NAME </a>

						&raquo; ".$_GET['action']."
						<br>
					</table>
					<br />
			
					<table width='100%' cellspacing='1' cellpadding='2' border='0' class='msg2'>
  						<tbody>
							<tr class='shortbarrow'>";
$output .= "					<td width='50%' class='".$c."'><a href='".$dir."' class='".$c1."'>$MODUL_NAME</a></td>";

if($DARF["historie"] )
{
	$output .= "	<td width='50%' class='".$d."'><a href='".$dir."?hide=1&action=historie' class='".$d1."'>Historie</a></td>";
}
$output .= "	</tr>
						</tbody>
					</table>
				<br />
				<hr>
				";
$output .= $meldung;


?>