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
				if($_GET['action'] == 'user_id_leihe')
			{
				$d = 'shortbarbitselect';
				$d1 = 'shortbarlinkselect';
			}
				if($_GET['page'] == 'historie')
			{
				$e = 'shortbarbitselect';
				$e1 = 'shortbarlinkselect';
			}
			if($_GET['page'] == 'gruppen')
			{
				$f = 'shortbarbitselect';
				$f1 = 'shortbarlinkselect';
			}
			
			
						$output .= '<script>';
			if(!$_GET['action'])
			{
			$output .= '
			window.onload=function()
				{ document.LeiheRueckgabe.user_id_leihe.focus(); }
			';
			}
			elseif($_GET['action'] == "user_id_leihe" )
			{
			$output .= '
			window.onload=function()
				{ document.USERDATA.senden.focus(); }
			';
			}if($_GET['action'] == "leihe" )
			{
			$output .= '
			window.onload=function()
				{ document.LeihDATA.id_artikel.focus(); }
			';
			}
			

			$output .= '</script>';	
$output .= "
				<a name='top' ></a>
					<table class='msg2' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
						<a href='".$global['project_path']."'>Projekt</a>
							&raquo;
						<a href='".$dir."'>Leihsystem </a>
						
							&raquo; ".$_GET['action']."
						<br>
					</table>
					<br />
					
					<table width='100%' cellspacing='1' cellpadding='2' border='0' class='msg2'>
  						<tbody>
							<tr class='shortbarrow'>";
/**/
							$sql_users = mysql_query("SELECT * FROM `event_teilnehmer` AS e JOIN user AS u ON e.user_id = u.id WHERE e.event_id = 18 AND e.anwesend != '0000-00-00 00:00:00' AND e.bezahlt = 1");			
$output .= "<script> 
$( function() { 
	var availableTags = [";
		while ($user =  mysql_fetch_array($sql_users))
		{
			$output .= "{label: '".str_replace("'","\\'",$user["nick"]." (".$user["vorname"]." ".$user["nachname"].") ".$user["sitz_nr"])."', id: '".$user["id"]."'},";
		}
$output .= " ];
	 $( '#suche' ).autocomplete({
        source: availableTags,
        select: function( event, ui ) {
          $('#user_id_leihe').val(ui.item.user_id_leihe);
        }
      })._renderItem = function( ul, item ) {
        return item.label;
      }; 
			}); 
			</script>"; 

/**/							
							$output .= "
								<form name='LeiheRueckgabe' action='".$dir."?hide=1&action=user_id_leihe' method='POST' onSubmit='return checkSubmit()'>
								<td width='20%' class='".$b."'><a href='../equipment/' class='".$b1."'>Zum Equipment</a></td>
								<td width='20%' class='".$c."'><a href='".$dir."' class='".$c1."'>&Uuml;bersicht</a></td>
								<!-- <td width='20%' class='".$c."'><a href='".$dir."?hide=1&action=NEW_Leihe' class='".$c1."'>Artikel verleihen</a></td> -->
								<td width='20%' class='".$d."'>
									<table border='0' align='center'>
										<tr>
											<td>
												UserID:
											</td>
											<td>
												<input id='user_id_leihe' name='user_id_leihe' type='text'  maxlength='4'>
											</td>
										</tr>
										<tr>
											<td>
												Nick:
											</td>
											<td>
												<input id='suche' name='suche' type='text'>
											</td>
										</tr>
										<tr>
											<td colspan='2'align='center'>
												<input name='senden' value='Leihe/R&uuml;ckgabe' type='submit' >
											</td>
										</tr>
									</table>
								</td>
								<td width='20%' class='".$e."'><a href='historie.php?page=historie'  class='".$e1."'>Historie</a></td>
								<td width='20%' class='".$f."'><a href='gruppen.php?page=gruppen'  class='".$f1."'>Gruppen verwalten</a></td>
								</form>
							</tr>
						</tbody>
					</table>
					<br />
					<hr>
				";
				   $output .= "

</form>
<iframe frameborder='0' style='width:0px; height:0px;' src='about:blank' id='operasucks'></iframe>
<script type='text/javascript' src='/user/xmlusersearch.js'></script>
<script type='text/javascript'>
<!--

// define variables needed by xmlusersearch.js
var inselect	= document.getElementById('inselect');
var divselect	= document.getElementById('divselect');
var insearch	= document.getElementById('insearch');
var divsearch	= document.getElementById('divsearch');
var xmllink	= '/user/?do=xmlsearch';

function checkSubmit()
{
    if(insearch.value != '') {
    	searchUser();
    	return false;
    }
    if(inselect.length == 0 || inselect.options[0].value == '' || inselect.options[0].value == '0') {
    	alert('Es wurde kein Benutzer gewaehlt');
    	return false;
    }
}

initUserSearch('0');

//-->
";
			$output .= '</script>';	
			

	
	$output .= $meldung;
?>