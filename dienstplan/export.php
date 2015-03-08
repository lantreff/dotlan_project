<?php
########################################################################
# Dienstplan Modul for dotlan       		                           #
#                                                                      #
# Copyright (C) 2010 Christian Egbers <christian@3gg3.de>              #
#                                                                      #
# admin/dienstplan/export.php - Version 1.0                            #
########################################################################

include_once("../../../global.php");
include("../functions.php");
require('../../fpdf16/fpdf.php');

$sql_userdata = $DB->query("SELECT * FROM user WHERE id = '".$user_id."' LIMIT 1");
$sql_plan = $DB->query("SELECT * FROM project_dienstplan GROUP BY plan");

$out_userdata = $DB->fetch_array($sql_userdata);


$pdf=new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','',10);
$pdf->Cell(30,5,	$out_userdata['vorname']." ".$out_userdata['nachname'],0,1);
while($out_plan = $DB->fetch_array($sql_plan))
{
				$pdf->Cell(30,5,	"");
				$pdf->Cell(30,5,	"");
				$pdf->Cell(10,5,	"");
				$pdf->Cell(10,5,	"");
				$pdf->Cell(0,5,'',0,1); //<hr />
				$pdf->Cell(80,0,'',1,1); //<hr />


$sql_dienstplan = $DB->query("SELECT * FROM project_dienstplan WHERE ( u_01 = '".$user_id."' OR u_02 = '".$user_id."' ) AND plan = '".$out_plan['plan']."' ORDER BY std ASC");  // out_plan
				while($out_dienstplan = $DB->fetch_array($sql_dienstplan))
								{// begin while
				$pdf->Cell(30,5,	$out_dienstplan['plan'] );
				$pdf->Cell(30,5,	$out_dienstplan['bereich']);
				$pdf->Cell(10,5,	$out_dienstplan['std']);
				$pdf->Cell(10,5,	"Uhr");
				$pdf->Cell(0,5,'',0,1); //<hr />
				$pdf->Cell(80,0,'',1,1); //<hr />
				}

}

$pdf->Output();
?>