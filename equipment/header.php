<?php

$PAGE->sitetitle = $PAGE->htmltitle = _(ucfirst($MODUL_NAME));
				
				$breite = "120";
				$a = 'shortbarbitselect';
				$a1 = 'shortbarlinkselect';
				$b = 'shortbarbit';
				$b1 = 'shortbarlink';
				$c = 'shortbarbit';
				$c1 = 'shortbarlink';
				$d = 'shortbarbit';
				$d1 = 'shortbarlink';
				$e = 'shortbarbit';
				$e1 = 'shortbarlink';
				$f = 'shortbarbit';
				$f1 = 'shortbarlink';
			
			

			
			if($_GET['hide'] == 1)
			{
				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
			}
			if($_GET['action'] == 'add')
			{
				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$b = 'shortbarbitselect';
				$b1 = 'shortbarlinkselect';
			}
			if($_GET['action'] == 'lagerort')
			{
				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$c = 'shortbarbitselect';
				$c1 = 'shortbarlinkselect';
			}
			if($_GET['action'] == 'kisten')
			{
				$a = 'shortbarbit';
				$a1 = 'shortbarlink';
				$d = 'shortbarbitselect';
				$d1 = 'shortbarlinkselect';
			}
			
			
			
 
$output .= "
				<a name='top' ></a>
					<table class='msg2' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
						<a href='".$global['project_path']."'>Projekt</a>
							&raquo;
						<a href='".$dir."'>".ucfirst($MODUL_NAME)."</a>
						
							&raquo; ".$_GET['action']."
						<br>
					</table>
					<br />";
					

			 

				$output .= "


				<table  cellspacing='1' cellpadding='2' border='0' class='shortbar'>
					 <tbody>
						<tr class='shortbarrow'>";
						
						$breite = "150";
						$output .= "<td width='".$breite."' class='".$a."'><a href='index.php' class='".$a1."'>&Uuml;bersicht</a></td>";
						$output .= "<td width='2' class='shortbarbitselect'>&nbsp;</td>";
						if($DARF["add"] )
							{
								$output .= "<td width='".$breite."' class='".$b."'><a href='?hide=1&action=add' class='".$b1."'>Equipment Anlegen</a></td>";
								$output .= "<td width='".$breite."' class='".$c."'><a href='?hide=1&action=lagerort' class='".$c1."'>Lagerorte</a></td>";
								$output .= "<td width='".$breite."' class='".$d."'><a href='?hide=1&action=kisten' class='".$d1."'>Beh&auml;lter</a></td>";
													
							}
								
					$output .= "
						</tr>
					</tbody>
				</table>
				<br>
			";
			$output .= '<script>';
			if($_GET['action'] == "eqtokiste")
			{
			$output .= '
			window.onload=function()
				{ document.addeq2kiste.eqid.focus(); }
			';
			}
			elseif($_GET['action'] == "eqtokiste"  && ( $_GET['action'] != "add" || $_GET['action'] != "edit") )
			{
			$output .= '
			window.onload=function()
				{ document.suche.kiste.focus(); }
			';
			}
			$output .= '</script>';	
		
	$output .= "<form name='suche' action='?hide=1&action=suche' method='POST'>
				Hier Barcode scannen: <input name='kiste' value='' size='25' type='text' maxlength='25'>
				</form>
				<br>
	";

			
?>