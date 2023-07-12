<?php



require("/var/www/sbt_websites/DBConnections/DBConnections.php");

$con = oci_pconnect($FILEVUSER,$FILEVPASS,$FILEVDB);
if (!$con) {
	echo '<script>alert("Error: Connection to Database Failed");</script>';
}

function databaseQuery($sql){

	global $con;

	$s = oci_parse($con, $sql);
	$r = oci_execute($s);

	if (!$r) {
		$e = oci_error($s);  // For oci_execute errors pass the statement handle
		print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%".($e['offset']+1)."s", "^");
		print  "\n</pre>\n";
		break;
	}

	oci_commit($con);

	return $s;
}

function databaseCount($result){
	$count = 0;
	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$count++;
	}
	oci_execute($result);
	return $count;
}

function resetPrimaryKey($sequence){
	$sql="drop sequence ".$sequence."";
	databaseQuery($sql);
	$sql="create sequence ".$sequence." start with 1 increment by 1 nomaxvalue cache 1000";
	databaseQuery($sql);
}

function databaseQueryLargeInsert($sql, $clob, $qas){

	global $con;

	$s = oci_parse($con, $sql);
	oci_bind_by_name($s, ":clob", $clob);
	oci_bind_by_name($s, ":qas", $qas);

	$r = oci_execute($s);

	if (!$r) {
		$e = oci_error($s);  // For oci_execute errors pass the statement handle
		print htmlentities($e['message']);
		print "\n<pre>\n";
		print htmlentities($e['sqltext']);
		printf("\n%".($e['offset']+1)."s", "^");
		print  "\n</pre>\n";
		break;
	}

	oci_commit($con);

	return $s;
}
?>
