<?php

//default checks.
//string exactly $num length
//2.79 changes by cne04
//14/06/2016


/*****************************************************************************************
** validation.php
** ---------------
** Carries out the primary validation functions for each file
**
** Version		Who				Date			Comment
** 0.0			SSavage			02/10/2020		Starting Change Log
** 0.1			SSavage			02/10/2020		Initial version and all changes up to date - original code written in 2016
**													and updated by various members of Ops Support team since then
** 0.2			SSavage			02/10/2020		SBTD-6000: Radley Contingency - Bulk Credit - Changes to app to allow files to include a footer -
**													which at present will simply be ignored from dilapidation
**
**
** 0.n			TBC				DD/MM/YYYY		Description
**
*****************************************************************************************/


function checkHeader($headerRow,$headerRecord)
{
	if(strpos($headerRecord,'|') ==false){
		$headerRecord = explode(',',$headerRecord);
		$error.='comma';
	}
	else{
		$headerRecord = explode('|',$headerRecord);
		$error.='pipe';
	}

		$num = count($headerRecord);
		$hrow='';

		//$hrow = implode("",$headerRecord);
		for($i=0;$i<$num;$i++)
		{
			//echo $headerRecord[$i]."test<br>";
			$hrow.=(string)trim($headerRecord[$i]);
		}

	//	$headerRow = explode(',',$headerRow);

		$num = count($headerRow);
		$trow = '';

		for($i = 0;$i<$num;$i++){
			$colString = PHPExcel_Cell::stringFromColumnIndex($i);
			$trow .=(string)PHPExcel_Shared_String::SanitizeUTF8($headerRow[$colString]);
			//$trow.=$headerRow[$i];
		}

		$headerRow = (string)$trow;
		$headerRecord =(string)$hrow;

		if($headerRow != $headerRecord){
			return false;
		}

		return true;


}

function validate($validCode,$value,$col,$bool,$error,$nextValue) {
	global $array;
	global $bimandatory;
	global $bimfields;
	$checkfield = "";
	$num;
	$error ="";
	if(strpos($validCode,"[")!=false){

		$checkfield = substr($validCode,0,-1);
		$var = explode("[",$checkfield);
		$checkfield = $var[0];
		$num = $var[1];
	}else{
		$checkfield = $validCode;
	}

	if(strpos($validCode,"(")>0){
		$checkfield = substr($validCode,0,-1);
		$var = explode("(",$checkfield);
		$checkfield = $var[0];
		$elem = $var[1];

		$bimfields .= $col.', ';
		$bimandatory=true;
		if(!isset($array[$elem])&&$value!='' && isset($value) && count($array)<1){
			$array[$elem] = $value;
		}else if(isset($array[$elem])&&$value!='' && isset($value) && count($array)>=1){
			if(in_array($value,$array)){
				$error.= "Duplicate field value";
				return false;
			}
		}

	}else{
		if(!isset($checkfield)){
			$checkfield = $validCode;
		}
	}

	switch ($checkfield)
	{

		case "ST": //string
				if(isset($num)){

					$validFile = checkString($value,$num,$error);
				}else{
					$validFile = checkString2($value,$error);
				}
			break;
		case "C": //character
				$validFile = checkString($value,1,$error);
			break;
		case "B": //Boolean
				$validFile = checkBool($value,$error);
			break;
		case "NUM": //number
			if(isset($num)){

				$validFile = checkNumber2($value,$num,$error);
			}else{
				$validFile = checkNumber($value,$error);
			}
			break;
		case "PNUM": //Property number
				$validFile = checkNumber($value,$error);
			break;
		case "PN": //Property Name
				$validFile = checkString2($value,$error);
			break;
		case "SN": //Street Name
				$validFile = checkString2($value,$error);
			break;
		case "TW"://town
				$validFile = checkString2($value,$error);
			break;
		case "CNT": //County
				$validFile = checkString2($value,$error);
			break;
		case "PC": //post code
				$validFile = checkPostcode2($value,$error);
			break;
		case "TEL": //telephone number
				$validFile = checkPhone($value,$error);
			break;
		case "E": //email
				$validFile = checkEmail2($value,$error);
			break;
		case "SAN": //subaccount number
				$validFile = checkSubAcc($value,$error);
			break;
		case "VC": //viewingcard
				$validFile = checkViewingCard($value,$error);
			break;
		case "ID": //osprey row id
				$validFile = checkRowID($value,$error);
			break;
		case "CUR": //currency
				$validFile = checkCurrency($value,$error);
			break;
		case "PROD": //product
				$validFile = checkProduct($value,$error);
			break;
		case "YN"://yes no
				$validFile = checkYesNo($value,$error);
				break;
		case "YNN": //Yes or No or Null
				$validFile = checkYNN($value,$error);
				break;
		case "YNYN"://y or n
				$validFile = checkYN($value,$error);
				break;
		case "EN": //escalated note
				$validFile = escaltedNote($value,$error,$nextValue);
				break;
		case "A": //action to be taken
				$validFile = actionToTake($value,$error);
				break;
		case "INVT": //invite type
				$validFile = inviteType($value,$error);
				break;
		default:
			$validFile = false;
	}

	if($bool && $bimandatory && count($array)<1){
		$error .= "Require a value in columns ".$bimfields;
		$validFile = false;
	}

	$myfile = fopen("log.txt", "a");
	fwrite($myfile, 'Line 174: ' .$error."\n");
	$return = array($validFile, $error);
	return $return;

}

function escaltedNote($value,&$error,$nextValue){
	if(($nextValue !== '0' && (is_null($value) || $value == ''))){
		$error.="Escalated note in standard ASCII format is required when  the Action is ".$nextValue;
		return false;
	}
	else if(!is_null($value) && $value !== ''){
		if(preg_match('/[\x00-\x1F\x7F-\xFF]/', $value)){
			//3-127 ASCII
			$error.="Escalated note must be standard ASCII characters only";
			return false;
		}
		else if($value[0] !== '"' || $value[strlen($value)-1] !== '"'){
			$error.= "Escalated note must be wrapped in double quotes";
			return false;
		}
		else if(strlen($value) > 600){
			$error.="Escalted note must be less than or equal to 600 characters";
			return false;
		}
	}
	return true;
}

function actionToTake($value, &$error){
	if($value !== '0' && $value !== '1' && $value !== '2'){
		$error.="Action must be equal to 0, 1 or 2";
		return false;
	}
	return true;
}

function inviteType($value, &$error){
	if($value !== 'NEW' && $value !== 'MIGRATION-ACTIVE' && $value !== 'MIGRATION-INACTIVE' && $value !== 'GENERATE-TOKEN'){
		$error.="Invite Type must be: NEW, MIGRATION-ACTIVE, MIGRATION-INACTIVE or GENERATE-TOKEN";
		return false;
	}
	return true;
}

function checkPhone($value,&$error){


	if(!is_null($value) && $value !=null && $value !=""){

		if(preg_match('/\s/',$value)){
			$error.="Spaces in phone numbers is not allowed, please resolve";
			return false;
		}

		$value = str_replace(" ", "", $value);
		$regex = '/[0-9]{10,15}/';


		if (preg_match($regex, $value)) {
			return true;
		}
		else{
			$error .="invalid phone number format";
			return false;
		}
	}
	else{
		return true;
	}
}

function checkEmail2($value){
	/*$regex = '^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$';
	if (preg_match($regex, $value)) {
		return true;
	}
	else{
		return false;
	}*/
	return true;
}

function checkCurrency($value,&$error){

	if(isset($value) && $value!=''){
		$length = strlen(substr(strrchr($value, "."), 1));


		if($length <2 or $length >2){
			$error.= "Error with formatting of Currency";
			return false;
		}

		if(!is_numeric($value)){
			$error.= "Error: Not a valid format. Please remove any £/$";
			return false;
		}
	}
	return true;


}
function checkString($checkField,$num,&$error){

	$length = strlen(utf8_decode($checkField));

	if(empty($checkField)){
		$error.= " Failed validation: empty field";
		return false;
	}
	if($checkField == ""){
		$error.= " Failed validation: empty field";
		return false;
	}
	if(!is_string($checkField)){
		$error.= " Failed validation: is not a string";
		return false;
	}

	if($length != $num)
	{
		$error.= " Failed validation: does not match length expected";
		return false;
	}
	$aValid = array('_','-',' ','(',')','|','/','&','+','\'');
	if(!ctype_alnum(str_replace($aValid,'',$checkField))){
		$error.=$checkField." String is not alphanumeric. Please review ".$checkField;
		return false;
	}
	return true;
}

function checkProduct($checkField,&$error){

	if(!checkString2($checkField,$error)){
		$error.= " Failed Product validation";
		return false;
	}
	if(strlen($checkField)>0 && strlen($checkField)<2){
		$error.= " If Product value is provided, must be more than 1 character";
		return false;
	}

	if(substr(strtoupper($checkField),-2)!='_O' && isset($checkField) && $checkField !=""){
		$error.= " Invalid Product/Offer Code, should end in '_O'. Ends in ".strtoupper(substr($checkField,-2));
		return false;
	}

	return true;
}

//string
function checkString2($checkField,&$error){
	if(!is_string($checkField)){
		$error.= " Failed validation: is not a string";
		return false;
	}

	$aValid = array('_','-',' ','(',')','|','/','&','+','\'');   //list of valid characters that aren't alphanumeric
	if(!ctype_alnum(str_replace($aValid,'',$checkField))&&$checkField!=""){
		$error.=" String is not alphanumeric. Please review ".$checkField;
		return false;
	}
/*
	$val = iconv('UTF-8','ASCII//TRANSLIT',str_replace($aValid,'',$checkField));
	if(is_null($val)||$val ==""){
		$error.=" String contains invalid characters tt".iconv('UTF-8','ASCII//TRANSLIT',$checkField)." ".$checkField;
		return false;
	}*/



	return true;
}

//boolean
function checkBool($checkField,&$error){

	if(!is_bool($checkField)){
		 $error.= "Failed validation: is not a True/False value";
		 return false;
	}
	return true;
}
//yes no value
function checkYesNo($checkField,&$error){

	if(strtoupper($checkField)!='YES' && strtoupper($checkField)!='NO' && isset($checkField) && $checkField!=''){
		$error.="Failed Validation: is not a Yes/No value";
		return false;
	}
	return true;
}
//yes no or null value
function checkYNN($checkField,&$error){
	if(strtoupper($checkField)!='YES' && strtoupper($checkField)!='NO' && $checkField !== ''){
		$error.="Failed Validation: value must be Yes No or Null";
		return false;
	}
	return true;
}

//y n value
function checkYN($checkField,&$error){

	if(strtoupper($checkField)!='Y' && strtoupper($checkField)!='N' && isset($checkField) && $checkField!=''){
		$error.="Failed Validation: is not a Y/N value";
		return false;
	}
	return true;
}

//number
function checkNumber($checkField,&$error){
	if(!is_numeric($checkField)&&isset($checkField)&&$checkField!=""){
		$error.= " Failed validation: field is not a valid number";
		return false;
	}
	return true;
}

//number2
function checkNumber2($checkField,$num,&$error){
	$length = strlen((String)$checkField);

	if(!is_numeric($checkField)&&isset($checkField)&&$checkField!=""){
		$error.= " Failed validation: field is not a valid number";
		return false;
	}

	if($length > $num)
	{
		$error.= " Failed validation: does not match length expected";
		return false;
	}
	return true;
}
//regex checks against postcode and email
function checkPostcode2($checkField,&$error){
	$value = str_replace(" ", "", $value);
	$regex = '/^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9]?[A-Za-z])))) {0,1}[0-9][A-Za-z]{2})$/';
	if (preg_match($regex, $value)) {
		return true;
	}
	else{
		$error.= "Failed validation: invalid postcode";
		return false;
	}
}

//system checks, do not check they exist purely format
function checkSubAcc($checkField,&$error){

	if(substr($checkField,0,1)==0){
		$error.= "Sub account number can't start with zero";
		return false;
	}


	if(!checkNumber($checkField) || !checkString((string)$checkField,12)){

		$error.= "Not a valid sub account number";
		return false;
	}

	return true;

}

function checkViewingCard($checkField,&$error){

	if(checkNumber($checkField)){

		if(!checkString((string)$checkField,12)){

			$error.= "Failed validation: Not a valid viewing card number length";
			return false;
		}
	}else{

		$error.= "Failed validation: Not a valid viewing card number not a number";
		return false;
	}

	return true;
}

function checkRowID($checkField,&$error){

	return true;

}

//if date = today return 1, past = 0 and future = 2
function checkDate2($checkField,&$error){

	return 1;

}

?>
