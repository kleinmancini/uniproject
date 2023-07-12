<?php
/*****************************************************************************************
** Ops Support - File Validator App
** --------------------------------
** Performs basic pre-validation of files prior to passing into (typically) ESB Batch jobs
** for processing into Osprey.
**
** App allows Buiness users to upload files and verify if they comply with predefined 
** templates depending on the target Batch job
**
** index.php
** ---------
** The starting point for constructing the App web page and pulling in all the resources that is uses
**
** Version		Who				Date			Comment
** 0.0			SSavage			02/10/2020		Starting Change Log
** 0.1			SSavage			02/10/2020		Initial version and all changes up to date - original code written in 2016
**													and updated by various members of Ops Support team since then
** 0.2			SSavage			02/10/2020		SBTD-6000: Radley Contingency - Bulk Credit - Changes to app to allow files to include a footer
**													which at present will simply be ignored from validation
**
**
**
** 0.n			TBC				DD/MM/YYYY		Description
**
*****************************************************************************************/





	include("lib.php");
	header('X-UA-Compatible: IE=EmulateIE8');
	date_default_timezone_set('Europe/London');

	//HOLDING PAGE
//		echo "<script>window.location.href = 'http://udwas2b0/filevalidator';</script>";


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


</head>
<body>

<div id="main">

<?php
	echo banner("Empty");

	echo menubar();

	echo progressBar();

    echo displaypages();

	echo createFooter();

?>
</div>

	<script type="text/javascript" src="js/jquery.easing.min.js"></script>
	<script type="text/javascript" src="js/jquery.lavalamp.min.js"></script>
	<script type="text/javascript" src="js/jquery.kwicks-1.5.1.js"></script>
	<script type="text/javascript">
    $(function() {
      $("#lava_menu").lavaLamp({
        fx: "backout",
        speed: 700
      });
    });
  </script>
</body>


</html>
