<?php
/*****************************************************************************************
** proc.php
** ---------
** Contains a number of helper procedures referenced (mainly) by lib.php
**
** Version		Who				Date			Comment
** 0.0			SSavage			02/10/2020		Starting Change Log
** 0.1			SSavage			02/10/2020		Initial version and all changes up to date - original code written in 2016
**													and updated by various members of Ops Support team since then
** 0.2			SSavage			02/10/2020		SBTD-6000: Added visibility of FOOTER detail to 'Select Batch job' screen
**
**
**
** 0.n			TBC				DD/MM/YYYY		Description
**
*****************************************************************************************/




session_start();
include("db.inc.php");
include("validation.php");

$array;
$bimandatory;
$bimfields;

if(isset($_GET['login'])){

	include("windowsauth.php");

	$username = $_POST['userid'];
	$password = $_POST['pass'];


	//Disabled for now for testing - if the password is wrong 7 times your account locks...
	//$tesing = true;
	//if($tesing){
	if(windowsLogin($username, $password)){

		$sql="select * from allowedusers a where a.userid='$username'";
		$result=databaseQuery($sql);
		$count=databaseCount($result);

		//If user exists then user is allowed
		if($count > 0){
			while ($row = oci_fetch_array($result, OCI_ASSOC)) {
				$_SESSION['username'] = $row['USERID'];
				//Check for Admin Privs
				if($row['ADMIN']){
					$_SESSION['admin'] = "1";
				}
			}
			header('Location: index.php?step=2');
		}
		else{
			header('Location: index.php?logindenied');
		}

	}
	else{
		header('Location: index.php?loginerror');
	}

}

if(isset($_GET['logout'])){
	session_destroy();
	header('Location: index.php?loggedout');
}

//Ajax On change email
if(isset($_GET['edituser-email'])){
	$id = $_POST['id'];
	$email = $_POST['email'];

	databaseQuery("UPDATE allowedusers SET email='$email' WHERE id='$id'");
}
//Ajax On change admin
if(isset($_GET['edituser-admin'])){
	$id = $_POST['id'];
	$check = $_POST['check'];

	databaseQuery("UPDATE allowedusers SET admin='$check' WHERE id='$id'");
}

if(isset($_GET['newuser'])){
	$userid = $_POST['addusername'];
	$userid = str_replace(' ', '', $userid);
	$email = $_POST['adduseremail'];
	$email = str_replace(' ', '', $email);
	$check = $_POST['addusercheck'];

	if($check == "on"){
		$check = "1";
	}
	else{
		$check = "";
	}

	databaseQuery("INSERT INTO allowedusers (userid, email, admin) VALUES ('$userid','$email','$check')");
	header('Location: index.php?step=admin&page=editusers');

}
if(isset($_GET['removeuser'])){
	$id = $_GET['id'];

	databaseQuery("delete from allowedusers a where a.id='$id'");
	header('Location: index.php?step=admin&page=editusers');

}

if(isset($_GET['addnotlist'])){
	$name = $_POST['addnotname'];
	$email = $_POST['addnotemail'];
	$upload = $_POST['addnotup'];
	$failed = $_POST['addnotfail'];

	if($upload == "on"){
		$upload = "1";
	}
	else{
		$upload = "";
	}
	if($failed == "on"){
		$failed = "1";
	}
	else{
		$failed = "";
	}

	databaseQuery("INSERT INTO notifications (name, email, uploadfile, failedattempt) VALUES ('$name','$email','$upload','$failed')");
	header('Location: index.php?step=admin&page=editmail');
}

if(isset($_GET['removenotlist'])){
	$id = $_GET['id'];

	databaseQuery("delete from notifications n where n.id='$id'");
	header('Location: index.php?step=admin&page=editmail');
}

//Ajax On change Name
if(isset($_GET['editnot-name'])){
	$id = $_POST['id'];
	$name = $_POST['name'];

	databaseQuery("UPDATE notifications SET name='$name' WHERE id='$id'");
}
//Ajax On change email
if(isset($_GET['editnot-email'])){
	$id = $_POST['id'];
	$email = $_POST['email'];

	databaseQuery("UPDATE notifications SET email='$email' WHERE id='$id'");
}
//Ajax On change upload
if(isset($_GET['editnot-upload'])){
	$id = $_POST['id'];
	$check = $_POST['check'];

	databaseQuery("UPDATE notifications SET uploadfile='$check' WHERE id='$id'");
}
//Ajax On change failed
if(isset($_GET['editnot-fail'])){
	$id = $_POST['id'];
	$check = $_POST['check'];

	databaseQuery("UPDATE notifications SET failedattempt='$check' WHERE id='$id'");
}

if(isset($_GET['newjob'])){
	$busname = $_POST['addbusname'];
	$batname = $_POST['addbatname'];
	$fileoutput = $_POST['addfilename'];
	$addexample = $_POST['addexample'];
	$examplefilename = $_POST['examplefilename'];
	$pieces = explode(",", $addexample);
	$numberfields = sizeof($pieces);
	$newFTPLocation = 'upload/FTP/'.$batname;

	databaseQuery("insert into batchfiles (bussinessname,batchname,fileoutputname,expectednumberfields,examplefile,completed, examplefilename, outputfilelocation) VALUES ('$busname','$batname','$fileoutput','$numberfields','$addexample','0', '$examplefilename', '$newFTPLocation')");
	mkdir($newFTPLocation);
	header('Location: index.php?step=admin&page=batchedit&batname='.$batname);
}

//Ajax On change failed
if(isset($_GET['editbatch-busname'])){
	$id = $_POST['id'];
	$name = $_POST['name'];

	databaseQuery("UPDATE batchfiles SET bussinessname='$name' WHERE id='$id'");
}
if(isset($_GET['editbatch-batname'])){
	$id = $_POST['id'];
	$name = $_POST['name'];

	databaseQuery("UPDATE batchfiles SET batchname='$name' WHERE id='$id'");
}

if(isset($_GET['editbatch-usecasename'])){
	$id = $_POST['id'];
	$name = $_POST['name'];

	databaseQuery("UPDATE batchfiles SET usecasename='$name' WHERE id='$id'");
}
if(isset($_GET['editbatch-file'])){
	$id = $_POST['id'];
	$name = $_POST['name'];

	databaseQuery("UPDATE batchfiles SET fileoutputname='$name' WHERE id='$id'");
}

if(isset($_GET['editbatch-examplefile'])){
	$id = $_POST['id'];
	$name = $_POST['name'];

	databaseQuery("UPDATE batchfiles SET examplefilename='$name' WHERE id='$id'");
}

if(isset($_GET['editbatch-comp'])){
	$id = $_POST['id'];
	$name = $_POST['check'];

	databaseQuery("UPDATE batchfiles SET completed='$name' WHERE id='$id'");
}
//SBTD-6000: Added procs to save FOOTER detail from 'Edit Batch job' screen
if(isset($_GET['editbatch-hasfooter'])){
	$id = $_POST['id'];
	$name = $_POST['check'];

	databaseQuery("UPDATE batchfiles SET hasfooter='$name' WHERE id='$id'");
}

if(isset($_GET['editbatch-footerrows'])){
	$id = $_POST['id'];
	$name = $_POST['name'];

	databaseQuery("UPDATE batchfiles SET footerrows='$name' WHERE id='$id'");
}

if(isset($_GET['editbatch-ticks'])){
	$id = $_POST['id'];
	$required = $_POST['requ'];
	$phonenumber = $_POST['phone'];
	$email = $_POST['email'];
	$postcode = $_POST['post'];
	$digit = $_POST['digit'];
	$propertyname = $_POST['propertyname'];
	$street = $_POST['street'];
	$town = $_POST['town'];

	databaseQuery("UPDATE batchfiles SET requiredfields='$required', postcodefields='$postcode', telephonefields='$phonenumber', emailfields='$email', digitonly='$digit', propertyname='$propertyname', street='$street', town='$town' WHERE id='$id'");
}

if(isset($_GET['viewlog'])){
	$id = $_GET['id'];
	$sql = 'select u.errormessage, u.qas from uploadlog u where u.id='.$id;

	$result=databaseQuery($sql);

	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$viewLog = $row['ERRORMESSAGE']->load();
		if($row['QAS'] != NULL){
			$viewQas = $row['QAS']->load();
		}
	}
	echo '<h3>Validation Results</h3>';
	echo '<pre>'.$viewLog.'</pre>';
	echo '<br/><br/><hr/><h3>QAS Results</h3>';
	if($viewQas != NULL){
		echo '<pre>'.$viewQas.'</pre>';
	}
	else{
		echo '<pre>No Results Found</pre>';

	}
}

//SBTD-6000: Added visibility of FOOTER detail to 'Select Batch job' screen
if(isset($_GET['batchinfo'])){

	$id = $_GET['job'];
	$sql = 'select * from batchfiles b where b.id='.$id;
	$result=databaseQuery($sql);

	echo '<p style="padding: 10px; width=100%;">';
	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		echo '<table><tr><td width="200px"><b>Business Name:</b></td><td>'.$row['BUSSINESSNAME'].'</td></tr>';
		echo '<tr><td><b>Batch Name:</b></td><td>'.$row['BATCHNAME'].'</td></tr>';
		if(isset($row['USECASENAME'])){
			echo '<tr><td><b>Usecase Name:</b></td><td>'.$row['USECASENAME'].'</td></tr>';
		}
		echo '<tr><td><b>Expected Columns:</b></td><td>'.$row['EXPECTEDNUMBERFIELDS'].'</td></tr>';
		echo '<tr><td><b>Expected Filename Format:</b></td><td>'.$row['FILEOUTPUTNAME'].'</td></tr>';
		echo '<tr><td><b>Example Filename:</b></td><td>'.$row['EXAMPLEFILENAME'].'</td></tr>';
		echo '<tr><td><b>Expected Footer Rows:</b></td><td>'.$row['FOOTERROWS'].'</td></tr>';
		echo '</table>';
		echo '<center style="margin-top: -20px;"><a href="example.php?batchname='.$row['BATCHNAME'].'" target="_blank">View the Template for this Batch Job</a></center>';
	}
	echo '</p>';
}

if(isset($_GET['upload'])){
	$target = "upload/tmpUpload/";
	$filename = $_FILES['file']['name'];
	$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
	$target = $target . $filename;
	$batchid = $_POST['batchid'];
	$outputMessage ='';

	if(move_uploaded_file($_FILES['file']['tmp_name'], $target))
	{
		//echo "File Uploaded!<br/>";
		//Once upload is complete run the validator
		$outputMessage .= '<br/><b>File Uploaded:</b> ' . $filename;
		$outputMessage .= '<br/><b>File Location:</b> ' . $target;
		$outputMessage .= validator($target, $batchid, $outputMessage,$filename);
	}
	else {
		$outputMessage .= "<br/>File Upload ERROR!";
	}


	$sql = 'select id from uploadlog u where u.batchid='.$batchid.' order by u.timestamp ASC';
	$result=databaseQuery($sql);
	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$lastEntry = $row['ID'];
	}

	header('Location: index.php?step=4&entry='.$lastEntry);
}



function validator($file, $batchid, $outputMessage, $filename){
//SBTD-6000: Added FOOTER details to function
global $array;
global $bimandatory;
global $bimfields;
	//File valid until proven otherwise
	$validFile = true;
	$QAS ='';
	$addressFailed = false;

	//QAS Result Vars
	$green=0;
	$orange=0;
	$red=0;

	//$sql = 'select * from batchfiles b where b.id='.$batchid;
	$sql = 'select b.bussinessname, b.batchname, b.fileoutputname, b.examplefilename, b.outputfilelocation, b.expectednumberfields, b.requiredfields, b.telephonefields, b.emailfields,  b.digitonly,  b.examplefile,  b.completed, b.specialrules, b.propertyname, b.street, b.town, b.postcodefields, b.validatefields, b.examplefile,b.specialrules,b.maxrecords, b.hasfooter, b.footerrows FROM batchfiles b WHERE b.id='.$batchid;
	$result=databaseQuery($sql);
	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$bussinessname = $row['BUSSINESSNAME'];
		$batchname = $row['BATCHNAME'];
		$fileoutput = $row['FILEOUTPUTNAME'];
		$expectednumberfields = $row['EXPECTEDNUMBERFIELDS'];
		$specialRule = $row['SPECIALRULES'];
		$example = $row['EXAMPLEFILE']->load();
		$requiredfields =  explode(",", $row['REQUIREDFIELDS']);
		$telephonefields =  explode(",", $row['TELEPHONEFIELDS']);
		$emailfields =  explode(",", $row['EMAILFIELDS']);
		$digits =  explode(",", $row['DIGITONLY']);
		$propertyname =  explode(",", $row['PROPERTYNAME']);
		$postcodefields =  explode(",", $row['POSTCODEFIELDS']);
		$street =  explode(",", $row['STREET']);
		$town =  explode(",", $row['TOWN']);
		$maxRecords = $row['MAXRECORDS'];
		$validatefields = explode(",",$row['VALIDATEFIELDS']);
		$headerrecord = $row['EXAMPLEFILE'];
		$hasFooter = $row['HASFOOTER'];   //SBTD-6000: Added FOOTER details to function
		$footerRows = $row['FOOTERROWS']; //SBTD-6000: Added FOOTER details to function
	}

	//File Output Location
	$outputdir = 'upload/validated/'.$batchname;

	$QASErrors ='';

	include ('PHPExcel/IOFactory.php');
	$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
	$cacheSettings = array( 'memoryCacheSize' => '128MB' );
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

	/*Stop phonenumber from loosing leading 0's*/
	class TextValueBinder implements PHPExcel_Cell_IValueBinder{
		public function bindValue(PHPExcel_Cell $cell, $value = null) {
			$cell->setValueExplicit($value, PHPExcel_Cell_DataType::TYPE_STRING);
			return true;
		}
	}

	$savedValueBinder = PHPExcel_Cell::getValueBinder();
	PHPExcel_Cell::setValueBinder(new TextValueBinder());

	//Find file reader type
	$inputFileType = PHPExcel_IOFactory::identify($file);

	$objReader = PHPExcel_IOFactory::createReader($inputFileType);
	if($specialRule == "pipe"){
		$objReader->setDelimiter('|');
	}
	else{
		$objReader->setDelimiter(',');
	}
	$objPHPExcel = $objReader->load($file);
	PHPExcel_Cell::setValueBinder($savedValueBinder);

	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

	//-1 removes the header row
	//SBTD-6000: And if has a footer the also remove footer row count from overall record count and update display message. 
	$numberOfRecordsFound = (count($sheetData))-1;
	if($hasFooter == 1){
		$numberOfRecordsFound = $numberOfRecordsFound-$footerRows;
		//If $numberOfRecordsFound now less than zero just set to 0 to keep the results tidier in the message output
		if($numberOfRecordsFound<0){
			$numberOfRecordsFound=0;
		}
		$outputmessage .= '<br/><b>Number of Records Found:</b> '.$numberOfRecordsFound.'<br/> (Ignoring last '.$footerRows.' rows for the Footer)<br/>';	
	}
	else{
		$outputmessage .= '<br/><b>Number of Records Found:</b> '.$numberOfRecordsFound.'<br/>';
	}



	if((isset($maxRecords) && $maxRecords!="") || $batchname = "SBBMCR033"){
		if(!checkHeader($sheetData[1], $headerrecord->load())){
			$outputmessage .= '<br/><b>ERROR FOUND:</b> INVALID FILE HEADER - The header record does not match what is expected!';
			$validFile = false;
		}

		if(($numberOfRecordsFound+1)>$maxRecords && $batchname != "SBBMCR033"){
			$outputmessage .= '<br/><b>ERROR FOUND:</b> TOO MANY RECORDS IN FILE - Max amount of records allowed in this file (including header) is: '.$maxRecords.'!';
			$validFile = false;
		}
	}

	if(checkFileExists($outputdir, $file)){
		$outputmessage .= '<br/><b>ERROR FOUND:</b> FILE ALREADY EXISTS IN OUTPUT DIRECTORY - Please ensure this job has not already been ran and rename the file and try again!';
		$validFile = false;
	}

	if(!checkFileName($file, $fileoutput)){

		$outputmessage .= '<br/><b>ERROR FOUND:</b> FILE NAME INCORRECT - Please ensure the file name matches the following: '.$file." ".$fileoutput;
		$validFile = false;
	}

	if($numberOfRecordsFound<1){
		$outputmessage .= '<br/><b>ERROR FOUND:</b> EMPTY FILE - This file contains no records to update! Invalid File Format';
		$validFile = false;
	}
	$addresses='';
	//SBTD-6000: By adjusting $numberOfRecordsFound above based on $footerRows this should alter to loop correctly to stop at the last real data record
	for($i=2; $i <= $numberOfRecordsFound+1; $i++){
		$row = $sheetData[$i];
		$numberfields = count($sheetData[$i]);

		//Check Number of fields in row against the number expected

		if($numberfields != $expectednumberfields){
			$outputmessage .= '<br/><b>ERROR FOUND: </b> at ROW: '.$i.' - Number of fields found: '.$numberfields.' Number of Fields expected: '.$expectednumberfields;
			$validFile = false;
		}
		//If row contains the correct number of fields then do the rest of the validation
		else{
			$BuildAddress="";
			$dupes; //duplicate rows;
			$dupe=""; //duplicate row

			//for job SBBBLK0TV
			$TVDupes;
			$tvDupe = "";
			$VCNo = "";

			//SBBBLK0TV
			$bimfields = "";
			for($currentCol=0; $currentCol < $numberfields; $currentCol++){

				//Converts a number into a column tag (EG A,B,C...AA,AB,etc)
				$colString = PHPExcel_Cell::stringFromColumnIndex($currentCol);
				$value = PHPExcel_Shared_String::SanitizeUTF8($row[$colString]);


				//Temporary for getting Action for validating esclated note
				$nextString = PHPExcel_Cell::stringFromColumnIndex($currentCol+1);
				$nextValue = PHPExcel_Shared_String::SanitizeUTF8($row[$nextString]);

				// $myfile = fopen("log.txt", "a");
				// fwrite($myfile, 'Line 438: ' .$value . ' - ' . $nextValue ."\n");

				$dupe.=$value;

				$tfile = "";
				$bool = true;
				if(isset($validatefields[$currentCol])&& $validatefields[$currentCol] !=""){

					//this is awful and needs replaced, but will work for now
					if($batchname == "SBBMCR270"){
						if($validatefields[$currentCol] =='VC'){
							$VCNo = $value;
						}

						if($validatefields[$currentCol] == 'PROD(A)' && $value!=''){

							$tvDupe = $VCNo .' '. $value;

							if(!in_array($tvDupe,$TVDupes)){
								$TVDupes[]=$tvDupe;
								$tvDupe="";
							}else{
								$validFile = false;
								$outputmessage .= '<br/><b>ERROR FOUND:</b> at ROW: '.$i.' - Duplicate product found for card. Please check and Remove. '.$tvDupe;
								$tvDupe="";
							}
						}
					}



					if($currentCol == $numberfields-1){
						//php 5.4+
						// $bool = validate($validatefields[$currentCol],$value,$currentCol,true,$tfile,$nextValue)[0];
						// $error = validate($validatefields[$currentCol],$value,$currentCol,true,$tfile,$nextValue)[1];
						//php < 5.4
						$bool = validate($validatefields[$currentCol],$value,$currentCol,true,$tfile,$nextValue);
						$bool = $bool[0];
						$error = validate($validatefields[$currentCol],$value,$currentCol,true,$tfile,$nextValue);
						$error = $error[1];

					}else{
						//php 5.4+
						// $bool = validate($validatefields[$currentCol],$value,$currentCol,false,$tfile,$nextValue)[0];
						// $error = validate($validatefields[$currentCol],$value,$currentCol,false,$tfile,$nextValue)[1];
						//php < 5.4
						$bool = validate($validatefields[$currentCol],$value,$currentCol,false,$tfile,$nextValue);
						$bool = $bool[0];
						$error = validate($validatefields[$currentCol],$value,$currentCol,false,$tfile,$nextValue);
						$error = $error[1];
					}


				if(!$bool){
					$validFile = false;
					if($bimfields == ""){
						$outputmessage .='<br/><b>ERROR FOUND:</b> at ROW:'.$i.' COL: '.($currentCol+1).' - <span style="color: red">'.$error.'</span> '.$tfile.' '.$bool;
					}else{
						$outputmessage .='<br/><b>ERROR FOUND:</b> at ROW:'.$i.' '.$tfile.' '.$bool;
					}
					//$outputmessage .= '<br/><b>ERROR FOUND:</b> at ROW: '.$i.' COL: '.($currentCol+1).' '.$validatefields[$currentCol].' - This record has failed validation. Please check';
				}
				}



				//Check required cols are not null
				if(($requiredfields[$currentCol] == 1) && ($value == NULL)){
					$outputmessage .= '<br/><b>ERROR FOUND:</b> at ROW: '.$i.' COL: '.($currentCol+1).' - This Value is required for this job!';
					$validFile = false;
				}
				//check if col is a postcode and that it is valid
				if(($postcodefields[$currentCol] == 1) && (!checkPostcode($value)) && ($value != NULL)){
					$outputmessage .= '<br/><b>ERROR FOUND:</b> at ROW: '.$i.' COL: '.($currentCol+1).' - This Value is not a valid Postcode!';
					$validFile = false;
				}
				//check if col is a phonenumber and that it is valid
				if(($telephonefields[$currentCol] == 1) && (!checkPhoneNumber($value)) && ($value != NULL)){
					$outputmessage .= '<br/><b>ERROR FOUND:</b> at ROW: '.$i.' COL: '.($currentCol+1).' - This Value is not a valid Phone Number!';
					$validFile = false;
				}
				//check if col is a email and that it is valid
				if(($emailfields[$currentCol] == 1) && (!checkEmail($value)) && ($value != NULL)){
					$outputmessage .= '<br/><b>ERROR FOUND:</b> at ROW: '.$i.' COL: '.($currentCol+1).' - This Value is not a valid Email Address!';
					$validFile = false;
				}
				//Build Address Line
				if($propertyname[$currentCol] == 1 && ($value != NULL)){
					if(!isset($BuildAddress["propertyname"])){
						$BuildAddress["propertyname"]=$value;
					}
					else{
						$BuildAddress["propertyname"]=$BuildAddress["propertyname"].','.$value;
					}
				}
				if($street[$currentCol] == 1 && ($value != NULL)){
					$BuildAddress["street"]=$value;
				}
				if($town[$currentCol] == 1 && ($value != NULL)){
					$BuildAddress["town"]=$value;
				}
				if($postcodefields[$currentCol] == 1 && ($value != NULL)){
					$BuildAddress["postcode"]=$value;
				}
			}
		if(isset($validatefields[$currentCol])&& $validatefields[$currentCol] !=""){
			if(!in_array($dupe,$dupes)){
				$dupes[$i]=$dupe;
			}else{

					$validFile = false;

					$outputmessage .= '<br/><b>ERROR FOUND:</b> at ROW: '.$i.' - Duplicate found in file, please remove.';
				}
			}

			$array = array();
			$bimandatory = false;
			if($BuildAddress != NULL){
				$BuildAddress = $BuildAddress["propertyname"].','.$BuildAddress["street"].','.$BuildAddress["town"].','.$BuildAddress["postcode"]."\r\n";
				$addresses[($i-1)] = $BuildAddress;
			}
		}

	}

	$QAS = '';
	$green = 0;
	$orange = 0;
	$red = 0;

	if($addresses != ""){
		$bulkProcessing = array_chunk($addresses, 100);

		include('qas-batch.php');


		for($i=0;$i < sizeof($bulkProcessing); $i++){
			$send = '';
			for($o=0;$o < sizeof($bulkProcessing[$i]); $o++){
				$send .= $bulkProcessing[$i][$o];
			}
			$bulkSearch=new bulkCheckAddresses();
			$bulkSearch->checkBulkAddress($send);
			$green += $bulkSearch->returnGreen();
			$orange += $bulkSearch->returnOrange();
			$red += $bulkSearch->returnRed();
			$QAS .= $bulkSearch->returnOutput();
			unset($bulkSearch);
			unset($send);
		}
	}
	$QAS = '<br/><center><table><td style="width: 300px;>DISPLAY:</td><tr><td style="width: 300px;"><input type="checkbox" id="green" checked/> GREEN: - ('.$green.')</td><td style="width: 300px;"><input type="checkbox" id="orange" checked/> AMBER: - ('.$orange.')</td><td style="width: 300px;"><input type="checkbox" id="red" checked/> RED - ('.$red. ')</td></tr></table></center>'.$QAS;

	if(($red>0)){
		$outputmessage .=  '<br/><br/><b>Address Validiation Failed (RED)</b> - Your file contains addresses that returned 0 Results ! ';
		$validFile = false;
	}
	if(($orange>0)){
		$outputmessage .=  '<br/><br/><b>Address Validiation (ORANGE)</b> - Your file contains addresses that returned Partial Results, These may still be accepted by Osprey. ';
	}
	if(($red>0) || ($orange>0)){
		$outputmessage .= '<br/><br/><b>QAS RESULTS</b> - <a onclick="dialogViewLog()" >Click Here for more details</a>';
	}

	if($validFile){
		$outputmessage .='<br/><b>File Validation Success</b> - File has been successfully validated!';

		if(!is_dir($outputdir)){
			mkdir($outputdir);
			chmod($outputdir, 755);
		}

		//Copy file to output folder
		$newfile = $outputdir.'/'.pathinfo($file,PATHINFO_BASENAME);
		if (!copy($file, $newfile)) {
			$outputmessage .= '<br/><br/><b>ERROR FOUND:</b> Failed to COPY file to output location!';
		}
		else{
			$outputmessage .='<br/><br/><b>File Successfully Copied</b> - File has been successfully copied to the output location: '.$newfile;
			$file = $newfile;
				// $fp = fopen($file, 'w');
				// fwrite($fp, $content);
				// fclose($fp);
			chmod($newfile, 0644);
		}
	}
	else{
		$outputmessage .='<br/><br/><b>File Validation Failure</b> - Your file has failed validation! Please correct the Errors before you try and validate this file again!';
	}

	$userid = $_SESSION['username'];
	databaseQueryLargeInsert("insert into uploadlog (id,userid,timestamp,filename,errormessage,batchid,validfile, qas) VALUES (uploadlog_seq.nextval,'$userid',SYSDATE,'$file',:clob,'$batchid','$validFile', :qas)", $outputmessage, $QAS);

	return $outputmessage;
}

function batchQAS($address){

	$result = QAS($address);

	return $result;
}

function checkPostcode($value){
	$value = str_replace(" ", "", $value);
	$regex = '/^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9]?[A-Za-z])))) {0,1}[0-9][A-Za-z]{2})$/';
	if (preg_match($regex, $value)) {
		return true;
	}
	else{
		return false;
	}
}

function checkPhoneNumber($value){
	$value = str_replace(" ", "", $value);
	$regex = '/[0-9]{10,15}/';
	if (preg_match($regex, $value)) {
		return true;
	}
	else{
		return false;
	}
}

function checkEmail($value){
	/*$regex = '^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$';
	if (preg_match($regex, $value)) {
		return true;
	}
	else{
		return false;
	}*/
	return true;
}
//Return true if the file exists in dir
function checkFileExists($outputlocation, $file){
	$file = $outputlocation.'/'.pathinfo($file,PATHINFO_BASENAME);
	if(file_exists($file)){
		return true;
	}
	else{
		return false;
	}
}

function checkFileName($file, $expectedName){

	if (preg_match($expectedName, $file)) {
		return true;
	}
	else{
		return false;
	}
}

function expandEntities($val) {
	$val = str_replace('&', '&amp;', $val);
	$val = str_replace("'", '&apos;', $val);
	$val = str_replace('"', '&quot;', $val);
	$val = str_replace('<', '&lt;', $val);
	$val = str_replace('>', '&gt;', $val);

	return $val;
}

function replaceComma($val) {
	$val = str_replace(',', '#', $val);

	return $val;
}

if(isset($_GET['cleanup'])){
	$entry  = $_GET['entry'];

	$sql="select * from uploadlog u where u.id=".$entry;
	$result=databaseQuery($sql);

	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$file = $row['FILENAME'];
	}

	$file = pathinfo($file,PATHINFO_BASENAME);

	$file = 'upload/tmpUpload/'.$file;

	unlink($file);

	header('Location: index.php?step=2');
}
//Check Addres against QAS
function QAS($address){

	require_once('lib/nusoap.php');
	//D12's QAS server
	//$client = new soapclient('http://10.24.182.181:2021/proweb.wsdl', true);
	$client = new nusoap_client('http://sbqasd13.bskyb.com:2021/proweb.wsdl', true);
	$err = $client->getError();
	if ($err) {
		echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
	}
	$address  = expandEntities($address);
	$params = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://www.qas.com/web-2007-09">
   <soapenv:Header/>
   <soapenv:Body>
	  <web:QASearch>
		 <web:Country>GBR</web:Country>
		 <web:Engine Flatten="true" Intensity="Exact" Threshold="750" Timeout="10000">Verification</web:Engine>
		 <!--Optional:-->
		 <web:Layout>OSPREY UNITED KINGDOM LAYOUT</web:Layout>
		 <!--Optional:-->
		 <web:Search>'.$address.'</web:Search>
	  </web:QASearch>
   </soapenv:Body>
</soapenv:Envelope>';

	$result = $client->call('DoSearch', $params);
	// Check for a fault
	if ($client->fault) {
		echo '<h2>Fault</h2><pre>';
		print_r($result);
		echo '</pre>';
	}

	if(isset($result["QAAddress"])){
		$theResult = 1;
	}
	else if(isset($result["QAPicklist"])){

		if($result["QAPicklist"]["PicklistEntry"]["Picklist"] == "No matches"){
			$theResult = 0;
		}
		else if(!isset($result["QAPicklist"]["PicklistEntry"][0])){
			$theResult = 0;
		}
		else{
			for($i=0;$i<sizeof($result["QAPicklist"]["PicklistEntry"]);$i++){
				$address = $result["QAPicklist"]["PicklistEntry"][$i]["Picklist"].','.$result["QAPicklist"]["PicklistEntry"][$i]["Postcode"];
				$address = replaceComma($address);
				$list[$i] = $address;
			}
			$theResult = $list;
		}
	}
	else{
		$theResult = 0;
	}

	return $theResult;
}

//Return result for the add
if(isset($_GET['QAS'])){
	$results = QAS($_POST['address']);

	if($results == "0"){
		echo "No Matches Found";
	}
	else if ($results == "1"){
		echo "Address Verifed";
	}
	else{
		echo json_encode($results);
	}
}

if(isset($_GET['QAS2'])){
	$results = QAS("1, td6 9es");

	if($results == "0"){
		echo "No Matches Found";
	}
	else if ($results == "1"){
		echo "Address Verifed";
	}
	else{
		echo json_encode($results);
	}
}
function emailList($filename, $userid){
	include("email-template.php");
	$message = generateEmail($filename, $userid);
	$headers = "From: DL-SBTOperations@bskyb.internal\r\n";
	$headers .= "Reply-To: DL-SBTOperations@bskyb.internal\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	mail("DL-SBTOperations@bskyb.internal", 'New Batch File Uploaded', $message, $headers);
}
//"DL-SBTOperations@bskyb.internal

if(isset($_GET['submitbatchjob'])){
	$linemanager = $_POST['linemanger'];
	$starttime = $_POST['starttime'];
	$comments = $_POST['comments'];
	$userid = $_POST['userid'];
	$status = $_POST['status'];
	$filename = $_POST['filename'];

	databaseQuery("insert into approvaltable (id,userid,runtime,filename,status,comments,linemanager) VALUES (approvaltable_seq.nextval,'$userid','$starttime','$filename','$status','$comments','$linemanager')");

	emailList($filename, $userid);

	header('Location: index.php?step=progress');
}

if(isset($_GET['approve'])){
	$id = $_GET['id'];
	$newTime = $_GET['time'];

	$sql="select * from approvaltable t where t.id=".$id;

	$result=databaseQuery($sql);
	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$oldtime = $row['RUNTIME'];
	}
	$admin = $_SESSION['username'];
	databaseQuery("UPDATE approvaltable t SET status=1, oldtime='$oldtime', appadmin='$admin', runtime='$newTime' where t.id='$id'");
	include("email-template.php");
	generateStatusUpdateEmail($id);
	header('Location: index.php?step=admin&page=approve');
}

if(isset($_GET['decline'])){
	$id = $_GET['id'];
	$admin = $_SESSION['username'];
	databaseQuery("UPDATE approvaltable t SET appadmin='$admin', status=5 where t.id='$id'");
	include("email-template.php");
	generateStatusUpdateEmail($id);
	header('Location: index.php?step=admin&page=approve');
}

if(isset($_GET['statusChange'])){
	$id = $_GET['id'];
	$status = $_GET['status'];
	$admin = $_SESSION['username'];
	databaseQuery("UPDATE approvaltable t SET appadmin='$admin', status='$status' where t.id='$id'");
	include("email-template.php");
	generateStatusUpdateEmail($id);
	header('Location: index.php?step=admin&page=statusupdate');
}

?>
