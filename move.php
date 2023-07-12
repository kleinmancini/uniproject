<?php
#UNCOMMENT WHAT YOU NEED

$filename = 'website_user_migration_PRD_00001_20210728.csv';
$path = '/var/www/sbt_websites/FileValidator/upload/validated/';
$batchJob = 'SBBMCR470' . '/';
//
//
// #Move out of validated folder
// echo rename($path.$batchJob.$filename, $path.$filename) ? 'Successfully moved file ' . $filename . '<br>To: ' . $path.$filename : 'Failed to move file ' . $filename . '<br>See PHP Error log (/var/log/httpd/error_log)';
//
// #Move in to validated folder
// //echo rename($path.$filename, $path.$batchJob.$filename) ? 'Successfully moved file ' . $filename . '<br>To: ' . $path.$batchJob.$filename : 'Failed to move file ' . $filename . '<br>See PHP Error log (/var/log/httpd/error_log)';
//
//
#Change Permissions
$permissions = 0777;
echo chmod($path.$batchJob.$filename, $permissions) ? 'Successfully set permissions on: ' . $filename : 'Failed to set permissions on file ' .$filename . '<br>See PHP Error log (/var/log/httpd/error_log)';

 ?>
