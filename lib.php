<?php
/*****************************************************************************************
** lib.php
** ---------
** Contains a number of helper procedures referenced (mainly) by index.php to drive the "pages" of the app
**
** Version		Who				Date			Comment
** 0.0			SSavage			02/10/2020		Starting Change Log
** 0.1			SSavage			02/10/2020		Initial version and all changes up to date - original code written in 2016
**													and updated by various members of Ops Support team since then
** 0.2			SSavage			02/10/2020		SBTD-6000: Added visibility of FOOTER detail to 'Edit Batch job' screen
**
**
**
** 0.n			TBC				DD/MM/YYYY		Description
**
*****************************************************************************************/




session_start();
include("db.inc.php");

function menubar(){
	$menu = '<header>';
    $menu .= '<div id="logo"><h1>Batch Validator</h1></div>';
	$menu .= '<nav>';
    $menu .= '<ul class="lavaLampWithImage" id="lava_menu">';
	$menu .= '<li ><a href="index.php">home</a></li>';
	if($_GET['step']=="address"){
		$menu .= '<li class="current"><a href="?step=address">address-validator</a></li>';
	}
	else{
		$menu .= '<li><a href="?step=address">address-validator</a></li>';
	}
	if($_GET['step']=="progress"){
		$menu .= '<li class="current"><a href="?step=progress">current-progress</a></li>';
	}
	else{
		$menu .= '<li><a href="?step=progress">current-progress</a></li>';
	}
	if(isset($_SESSION['admin'])){
		if($_GET['step']=="admin"){
			$menu .= '<li class="current"><a href="?step=admin">admin</a></li>';
		}
		else{
			$menu .= '<li><a href="?step=admin">admin</a></li>';
		}
	}
	if(isset($_SESSION['username'])){
		$menu .= '<li><a href="proc.php?logout">logout</a></li>';
	}
	$menu .= '</ul>';
	$menu .= '</nav>';
	$menu .= '</header>';
	$menu .= '<div style="clear:both;"></div> ';

	return $menu;
}

function progressBar(){

	if(isset($_GET['step'])){
		$step = $_GET['step'];
	}
	else{
		$step = 1;
	}
	$steps=array("Login","Select Batch Job","Upload File & Validate","Validation Results","Submit & Complete");

	$progress = '<div id="progress">';
	for($i = 0; $i < sizeof($steps); $i++){
		if($i != 0){
			$progress .= ' > ';
		}
		if($i == ($step-1)){
			$progress .= '<b>Step ' .($i+1).' - '.$steps[$i].'</b>';
		}
		else{
			$progress .= 'Step ' .($i+1).' - '.$steps[$i];
		}

	}
	$progress .= '</div>';
	$progress .= '<div style="clear:both;"></div> ';

	return $progress;
}

function createFooter(){

	$year = date("Y");
	$footer = '<div id="footer"><p>© Sky Business Technology - 2013';
	$footer .= '</p><p>Email: <a href="mailto:DL-SBTOperations@bskyb.internal">DL-SBTOperations@bskyb.internal</a></p>';
	$footer .= '</div>';

	return $footer;
}

function displaypages(){

	$displaypage =  '<div id="content" class="box">';

	if(isset($_SESSION['username'])){
		if(isset($_GET['step'])){
			$page = $_GET['step'];
		}
		else{
			header('Location: index.php?step=2');
		}
	}
	else if($_GET['step'] == "address"){
		$page = "address";
	}
	else if($_GET['step'] == "progress"){
		$page = "progress";
	}
	else{
		$page = "1";
	}


	if(isset($page)){
		switch ($page) {
			case "admin":
				$displaypage .= admin();
			break;
			case "address":
				$displaypage .= address();
			break;
			case "progress":
				$displaypage .= progress();
			break;
			case "1":
				$displaypage .= login();
			break;
			case "2":
				$displaypage .= selectbatchjob();
			break;
			case "3":
				$displaypage .= uploadfile();
			break;
			case "4":
				$displaypage .= validationResults();
			break;
			case "5":
				$displaypage .= submit();
			break;
		}
	}

	$displaypage .= '</div>';
	$displaypage .= '<div style="clear:both;"></div> ';
	return $displaypage;
}


function login(){

	$login = '<div id="login">';
	$login .= '<h1>Login</h1>';
	$login .= '<form class="form_settings" method="POST" action="proc.php?login">';
	$login .= '<center><table>';
	$login .= '<tr><td><label for="name">Username:</label></td>';
	$login .= '<td><input type="name" name="userid"></td></tr>';
	$login .= '<tr><td><label for="username">Password:</label></td>';
	$login .= '<td><input type="password" name="pass"></td></tr>';
	$login .= '<tr><td></td><td><input type="submit" name="submit" value="Login" class="submit" style="float: right;"></td></tr>';
	$login .= '</table></center>';
	$login .= '</form>';
	$login .= '</div>';

	if(isset($_GET['loginerror'])){
		$login .= '<script>';
		$login .= 'openBanner("Invalid Login Details!");';
		$login .= '</script>';
	}
	else if(isset($_GET['logindenied'])){
		$login .= '<script>';
		$login .= 'openBanner("You do not have access to this tool, If you think you should please request access <a href=\"\" style=\"color: yellow;\">here</a>!");';
		$login .= '</script>';
	}
	else if(isset($_GET['loggedout'])){
		$login .= '<script>';
		$login .= 'openBanner("Successfully Logged out");';
		$login .= '</script>';
	}
	return $login;
}

function banner($message){
	if(!isset($message)){
		$message = 'Test Message';
	}

	$banner = '<div id="message" style="display: none;" >';
	$banner .= '<span id="messageContent">'.$message.'</span><a href="#" class="close-notify" onclick="closeBanner()">X</a>';
	$banner .= '<script>';
	$banner .= 'function openBanner(message) {
					$("#messageContent").html(message);
					$("#message").css("display","inline");
				}';
	$banner .= 'function closeBanner() {
					$("#message").css("display","none");
				}';
	$banner .= '</script>';
	$banner .= '</div>';

	return $banner;
}

function selectbatchjob(){

	$step2 = '';

	if(isset($_GET['notadmin'])){
		$step2 .= '<script>';
		$step2 .= 'openBanner("Nice try, but you are not an Admin!");';
		$step2 .= '</script>';
	}

	$step2 .= '<div id="thecontent">';
	$step2 .= '<h3>Step 2 - Select a Batch Job:</h3>';

	$sql="select * from batchfiles b where b.completed=1 order by b.bussinessname";
	$result=databaseQuery($sql);

	$step2 .= '<p><form class="form_settings" method="POST" action="index.php?step=3"><select id="batchjob" name="batchjob" style="width: 100%;">';
	$step2 .= '<option value="blank">Select Batch Job:</option>';
	while ($row = oci_fetch_array($result, OCI_ASSOC)) {

		if(@isset($row['USECASENAME'])){
			$step2 .= '<option value="'.$row['ID'].'">'.$row['BUSSINESSNAME'].' ('.$row['USECASENAME'].') - '.$row['BATCHNAME'].'</option>';
		}
		else{
			$step2 .= '<option value="'.$row['ID'].'">'.$row['BUSSINESSNAME'].' - '.$row['BATCHNAME'].'</option>';
		}
	}
	$step2 .= '</select></p>';
	$step2 .= '<div id="infoBox">';
	$step2 .= '</div>';
	$step2 .= '<p style="margin-bottom: 20px;"><input type="submit" id="submit" name="submit" value="Continue" class="submit" style="float: right;" disabled/></p>';
	$step2 .= '</form>';
	$step2 .= '</div>';

	$step2 .= '<script>
	$("#batchjob").change(function() {
		func = ($(this).val());
		$("#infoBox").show();
		$("#batchjob option[value=blank]").remove();
		$.ajax({
		  url: "proc.php?batchinfo&job="+func,
		  success: function(data) {
			$("#infoBox").html(data);
			$("#submit").removeAttr("disabled");
		  }
		});
	});
	</script>';

	return $step2;
}

function uploadfile(){


	if(!isset($_POST['batchjob'])){
		header('Location: index.php?step=2');
	}

	$batchid = $_POST['batchjob'];
	$step2 = '<div id="thecontent">';
	$step2 .= '<h3>Step 3 - Upload File & Validate:</h3>';

	$sql="select * from batchfiles b where b.completed=1";
	$result=databaseQuery($sql);

	$step2 .= '<p><form class="form_settings" enctype="multipart/form-data" method="POST" action="proc.php?upload">';
	$step2 .= '<input type="hidden" name="MAX_FILE_SIZE" value="21000000" />';
	$step2 .= '<input type="hidden" name="batchid" value="'.$batchid.'" />';
	$step2 .= '<div><input class="uploadArea" name="file" type="file" id="file" required/><br /></div>';
	$step2 .='</p>';
	$step2 .= '<div id="infoBox">';
	$step2 .= '</div>';
	$step2 .= '<p style="margin-bottom: 20px;"><input type="submit" id="submit" name="submit" value="Continue" onclick="spinner();" class="submit" style="float: right;"/></p>';
	$step2 .= '</form>';
	$step2 .= '</div>';

	$step2 .= '<script>
	$(document).ready(function() {
		$("#infoBox").show();
		$.ajax({
		  url: "proc.php?batchinfo&job='.$batchid.'",
		  success: function(data) {
			$("#infoBox").html(data);
		  }
		});
	});

	function spinner(){
		$( "#dialogspinner" ).dialog({
		width: 100,
		modal: true
	   });
    };
	</script>

	<div id="dialogspinner" title="Processing" style="display:none;">
		<br/>
		<center><img src="spinner.gif"/></center>
	</div>';

	return $step2;
}

function admin(){

	if(!isset($_SESSION['admin'])){
		header('Location: index.php?step=2&notadmin');
	}
	$admin = '<div id="admin">';
	if(isset($_GET['page'])){
		$page = $_GET['page'];

		switch ($page) {
			case "batch":
				$admin .= viewbatchjobs();
			break;
			case "users":
				$admin .= viewusers();
			break;
			case "logs":
				$admin .= viewlogs();
			break;
			case "editusers":
				$admin .= editusers();
			break;
			case "batchedit":
				$admin .= batchedit();
			break;
			case "approve":
				$admin .= approval();
			break;
			case "logsApproval":
				$admin .= approvalHistory();
			break;
			case "statusupdate":
				$admin .= updateStatus();
			break;

		}
	}
	else{
		$admin .= viewbatchjobs();
	}
	$admin .= '</div>';
	//Sidebar for Admin Panel
	$admin .= '<div id="adminSidebar">';
	$admin .= '<p><h3>Admin Panel</h3></p>';
	$admin .= '<p><b><center>Batch Jobs</center></b></p>';
	$admin .= '<p><button class="submit" onclick="window.location.href = \'?step=admin&page=batch\';">View Batch Jobs</button></p>';
	$admin .= '<p><button class="submit" onclick="window.location.href = \'?step=admin&page=approve\';">Approve Uploaded Files</button></p>';
	$admin .= '<p><button class="submit" onclick="window.location.href = \'?step=admin&page=statusupdate\';">Update Status</button></p>';
	$admin .= '<p><b><center>Authorised Users</center></b></p>';
	$admin .= '<p><button class="submit" onclick="window.location.href = \'?step=admin&page=users\';">View Users</button></p>';
	$admin .= '<p><b><center>Logs</center></b></p>';
	$admin .= '<p><button class="submit" onclick="window.location.href = \'?step=admin&page=logs\';">View Batch Upload Logs</button></p>';
	//$admin .= '<p><button class="submit" onclick="window.location.href = \'?step=admin&page=logsApproval\';">View Approval History</button></p>';
	$admin .= '</div>';
	$admin .= '<div style="clear:both;"></div> ';

	$admin .= '<script>';
	$admin .= '
		$(document).ready(function() {
			var newHeight = $(content).height();
			$(adminSidebar).css("height",newHeight);

		});
	';
	$admin .= '</script>';

	return $admin;
}

function viewbatchjobs(){

	$content = '<div id="adminpanelheader">';
	$content .= '<h3>View Batch Jobs</h3>';
	$content .= '<button class="submit buttonRight" style="float: right; margin-top: -45px;" onclick="dialognewuser();">New Batch Job</button>';
	$content .= '</div>';

	$sql="select * from batchfiles order by bussinessname";
	$result=databaseQuery($sql);
	$content .= '<div id="overflowBatch">';
	$content .= '<center><table id="table-a">';
	$content .= '<thead><tr><th>BUSSINESS NAME</th><th>BATCH NAME</th><th>FILE OUTPUT NAME</th><th>NUMBER FIELDS</th><th>ENABLED</th><th></th></tr></thead><thbody>';

	while ($row = oci_fetch_array($result, OCI_ASSOC)) {

		if($row['COMPLETED'] == "1"){
			$checkbox = '<center><input type="checkbox" name="defaultuser" value="defaultuser" checked disabled></center>';
		}
		else{
			$checkbox = '<center><input type="checkbox" name="defaultuser" value="defaultuser" disabled></center>';
		}

		$content .= '<tr><td>'.$row['BUSSINESSNAME'].'</td><td>'.$row['BATCHNAME'].'</td><td><div class="filenamefieldlogs">'.$row['FILEOUTPUTNAME'].'</div></td><td>'.$row['EXPECTEDNUMBERFIELDS'].'</td><td>'.$checkbox.'</td><td><button class="submitSmall buttonRight" style="float: right;" onclick="window.location.href = \'index.php?step=admin&page=batchedit&batname='.$row['BATCHNAME'].'\';">View More & Edit</button></td></td></tr>';
	}

	$content .= '</thbody></table></center></div>';

	$content .= '';

	$content .= '
	<script>
	function dialognewuser(){
       $( "#dialog" ).dialog({
		width: 500,
		modal: true
	   });
    };
    </script>

	<div id="dialog" title="Add A Batch Job" style="display:none;">
		<form class="form_settings" method="POST" action="proc.php?newjob">
		<center><table id="table-b" ">
		<tr><td style="width:450px><label for="addbusname">Bussiness Name:</label></td></tr>
		<tr><td><input type="addbusname" name="addbusname"></td></tr>
		<tr><td><label for="addbatname">Batch Name:</label></td>
		<tr><td><input type="addbatname" name="addbatname"></td></tr>
		<tr><td><label for="addfilename">File Output Name (RegEx):</label></td>
		<tr><td><input type="addfilename" name="addfilename"></td></tr>
		<tr><td><label for="examplefilename">Example File Name:</label></td>
		<tr><td><input type="examplefilename" name="examplefilename"></td></tr>
		<tr><td><label for="addexample">Job Columns: (Comma Seperated)</label></td>
		<tr><td><input type="addexample" name="addexample"></td></tr>
		<tr><td><input type="submit" name="submit" value="Create Job" class="submit"></td></tr>
		</table></center>
		</form>
	</div>';

	return $content;
}

function batchedit(){
	//SBTD-6000: Added visibility of FOOTER detail to 'Edit Batch job' screen

	if(isset($_GET['batname'])){
		$batname = $_GET['batname'];
	}
	else{

	}

	$sql="select b.id, b.bussinessname, b.batchname, b.usecasename, b.fileoutputname, b.examplefilename, b.outputfilelocation, b.expectednumberfields, b.requiredfields, b.telephonefields, b.emailfields,  b.digitonly,  b.examplefile,  b.completed, b.specialrules, b.propertyname, b.street, b.town, b.postcodefields, b.hasfooter, b.footerrows FROM batchfiles b WHERE b.batchname='$batname'";
	$result=databaseQuery($sql);

	while ($row = oci_fetch_array($result, OCI_ASSOC)) {

		$content = '<div id="adminpanelheader">';
		$content .= '<h3>Edit Batch Job - '.$row['BUSSINESSNAME'].'</h3>';
		$content .= '<button class="submit buttonRight" style="float: right; margin-top: -45px;" onclick="saveBatchFileChanges();">Save Changes</button>';
		$content .= '</div>';

		$content .= '<table id="table-b"><thbody>';
		$content .= '<tr><td class="editbatch">Bussiness Name:</td><td><input style="width: 300px;" id="businessname'.$row['ID'].'" type="text" value="'.$row['BUSSINESSNAME'].'"/></td></tr>';
		$content .= '<tr><td class="editbatch">Batch Name:</td><td><input style="width: 300px;" id="batchname'.$row['ID'].'" type="text" value="'.$row['BATCHNAME'].'"/></td></tr>';
		$content .= '<tr><td class="editusecase">UseCase Name:</td><td><input style="width: 300px;" id="usecasename'.$row['ID'].'" type="text" value="'.$row['USECASENAME'].'"/></td></tr>';
		$content .= '<tr><td class="editbatch">File Name:</td><td><input style="width: 300px;" id="filename'.$row['ID'].'" type="text" value="'.$row['FILEOUTPUTNAME'].'"/></td></tr>';
		$content .= '<tr><td class="editbatch">Example File Name:</td><td><input style="width: 300px;" id="examplefilename'.$row['ID'].'" type="text" value="'.$row['EXAMPLEFILENAME'].'"/></td></tr>';
		$content .= '<tr><td class="editbatch">Number of Records (CALCULATED):</td><td>'.$row['EXPECTEDNUMBERFIELDS'].'</td></tr>';
		$content .= '<tr><td class="editbatch">Has Footer:</td><td>'.createCheckbox("hasfooter".$row['ID'], $row['HASFOOTER'], "style=\"float:left;\"").'</td></tr>';
		$content .= '<tr><td class="editbatch">Number of Footer Rows:</td><td><input style="width: 25px;" id="footerrows'.$row['ID'].'" type="text" value="'.$row['FOOTERROWS'].'"/></td></tr>';
		$content .= '<tr><td class="editbatch">Output Folder:</td><td>'.$row['OUTPUTFILELOCATION'].'</td></tr>';
		$content .= '<tr><td class="editbatch">Enabled:</td><td>'.createCheckbox("enabled".$row['ID'], $row['COMPLETED'], "style=\"float:left;\"").'</td></tr>';

		$specialRule = $row['SPECIALRULES'];
		$example = $row['EXAMPLEFILE']->load();
		$required =  explode(",", $row['REQUIREDFIELDS']);
		$phonenum =  explode(",", $row['TELEPHONEFIELDS']);
		$email =  explode(",", $row['EMAILFIELDS']);
		$digits =  explode(",", $row['DIGITONLY']);
		$propertyname =  explode(",", $row['PROPERTYNAME']);
		//$postcode =  explode(",", $row['POSTCODEFIELDS']);
		//$street =  explode(",", $row['STREET']);
		//$town =  explode(",", $row['TOWN']);

		$content .= '<tr><td class="editbatch"></td><td></td></tr>';
		$content .= '</thbody></table>';
		$content .= '<span style="float:left; margin-bottom: 10px;">Expected Columns in file:</span><br/>';
		$content .= '<div id="overflowBatch">';
		$content .= '<table id="table-b">';
		$content .= '<thead><tr><th>FIELD</th><th>DATA REQUIRED</th><th>PHONE NUMBER</th><th>EMAIL ADDRESS</th><th>NUMBER ONLY</th><th>ADDRESS FIELD</th></tr></thead><thbody>';

		if($specialRule == "pipe"){
			$pieces = explode("|", $example);
		}
		else{
			$pieces = explode(",", $example);
		}
		for($i = 0; $i <sizeof($pieces); $i++){

			$content .= '<tr><td>'.$pieces[$i].'</td><td>'.createCheckbox("required".$i, $required[$i]).'</td><td>'.createCheckbox("phonenumber".$i, $phonenum[$i]).'</td><td>'.createCheckbox("email".$i, $email[$i]).'</td><td>'.createCheckbox("digit".$i, $digits[$i]).'</td><td>'.createCheckbox("propertyname".$i, $propertyname[$i]).'</td></tr>';


		}
		$content .= '</thbody>';
		$content .= '</table></div>';
		//SBTD-6000: Added visibility of FOOTER detail to 'Edit Batch job' screen
		$content .= '<script>
		$("#businessname'.$row['ID'].'").change( function() {
			$.ajax({
				type: "POST",
				url: "proc.php?editbatch-busname",
				data: { id: "'.$row['ID'].'", name: $(this).attr("value") }
			});
		});
		$("#batchname'.$row['ID'].'").change( function() {
			$.ajax({
				type: "POST",
				url: "proc.php?editbatch-batname",
				data: { id: "'.$row['ID'].'", name: $(this).attr("value") }
			});
		});
		$("#usecasename'.$row['ID'].'").change( function() {
			$.ajax({
				type: "POST",
				url: "proc.php?editbatch-usecasename",
				data: { id: "'.$row['ID'].'", name: $(this).attr("value") }
			});
		});
		$("#filename'.$row['ID'].'").change( function() {
			$.ajax({
				type: "POST",
				url: "proc.php?editbatch-file",
				data: { id: "'.$row['ID'].'", name: $(this).attr("value") }
			});
		});
		$("#examplefilename'.$row['ID'].'").change( function() {
			$.ajax({
				type: "POST",
				url: "proc.php?editbatch-examplefile",
				data: { id: "'.$row['ID'].'", name: $(this).attr("value") }
			});
		});
		$("#hasfooter'.$row['ID'].'").change( function() {
			if($(this).is(\':checked\')){
				$.ajax({
					type: "POST",
					url: "proc.php?editbatch-hasfooter",
					data: { id: "'.$row['ID'].'", check: "1" }
				});
			}
			else{
				$.ajax({
					type: "POST",
					url: "proc.php?editbatch-hasfooter",
					data: { id: "'.$row['ID'].'", check: "0" }
				});
			}
		});
		$("#footerrows'.$row['ID'].'").change( function() {
			$.ajax({
				type: "POST",
				url: "proc.php?editbatch-footerrows",
				data: { id: "'.$row['ID'].'", name: $(this).attr("value") }
			});
		});
		$("#enabled'.$row['ID'].'").change( function() {
			if($(this).is(\':checked\')){
				$.ajax({
					type: "POST",
					url: "proc.php?editbatch-comp",
					data: { id: "'.$row['ID'].'", check: "1" }
				});
			}
			else{
				$.ajax({
					type: "POST",
					url: "proc.php?editbatch-comp",
					data: { id: "'.$row['ID'].'", check: "0" }
				});
			}
		});

		function saveBatchFileChanges(){
			var records = '.$row['EXPECTEDNUMBERFIELDS'].';
			var required = "";
			var phonenumbers = "";
			var emails = "";
			var postcodes = "";
			var digits = "";
			var propertynames = "";
			var streets = "";
			var towns = "";

			for(i=0;i<records;i++){

				if($("#required"+i).is(\':checked\')){
					ans = 1;
				}
				else{
					ans = 0;
				}
				required = required+","+ans;

				if($("#phonenumber"+i).is(\':checked\')){
					ans = 1;
				}
				else{
					ans = 0;
				}
				phonenumbers = phonenumbers+","+ans;

				if($("#email"+i).is(\':checked\')){
					ans = 1;
				}
				else{
					ans = 0;
				}
				emails = emails+","+ans;

				if($("#postcode"+i).is(\':checked\')){
					ans = 1;
				}
				else{
					ans = 0;
				}
				postcodes = postcodes+","+ans;

				if($("#digit"+i).is(\':checked\')){
					ans = 1;
				}
				else{
					ans = 0;
				}
				digits = digits+","+ans;

				if($("#propertyname"+i).is(\':checked\')){
					ans = 1;
				}
				else{
					ans = 0;
				}
				propertynames = propertynames+","+ans;

				if($("#street"+i).is(\':checked\')){
					ans = 1;
				}
				else{
					ans = 0;
				}
				streets = streets+","+ans;

				if($("#town"+i).is(\':checked\')){
					ans = 1;
				}
				else{
					ans = 0;
				}
				towns = towns+","+ans;

			}

			required = required.substring(1);
			phonenumbers = phonenumbers.substring(1);
			emails = emails.substring(1);
			postcodes = postcodes.substring(1);
			digits = digits.substring(1);
			propertynames = propertynames.substring(1);
			streets = streets.substring(1);
			towns = towns.substring(1);

			$.ajax({
				type: "POST",
				url: "proc.php?editbatch-ticks",
				data: { id: "'.$row['ID'].'", requ: required, phone: phonenumbers, email: emails, post: postcodes, digit: digits, propertyname: propertynames, street: streets, town: towns }
			});
			openBanner("Data saved!");
		}

		</script>';
	}
	return $content;

}

function createCheckbox($name, $dbvalue, $style){
	if($dbvalue == "1"){
		$checkbox = '<center><input '.$style.' id="'.$name.'" type="checkbox" name="'.$name.'" value="1" checked></center>';
	}
	else{
		$checkbox = '<center><input '.$style.' id="'.$name.'" type="checkbox" name="'.$name.'"  value="0"></center>';
	}
	return $checkbox;
}

function viewusers(){

	$users = '<div id="adminpanelheader">';
	$users .= '<h3>View Allowed Users</h3>';
	$users .= '<button class="submit buttonRight" style="float: right; margin-top: -45px;" onclick="window.location.href = \'?step=admin&page=editusers\';">Edit Users</button>';
	$users .= '</div>';

	$sql="select * from allowedusers order by userid";
	$result=databaseQuery($sql);

	$users .= '<center><table id="table-a">';
	$users .= '<thead><tr><th>USERID</th><th>EMAIL</th><th>ADMIN</th></tr></thead><thbody>';

	while ($row = oci_fetch_array($result, OCI_ASSOC)) {

		if($row['ADMIN'] == "1"){
			$checkbox = '<center><input type="checkbox" name="defaultuser" value="defaultuser" checked disabled></center>';
		}
		else{
			$checkbox = '<center><input type="checkbox" name="defaultuser" value="defaultuser" disabled></center>';
		}

		$users .= '<tr><td>'.$row['USERID'].'</td><td>'.$row['EMAIL'].'</td><td>'.$checkbox.'</td></tr>';
	}

	$users .= '</thbody></table></center>';

	return $users;
}

function editusers(){

	$users = '<div id="adminpanelheader">';
	$users .= '<h3>View Allowed Users</h3>';
	$users .= '<button class="submit buttonRight" style="float: right; margin-top: -45px;">Save Changes</button>';
	$users .= '</div>';

	$sql="select * from allowedusers order by userid";
	$result=databaseQuery($sql);

	$users .= '<center><table id="table-a">';
	$users .= '<thead><tr><th>USERID</th><th>EMAIL</th><th>ADMIN</th><th>REMOVE</th></tr></thead><thbody>';

	while ($row = oci_fetch_array($result, OCI_ASSOC)) {

		if($row['ADMIN'] == "1"){
			$checkbox = '<center><input id="check'.$row['ID'].'" type="checkbox" name="edituseradmin" checked></center>';
		}
		else{
			$checkbox = '<center><input id="check'.$row['ID'].'" type="checkbox" name="edituseradmin"></center>';
		}

		$users .= '<tr><td>'.$row['USERID'].'</td><td><input class="fill" id="email'.$row['ID'].'" type="text" value="'.$row['EMAIL'].'"/></td><td>'.$checkbox.'</td><td><button class="submitSmall buttonRight" style="float: right;" onclick="window.location.href = \'proc.php?removeuser&id='.$row['ID'].'\';">Remove User</button></td></tr>';

		$users .= '<script>

				$("#email'.$row['ID'].'").change( function() {
					$.ajax({
						type: "POST",
						url: "proc.php?edituser-email",
						data: { id: "'.$row['ID'].'", email: $(this).attr("value") }
					});
				});

				$("#check'.$row['ID'].'").change( function() {
					if($(this).is(\':checked\')){
						$.ajax({
							type: "POST",
							url: "proc.php?edituser-admin",
							data: { id: "'.$row['ID'].'", check: "1" }
						});
					}
					else{
						$.ajax({
							type: "POST",
							url: "proc.php?edituser-admin",
							data: { id: "'.$row['ID'].'", check: "" }
						});
					}
				});

				</script>';

	}

	$users .= '</thbody></table ></center>';
	$users .= '<button class="submit buttonRight" style="float: right;" onclick="dialognewuser();">New User</button>';

	$users .= '
	<script>
	function dialognewuser(){
       $( "#dialog" ).dialog({
		width: 500,
		modal: true
	   });
    };
    </script>

	<div id="dialog" title="Add A New User" style="display:none;">
		<form class="form_settings" method="POST" action="proc.php?newuser">
		<center><table id="table-b">
		<tr><td><label for="addusername">Windows UserID:</label></td></tr>
		<tr><td><input type="addusername" name="addusername"></td></tr>
		<tr><td><label for="adduseremail">Email:</label></td>
		<tr><td><input type="adduseremail" name="adduseremail"></td></tr>
		<tr><td>
		<table>
		<tr><td  style="width: 90%;"><label for="addusercheck">Admin:</label></td><td><input id="addusercheck" style="width: 20px;" type="checkbox" name="addusercheck"></td></tr>
		</table>
		</td></tr>
		<tr><td><input type="submit" name="submit" value="Create User" class="submit"></td></tr>
		</table></center>
		</form>
	</div>';


	return $users;
}
function viewlogs(){

	//Search Fields
	if(isset($_POST['u'])){
		$_SESSION['searchusername'] = $_POST['u'];
		$_SESSION['search'] =true;
	}
	if(isset($_POST['f'])){
		$_SESSION['searchfile'] = $_POST['f'];
		$_SESSION['search'] =true;
	}
	if(isset($_POST['start'])){
		$_SESSION['searchstart'] = $_POST['start'];
		$_SESSION['search'] =true;
	}
	if(isset($_POST['finish'])){
		$_SESSION['searchfinish'] = $_POST['finish'];
		$_SESSION['search'] =true;
	}
	//Clear Search
	if(isset($_GET['clear'])){
		unset($_SESSION['searchusername']);
		unset($_SESSION['searchfile']);
		unset($_SESSION['searchstart']);
		unset($_SESSION['searchfinish']);
		unset($_SESSION['search']);
	}

	$content = '<div id="adminpanelheader">';
	$content .= '<h3>View Error Logs</h3>';
	$content .= '<center><form  method="POST" action="index.php?step=admin&page=logs&search"><table id="table-b">';
	$content .= '<thead><tr><td><h4>Search:</h4></td><td></td><td></td><td></td></tr></thead><thbody>
	<tr><td>UserID:</td><td><input type="text" id="u" name="u" value="'.$_SESSION['searchusername'].'"/></td><td>Start Date:</td><td><input type="text" id="start" name="start" value="'.$_SESSION['searchstart'].'"/>';
	if($_SESSION['search']){
		$content .= '<input type="button" class="submitSmall" style="float: right;" onclick="window.location.href = \'index.php?step=admin&page=logs&clear\';" value="Clear"/></td></tr>';
	}
	$content .= '<tr><td>File Name:</td><td><input type="text" id="f" name="f" value="'.$_SESSION['searchfile'].'"/></td><td>End Date:</td><td><input type="text" name="finish" id="finish" value="'.$_SESSION['searchfinish'].'"/><input type="submit" class="submitSmall" style="float: right;" value="Search"/></td></tr>';
	$content .= '</thbody></table></form>';
	$content .= '</div>';


	$sql ='select outer.*
		FROM (SELECT ROWNUM rn, inner.*
			 FROM ( SELECT u.id, u.userid, COUNT(*) OVER () RC, TO_CHAR(u.timestamp,\'DD-MM-YY HH24:MI\') as TIMESTAMP, u.validfile, u.filename, u.errormessage, b.bussinessname FROM uploadlog u
					JOIN batchfiles b
					ON u.batchid=b.id';

	$i = 0;
	if(isset($_GET['search'])){

		if(isset($_SESSION['searchusername']) && $_SESSION['searchusername'] != NULL){
			$arrayForSearch[$i] = 'u.userid LIKE \'%'.$_SESSION['searchusername'].'%\'';
			$i++;
		}
		if(isset($_SESSION['searchfile'])){
			$arrayForSearch[$i] = 'u.filename LIKE \'%'.$_SESSION['searchfile'].'%\'';
			$i++;
		}
		if(isset($_SESSION['searchstart']) && $_SESSION['searchstart'] != NULL){
			$arrayForSearch[$i] = 'u.timestamp BETWEEN TO_DATE(\''.$_SESSION['searchstart'].'\',\'DD-MM-YYYY\') AND TO_DATE(\''.$_SESSION['searchfinish'].'\',\'DD-MM-YYYY\')';
			$i++;
		}
	}
	if(isset($_GET['from'])){
		$from = $_GET['from'];
	}
	else{
		$from = 0;
	}
	$to = $from + 5;

	$search = '';
	for($p=0;$p<$i; $p++){
		if($p == 0){
			$search .= ' WHERE';
		}
		else{
			$search .= ' AND';
		}
		$search .= ' '. $arrayForSearch[$p] ;
	}

	$sql .= $search;

	if($from != 0){
		$tmpto = $to-1;
	}
	else{
		$tmpto = $to;
	}

	$sql .= ' ORDER BY u.timestamp DESC ) inner) outer
		WHERE outer.rn BETWEEN '.$from.' AND '.$tmpto;

	$result=databaseQuery($sql);
	$content .= '<div id="overflowBatch">';
	$content .= '<table id="table-a" style="margin-top: 110px;">';
	$table .= '<thead><tr><th>BATCH JOB</th><th>TIMESTAMP</th><th>FILE NAME</th><th>USER</th><th>VIEW LOG</th></tr></thead><thbody>';

	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$viewLog = $row['ERRORMESSAGE']->load();
		$date = $row['TIMESTAMP'];
		$totalRecords = $row['RC'];
		if($row['VALIDFILE']){
			$busname = '<span style="color: green;">'.$row['BUSSINESSNAME'].'</span>';
		}
		else{
			$busname = '<span style="color: red;">'.$row['BUSSINESSNAME'].'</span>';
		}

		$table .= '<tr><td>'.$busname.'</td><td>'.$date.'</td><td><div class="filenamefieldlogs">'.pathinfo($row['FILENAME'],PATHINFO_BASENAME).'</div></td><td>'.$row['USERID'].'</td><td><button class="submitSmall" style="float: right; width:100px" onclick="dialogViewLog('.$row['ID'].')">View Log</button></td></tr>';
	}

	$table .= '</thbody></table></div>';

	$content .= $table;
	$content .= pageination($from, $to, $totalRecords);
	$content .= '
	<script>
	function dialogViewLog(id){

		$.ajax({
		  url: "proc.php?viewlog&id="+id,
		  success: function(data) {
			$("#logcontents").html(data);
		  }
		});
       $( "#dialog" ).dialog({
		width: 800,
		height: 800,
		modal: true
	   });
    };
    </script>

	<div id="dialog" title="View Log" style="display:none;">
		<div id="logcontents"></div>
	</div>';

	return $content;
}

function pageination($from, $to, $total){

	$links = '<div id="pagination">';
	//$links .='Displaying Records: '.$from.' - '.$to .' of '.$total;
	$perpage = $to-$from;
	$inc = 5;

	$numberPages = ceil($total/$perpage);

	$links .= '<div id="pagenumbercontainer">';
	if($from > 0){
		if($from <= $inc){
			$previous = 0;
		}
		else{
			$previous = $from-$inc ;
		}
		$links .= '<a href="index.php?step=admin&page=logs&search&from='.$previous.'" class="pagenumber"><</a>';
	}
	for($i=0; $i <$numberPages; $i++){
		if($from == ($i*$inc)){
			$links .= '<a href="index.php?step=admin&page=logs&search&from='.($i*$inc).'" class="pagenumberactive">'.($i+1).'</a>';
		}
		else{
			$links .= '<a href="index.php?step=admin&page=logs&search&from='.($i*$inc).'" class="pagenumber">'.($i+1).'</a>';
		}
	}
	if($to < $total){
		$next = $from+$inc;
		$links .= '<a href="index.php?step=admin&page=logs&search&from='.$next.'" class="pagenumber">></a>';
	}
	$links .='</div></div>';

	return $links;

}

function validationResults(){
	$entry = $_GET['entry'];
	$content = '<div id="thecontent">';
	$content .= '<h3>Step 4 - Validation Results:</h3>';

	$content .='

	';

	$sql="select * from uploadlog u where u.id=".$entry;
	$result=databaseQuery($sql);

	$content .= '<p>';
	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$content .='<div id="validationResults">';
		$content .= $row['ERRORMESSAGE']->load();
		$content .='</div>';
		$validFile = $row['VALIDFILE'];
		if($row['QAS'] != NULL){
			$QAS = $row['QAS']->load();
		}
	}
	if($validFile){
		$content .= '<p style="margin-bottom: 35px;"><input type="button" id="submit" name="submit" value="Continue" class="submit" style="float: right;" onclick="window.location.href = \'index.php?step=5&entry='.$entry.'\';"/></p>';
	}
	else{
		$content .= '<p style="margin-bottom: 35px;"><input type="button" id="submit" name="submit" value="Restart" class="submit" style="float: right;" onclick="window.location.href = \'proc.php?cleanup&entry='.$entry.'\';"/></p>';
	}
	$content .= '</div>';

	$content .= '
	<script>

	function dialogViewLog(){

       $( "#dialog" ).dialog({
		width: 1000,
		height: 800,
		modal: true
	   });
    };
    </script>

	<div id="dialog" title="View QAS Results" style="display:none;">
		<div id="logcontents">
		<script>
	$("#green").change( function() {
		if($(this).is(\':checked\')){
			$(".batg").show();
		}
		else{
			$(".batg").hide();
		}
	});
	$("#orange").change( function() {
		if($(this).is(\':checked\')){
			$(".bato").show();
		}
		else{
			$(".bato").hide();
		}
	});
	$("#red").change( function() {
		if($(this).is(\':checked\')){
			$(".batr").show();
		}
		else{
			$(".batr").hide();
		}
	});
	</script><pre>'.$QAS.'</pre></div>
	</div>';

	return $content;
}

function submit(){

	$entry = $_GET['entry'];
	$content .= '<div id="thecontent">';
	$content .= '<h3>Step 5 - Submit & Complete:</h3>';

	$sql="select * from uploadlog u where u.id=".$entry;
	$result=databaseQuery($sql);

	$content .= '<p>';

	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$file = $row['FILENAME'];
		$validFile = $row['VALIDFILE'];
	}

	//Clean up tmp folder, remove tmp file for this task
	$file = pathinfo($file,PATHINFO_BASENAME);
	$file = 'upload/tmpUpload/'.$file;
	unlink($file);
	$content .= '<script> openBanner("Spark Approval Coming Soon...");</script>';

	$content .= '<h4>Approval:</h4>';

	$content .= '<form class="form_settings" method="POST" action="proc.php?submitbatchjob">';
	$content .= '<input type="hidden" name="userid" value="'.$_SESSION['username'].'" />';
	$content .= '<input type="hidden" name="status" value="0" />';
	$content .= '<input type="hidden" name="filename" value="'.$file.'" />';
	$content .= '<table >';
	$content .= '<tr><td>Your Line Manager: </td><td><input type="text" name="linemanger" id="linemanger"/></td></tr>';
	$content .= '<tr><td>Requested Start Time: </td><td><input type="text" name="starttime" id="starttime" value="ASAP"/></td></tr>';
	$content .= '<tr><td>Comments: </td><td><textarea name="comments" id="comments"></textarea></td></tr>';
	$content .= '<tr><td></td><td><input type="submit" name="submit" value="Submit" class="submit"></td></tr>';
	$content .= '</table>';
	$content .= '</form>';

	$content .= '</div>';

	return $content;
}

function address(){

	$content = '<div id="login">';
	$content .= '<h1>Address Validator</h1>';
	$content .= 'Validate an Address against QAS';
	$content .= '<form class="form_settings" id="form">';
	$content .= '<center><table>';
	$content .= '<tr><td><label for="pname" style="float: left;">Property Name/Number:</label></td>';
	$content .= '<td><input type="text" name="pname" id="pname" value=""></td></tr>';
	$content .= '<tr><td><label for="street" style="float: left;">Street:</label></td>';
	$content .= '<td><input type="text" name="street" id="street" value=""></td></tr>';
	$content .= '<tr><td><label for="town" style="float: left;">Town:</label></td>';
	$content .= '<td><input type="text" name="town" id="town" value=""></td></tr>';
	$content .= '<tr><td><label for="postcode" style="float: left;">Postcode:</label></td>';
	$content .= '<td><input type="text" name="postcode" id="postcode" value=""></td></tr>';
	$content .= '<tr><td></td><td><input type="button" name="submit" value="Submit" class="submit"  onclick="submitAddress()" style="float: right;"></td></tr>';
	$content .= '</table></center>';
	$content .= '</form>';
	$content .= '<div id="infoBox3">';
	$content .= '</div>';
	$content .= '</div>';

	$content .= '<script>
	function submitAddress(){
		func = ($(\'#pname\').val()+","+$(\'#street\').val()+","+$(\'#town\').val()+","+$(\'#postcode\').val());
		$.ajax({
			type: "POST",
			url: "proc.php?QAS",
			async: false,
			cache: false,
			data: { address: func },
			success: function(data) {
				displayResults(data);
		  }
		});
	}

	function displayResults(data){

		if(data == "Address Verifed"){
			openBanner("Address Exists In QAS");
			$("#infoBox3").hide();
			$("#infoBox3").html("<b><span style=\"color:green\">Address Verified!</span></b><br/><br/>");
			$("#infoBox3").show();
		}
		else if (data ==  "No Matches Found"){
			openBanner("Address Does not exists in QAS");
			$("#infoBox3").hide();
			$("#infoBox3").html("<b><span style=\"color:red\">No Results Returned!</span></b><br/><br/>");
			$("#infoBox3").show();
		}
		else{

			result = "<span style=\"float: left;\">The address you provided returned the following Results:</span><br/><table id=\"table-a\">";

			data = data.substring(1, data.length-1);
			var data=data.split(",");

			for(i=0;i<data.length;i++){
				var n=data[i].replace(/#/g,",");
				n=n.replace(/"/g,"");

				result = result+"<tr><td>"+n+"<td></tr>";
			}
			result = result +"</table>";
			$("#infoBox3").html(result);
			$("#infoBox3").show();
		}

	}
	</script>';


	return $content;

}

function progress(){

	$content = '<h3>Batch Job Progress</h3>';

	$sql ='select * from approvaltable';

	$result=databaseQuery($sql);

	$content .= '<table id="table-a" style="margin-top: 50px;">';
	$content .= '<thead><tr><th>REQUESTED USER</th><th>FILENAME</th><th>STATUS</th><th>REQUESTED START</th><th>SUBMITTED</th></tr></thead><thbody>';

	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$status = $row['STATUS'];
		switch ($status) {
			case "0":
				$status = '<span style="color: orange;">Awaiting Approval</span>';
			break;
			case "1":
				$status = '<span style="color: green;">Approved</span>';
			break;
			case "2":
				$status = '<span style="color: green;">Batch File Submitted</span>';
			break;
			case "3":
				$status = '<span style="color: green;">Complete</span>';
			break;
			case "4":
				$status = '<span style="color: orange;">Complete With Some Errors</span>';
			break;
			case "5":
				$status = '<span style="color: red;">Approval Rejected</span>';
			break;
			case "6":
				$status = '<span style="color: red;">Failed</span>';
			break;
		}
		$content .= '<tr><td>'.$row['USERID'].'</td><td><div class="filenamefieldlogs">'.pathinfo($row['FILENAME'],PATHINFO_BASENAME).'</div></td><td>'.$status.'</td><td>'.$row['RUNTIME'].'</td><td>'.$row['TIMESTAMP'].'</td></tr>';
	}

	$content .= '</thbody></table>';

	return $content;

}

function approval(){

	$content = '<div id="adminpanelheader">';
	$content .= '<h3>Approve Uploaded Files</h3>';
	$content .= '</div>';

	$sql="select * from APPROVALTABLE t where t.status=0 order by t.timestamp";
	$result=databaseQuery($sql);

	$content .= '<center><table id="table-a">';
	$content .= '<thead><tr><th>REQUESTED USER</th><th>RUN TIME</th><th>FILE LOCATION</th><th>UPLOADED DATE</th><th>REQUESTOR LINE MANAGER</th><th>ACTIONS</th></tr></thead><thbody>';

	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$content .= '<tr><td>'.$row['USERID'].'</td><td><input style="width: 50px;" type="text" id="starttime'.$row['ID'].'" value="'.$row['RUNTIME'].'"/></td><td><div class="filenamefieldlogs">'.pathinfo($row['FILENAME'],PATHINFO_BASENAME).'</div></td><td>'.$row['TIMESTAMP'].'</td><td>'.$row['LINEMANAGER'].'</td><td><button class="submitSmall buttonRight" style="float: right;" onclick="approve('.$row['ID'].');">Approve</button><button class="submitSmall buttonRight" style="float: right;" onclick="decline('.$row['ID'].');">Decline</button></td></td></tr>';
	}

	$content .= '
	<script>
		function approve(id){
			var link = "proc.php?approve&id="+id+"&time=";
			var time = document.getElementById(\'starttime\'+id).value;
			link = link + time;
			window.location.href = link;
		}

		function decline(id){
			var link = "proc.php?decline&id="+id;
			window.location.href = link;
		}
	</script>
	';
	$content .= '</thbody></table></center>';

	$content .= '';

	return $content;

}

function approvalHistory(){

}

function createStatusCombo($current, $id){
	$array[$current] = "selected";
	$content = '<select id="status'.$id.'" name="status'.$id.'" style="width: 100%;">';
	$content .= '<option value="0" '.$array[0].'>Awaiting Approval</option>';
	$content .= '<option value="1" '.$array[1].'>Approved</option>';
	$content .= '<option value="2" '.$array[2].'>Batch File Submitted</option>';
	$content .= '<option value="3" '.$array[3].'>Complete</option>';
	$content .= '<option value="4" '.$array[4].'>Complete With Some Errors</option>';
	$content .= '<option value="5" '.$array[5].'>Rejected</option>';
	$content .= '<option value="6" '.$array[6].'>Failed</option>';
	$content .= '</select>';

	return $content;
}

function updaeStatus(){
	$content .= '<div id="adminpanelheader">';
	$content .= '<h3>Change Status</h3>';
	$content .= '</div>';

	$sql="select * from APPROVALTABLE t where t.status!=0  order by t.timestamp";
	$result=databaseQuery($sql);

	$content .= '<center><table id="table-a">';
	$content .= '<thead><tr><th>REQUESTED USER</th><th>RUN TIME</th><th>FILE LOCATION</th><th>UPLOADED DATE</th><th>CURRENT STATUS</th><th>ACTIONS</th></tr></thead><thbody>';

	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$content .= '<tr><td>'.$row['USERID'].'</td><td>'.$row['RUNTIME'].'</td><td><div class="filenamefieldlogs">'.pathinfo($row['FILENAME'],PATHINFO_BASENAME).'</div></td><td>'.$row['TIMESTAMP'].'</td><td>'.createStatusCombo($row['STATUS'],$row['ID'] ).'</td><td><button class="submitSmall buttonRight" style="float: right;" onclick="update('.$row['ID'].');">Update</button></td></td></tr>';
	}

	$content .= '
	<script>
		function update(id){
			var link = "proc.php?statusChange&id="+id+"&status=";
			var status = document.getElementById(\'status\'+id).value;
			link = link + status;
			window.location.href = link;
		}
	</script>
	';
	$content .= '</thbody></table></center>';

	$content .= '';
	return $content;
}


?>
