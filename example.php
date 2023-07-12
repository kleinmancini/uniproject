<?php
/*****************************************************************************************
** example.php
** ---------
** Displays an example definition of the chosen file template to the user
**
** Version		Who				Date			Comment
** 0.0			SSavage			02/10/2020		Starting Change Log
** 0.1			SSavage			02/10/2020		Initial version and all changes up to date - original code written in 2016
**												and updated by various members of Ops Support team since then
** 0.2			SSavage			02/10/2020		SBTD-6000: Added visibility of FOOTER detail to 'Example' screen as a warning only for now
**
**
**
** 0.n			TBC				DD/MM/YYYY		Description
**
*****************************************************************************************/
	include("lib.php");
?>
<!DOCTYPE html>
<html>

<head>
	<title>Sky Business Technology - Batch Validator</title>
	<meta name="description" content="website description" />
	<meta name="keywords" content="website keywords, website keywords" />
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link href="css/redmond/jquery-ui-1.9.2.custom.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
	<script type="text/javascript" src="js/modernizr-1.5.min.js"></script>
	<script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="scripts/shCore.js"></script>
	<script type="text/javascript" src="scripts/shBrushJScript.js"></script>
	<link type="text/css" rel="stylesheet" href="styles/shCoreDefault.css"/>
	<script type="text/javascript">SyntaxHighlighter.all();</script>

</head>
<body>
<?php

$batname = $_GET['batchname'];

$sql="select * from batchfiles b where b.batchname='$batname' order by bussinessname";

//echo $sql;
$result=databaseQuery($sql);

while ($row = oci_fetch_array($result, OCI_ASSOC)) {
	$numberCol = $row['EXPECTEDNUMBERFIELDS'];
	$bussinessname = $row['BUSSINESSNAME'];
	$batchname = $row['BATCHNAME'];
	$exampleFileName = $row['EXAMPLEFILENAME'];
	$exampleFile = $row['EXAMPLEFILE']->load();
	$filename = $row['FILEOUTPUTNAME'];
	$required =  explode(",", $row['REQUIREDFIELDS']);
	$phonenum =  explode(",", $row['TELEPHONEFIELDS']);
	$email =  explode(",", $row['EMAILFIELDS']);
	$digits =  explode(",", $row['DIGITONLY']);
	$propertyname =  explode(",", $row['PROPERTYNAME']);
	$specialRule =  $row['SPECIALRULES'];
	$hasFooter =  $row['HASFOOTER']; 	//SBTD-6000: Added FOOTER details to function
	$footerRows =  $row['FOOTERROWS']; 	//SBTD-6000: Added FOOTER details to function

}
echo '<br/>';

echo generateTextView($batchid, $exampleFile, $numberCol, $bussinessname, $batchname, $filename, $required, $phonenum, $email, $digits, $propertyname, $exampleFileName, $specialRule, $hasFooter, $footerRows);

function generateTextView($batchid, $example, $numberCols, $bussinessname, $batchname, $filename, $required, $phonenum, $email, $digits, $propertyname, $exampleFileName, $specialRule, $hasFooter, $footerRows){
	//SBTD-6000: Added visibility of FOOTER details to function
	if($specialRule == "pipe"){
		$delim='|';
	}
	else{
		$delim=',';
	}
	$numberRows = 5;
	
	$content .= '<div id="textview">';
	$content .= '<h3>Template for: '.$bussinessname.' </h3>';
	$content .=  '<table><tr><td width="200px"><b>Batch Name:</b></td><td>'.$batchname.'</td></tr>';
	$content .=  '<tr><td><b>Expected Columns:</b></td><td>'.$numberCols.'</td></tr>';
	$content .=  '<tr><td><b>Expected Filename Format:</b></td><td>'.$filename.'</td></tr>';
	$content .=  '<tr><td><b>Example Filename:</b></td><td>'.$exampleFileName.'</td></tr>';	
	//SBTD-6000: Added visibility of FOOTER details to function
	if($hasFooter == 1){
		$content .=  '<tr><td><b>Expected Footer Rows:</b></td><td>'.$footerRows.'</td></tr>';
	}	
	$content .=  '</table>';
	
	
	//SBTD-6000: Added visibility of FOOTER warning only for now
	if($hasFooter == 1){
		$content .= '<b>This file contain a mandatory footer section. This is currently not checked by the File Validator.</b>';
		$content .= '<br/><br/>As such the last <b>'.$footerRows.'</b> rows of any file submitted will be ignored by the File Validator.';
		$content .= '<br/>Please be sure this footer detail is configured as requied otherwise the Batch job will fail.';
		$content .= '<br/>If there are any questions please contact Operational Support before submitting your file.';
		$content .= '<br/><br/><br/>';
		}

	
	
	
	$content .=  'Below is an example file containing The Header Columns and '.$numberRows. ' Blank Rows:';
	//SBTD-6000: Added visibility of FOOTER warning only for now
	if($hasFooter == 1){
		$content .= '<br/><b>NB: The example does not include any footer details at present!</b>';
		}	

	$content .='<pre class="brush: jscript; toolbar: false; class-name: formatSource;">'.$example.'';
	for($p=0; $p < $numberRows; $p++){
	
	$content .='
';
		for($i=1; $i < $numberCols; $i++){
			$content .=$delim;
		}
	}
	$content .= '</pre>';
	
	
	$fileContent .= $example;
	for($p=0; $p < $numberRows; $p++){
		$fileContent .="\r\n";
		for($i=1; $i < $numberCols; $i++){
			$fileContent .=',';
		}
	}
	
	$content .= '<form action="./download.php" method="post">
	<input type="hidden" name="content" value="'.$fileContent.'">
	<input type="hidden" name="filename" value="'.$filename.'">
	<input type="submit" class="submit buttonRight" style="float: right; margin-top: -13px; margin-right: -2px;" value="Download Example" /></form>';
	
	$content .= '<br/><br/><b>Please do not use commas in this file other than the ones that are shown above. For example if an address contains commas, ignore them!</b><br/><br/>This batch job has the following requirements in order to validate:';
	
	$content .= '<table id="table-b" style="margin-top: 0;"><thbody>';

	$content .= '<table id="table-b">';
	$content .= '<thead><tr><th>FIELD</th><th>DATA REQUIRED</th><th>PHONE NUMBER</th><th>EMAIL ADDRESS</th><th>NUMBER ONLY</th><th>ADDRESS FIELD</th></tr></thead><thbody>';
	//$pieces = explode(",", $example);
	
	if($specialRule == "pipe"){
		$pieces = explode("|", $example);
	}
	else{
		$pieces = explode(",", $example);
	}
	for($i = 0; $i <sizeof($pieces); $i++){
		//$content .= '<tr><td>'.$pieces[$i].'</td><td>'.createCheckbox("required".$i, $required[$i], "style=\"\" disabled").'</td><td>'.createCheckbox("postcode".$i, $postcode[$i], "disabled").'</td><td>'.createCheckbox("phonenumber".$i, $phonenum[$i], "disabled").'</td><td>'.createCheckbox("email".$i, $email[$i], "disabled").'</td></tr>';
		$content .= '<tr><td>'.$pieces[$i].'</td><td>'.createCheckbox("required".$i, $required[$i]).'</td><td>'.createCheckbox("phonenumber".$i, $phonenum[$i]).'</td><td>'.createCheckbox("email".$i, $email[$i]).'</td><td>'.createCheckbox("digit".$i, $digits[$i]).'</td><td>'.createCheckbox("propertyname".$i, $propertyname[$i]).'</td></tr>';
	}
	
	$content .= '</div>';
	
	return $content;

}
?>
</body>
</html>