<?php
include '/var/www/sbt_websites/PHPMailer/PHPMailerAutoload.php';
//CHANGE PATH BELOW TO CORRECT DATA IN FOLDER
  foreach (scandir('/apps/prd/home/ftpsbprd/datain/') as $file) {
  //foreach (scandir('/var/www/sbt_websites/FileValidator/datain/') as $file) {
    if(substr($file, 0, 15) == 'escalated_note_' && substr($file, -9) == 'error.csv'){ //CHANGE TO ERROR.CSV
      require_once '/var/www/sbt_websites/DBConnections/DBConnections_new.php';
      $con = oci_new_connect($FILEVUSER,$FILEVPASS,$FILEVDB);
      $sql = "SELECT u.email FROM filevalidator.uploadlog ul, filevalidator.allowedusers u
              WHERE ul.filename LIKE '%".substr($file, 0, -10)."%'
              AND ul.validfile = 1
              AND ul.userid = u.userid";
      $parse = oci_parse($con, $sql);
      oci_execute($parse);
      oci_execute(oci_parse($con, $sql));
      while($row = oci_fetch_array($parse)){
        $EMAIL = $row["EMAIL"];

        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
          $message = 'SBBMCR480 - Bulk Escalted Note Upload has failed to process the input file. <br><br>Please see the attached error file: ' . $file;
          $mail->setFrom('SBTApps@sky.uk');
          $mail->addAddress($EMAIL);
          //CHANGE TO CORRECT PATH
          $mail->addAttachment('/apps/prd/home/ftpsbprd/datain/'.$file);         // Add attachments
          //$mail->addAttachment('/var/www/sbt_websites/FileValidator/datain/'.$file);         // Add attachments
          $mail->isHTML(true);                                  // Set email format to HTML
          $mail->Subject = 'ERROR: SBBMCR480 - Bulk Escalted Note Upload';
          $mail->Body    = $message;
          $mail->send();
          echo 'Message has been sent';
          rename('/var/www/sbt_websites/FileValidator/datain/'.$file, '/var/www/sbt_websites/FileValidator/'.$file);
        }
        catch (Exception $e) {
          echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
      }
    }
  }
?>
