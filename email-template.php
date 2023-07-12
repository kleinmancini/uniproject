<?php
 function generateEmail($file, $userid){
	$email = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
@media only screen and (max-device-width: 480px) {
#headline p { font-size: 30px !important; }
.article-content, #left-sidebar{ -webkit-text-size-adjust: 90% !important; -ms-text-size-adjust: 90% !important; }
.header-content, .footer-content-left {-webkit-text-size-adjust: 80% !important; -ms-text-size-adjust: 80% !important;}
img { height: auto; line-height: 100%;}
 }
/* Client-specific Styles */
#outlook a { padding: 0; }	/* Force Outlook to provide a "view in browser" button. */
body { width: 100% !important; }
.ReadMsgBody { width: 100%; }
.ExternalClass { width: 100%; display:block !important; } /* Force Hotmail to display emails at full width */
/* Reset Styles */
/* Add 100px so mobile switch bar doesn"t cover street address. */
body { background-color: #c7c7c7; margin: 0; padding: 0; }
br, strong br, b br, em br, i br { line-height:100%; }
h1, h2, h3, h4, h5, h6 { line-height: 100% !important; -webkit-font-smoothing: antialiased; }
h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: blue !important; }
h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {	color: red !important; }
/* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited { color: purple !important; }
/* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
table td, table tr { border-collapse: collapse; }
.yshortcuts, .yshortcuts a, .yshortcuts a:link,.yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span {
color: black; text-decoration: none !important; border-bottom: none !important; background: none !important;
}

body, td { font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; }

#headline p { color: #eeeeee; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; font-size: 20px; text-align: center; margin-top:0px; margin-bottom:10px; }
#headline p a { color: #eeeeee; text-decoration: none; }
#subtitle p { color: #eeeeee; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; font-size: 16px; text-align: center; margin-top:0px; margin-bottom:30px; }

.article-content { font-size: 13px; line-height: 18px; color: #444444; margin-top: 0px; margin-bottom: 18px; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; }
.article-content a { color: #2f82de; font-weight:bold; text-decoration:none; }

#footer { background-color: #000000; color: #888888; }


</style>
<!--[if gte mso 9]>
<style _tmplitem="264" >
.article-content ol, .article-content ul {
   margin: 0 0 0 24px;
   padding: 0;
   list-style-position: inside;
}
</style>
<![endif]--></head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="background-table">
	<tbody><tr>
		<td align="center" bgcolor="#c7c7c7">
        	<table class="w640" style="margin:0 10px;" width="640" cellpadding="0" cellspacing="0" border="0">
            	<tbody>
                <tr>
					<td id="header" class="w640" width="640" align="center" bgcolor="#000000">
						<table class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
							<tbody><tr><td class="w30" width="30"></td><td class="w580" width="580" height="30"></td><td class="w30" width="30"></td></tr>
								<tr><td class="w30" width="30"></td>
									<td class="w580" width="580">
										<div align="center" id="headline"><p><strong><singleline label="Title">Sky Business Technology</singleline></strong><br/></p></div>
										<div align="center" id="subtitle"><p><strong><singleline label="Title">File Validator Up001451</singleline></strong></p></div>
									</td>
									<td class="w30" width="30"></td>
								</tr>
							</tbody>
						</table>
					</td>
                </tr>
                <tr id="simple-content-row"><td class="w640" width="640" bgcolor="#ffffff">
					<table class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
						<tbody><tr><td class="w30" width="30"></td>
									<td class="w580" width="580">
										<repeater>
											<layout label="Text only">
												<table class="w580" width="580" cellpadding="0" cellspacing="0" border="0">
													<tbody><tr>
														<td class="w580" width="580">
															<p align="left" class="article-title"><singleline label="Title">A File Has Been Submitted</singleline></p>
															<div align="left" class="article-content">
																<multiline label="Description">The following file has been uploaded and validated: <i>'.$file.'</i> <br/>By user '.$userid.'<br/> Please login to the validator to approve this.<br><br><a href=http://sbtapps.bskyb.com/filevalidator/>File Validator</a></multiline>
															</div>
														</td>
													</tr>
													<tr><td class="w580" width="580" height="10"></td></tr>
												</tbody></table>
											</layout>
										</repeater>
									</td>
								</tr>
						</tbody>
					</table>
				</td></tr>
                <tr>
                <td class="w640" width="640">
				<table id="footer" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000">
					<tbody>
					<tr><td class="w30" width="30"></td><td class="w580 h0" width="360" height="30"></td><td class="w0" width="60"></td><td class="w0" width="160"></td><td class="w30" width="30"></td></tr>
					</tbody>
				</table>
				</td>
				</tr>
				</tbody>
			</table>

</tbody></table></body></html>
';

	return $email;
}

function generateStatusUpdateEmail($id){

	$sql="select * from APPROVALTABLE t join allowedusers a ON t.userid=a.userid WHERE t.id=".$id;

	$result=databaseQuery($sql);
	while ($row = oci_fetch_array($result, OCI_ASSOC)) {
		$status = $row['STATUS'];
		$file = $row['FILENAME'];
		$runtime = $row['RUNTIME'];
		$admin = $row['APPADMIN'];
		$emailaddress = $row['EMAIL'];
	}

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

	$message = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><title></title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style type="text/css">
@media only screen and (max-device-width: 480px) {
#headline p { font-size: 30px !important; }
.article-content, #left-sidebar{ -webkit-text-size-adjust: 90% !important; -ms-text-size-adjust: 90% !important; }
.header-content, .footer-content-left {-webkit-text-size-adjust: 80% !important; -ms-text-size-adjust: 80% !important;}
img { height: auto; line-height: 100%;}
 }
/* Client-specific Styles */
#outlook a { padding: 0; }	/* Force Outlook to provide a "view in browser" button. */
body { width: 100% !important; }
.ReadMsgBody { width: 100%; }
.ExternalClass { width: 100%; display:block !important; } /* Force Hotmail to display emails at full width */
/* Reset Styles */
/* Add 100px so mobile switch bar doesn"t cover street address. */
body { background-color: #c7c7c7; margin: 0; padding: 0; }
br, strong br, b br, em br, i br { line-height:100%; }
h1, h2, h3, h4, h5, h6 { line-height: 100% !important; -webkit-font-smoothing: antialiased; }
h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: blue !important; }
h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {	color: red !important; }
/* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited { color: purple !important; }
/* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
table td, table tr { border-collapse: collapse; }
.yshortcuts, .yshortcuts a, .yshortcuts a:link,.yshortcuts a:visited, .yshortcuts a:hover, .yshortcuts a span {
color: black; text-decoration: none !important; border-bottom: none !important; background: none !important;
}

body, td { font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; }

#headline p { color: #eeeeee; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; font-size: 20px; text-align: center; margin-top:0px; margin-bottom:10px; }
#headline p a { color: #eeeeee; text-decoration: none; }
#subtitle p { color: #eeeeee; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; font-size: 16px; text-align: center; margin-top:0px; margin-bottom:30px; }

.article-content { font-size: 13px; line-height: 18px; color: #444444; margin-top: 0px; margin-bottom: 18px; font-family: "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif; }
.article-content a { color: #2f82de; font-weight:bold; text-decoration:none; }

#footer { background-color: #000000; color: #888888; }


</style>
<!--[if gte mso 9]>
<style _tmplitem="264" >
.article-content ol, .article-content ul {
   margin: 0 0 0 24px;
   padding: 0;
   list-style-position: inside;
}
</style>
<![endif]--></head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="0" id="background-table">
	<tbody><tr>
		<td align="center" bgcolor="#c7c7c7">
        	<table class="w640" style="margin:0 10px;" width="640" cellpadding="0" cellspacing="0" border="0">
            	<tbody>
                <tr>
					<td id="header" class="w640" width="640" align="center" bgcolor="#000000">
						<table class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
							<tbody><tr><td class="w30" width="30"></td><td class="w580" width="580" height="30"></td><td class="w30" width="30"></td></tr>
								<tr><td class="w30" width="30"></td>
									<td class="w580" width="580">
										<div align="center" id="headline"><p><strong><singleline label="Title">Sky Business Technology UP001451</singleline></strong><br/></p></div>
										<div align="center" id="subtitle"><p><strong><singleline label="Title">File Validator</singleline></strong></p></div>
									</td>
									<td class="w30" width="30"></td>
								</tr>
							</tbody>
						</table>
					</td>
                </tr>
                <tr id="simple-content-row"><td class="w640" width="640" bgcolor="#ffffff">
					<table class="w640" width="640" cellpadding="0" cellspacing="0" border="0">
						<tbody><tr><td class="w30" width="30"></td>
									<td class="w580" width="580">
										<repeater>
											<layout label="Text only">
												<table class="w580" width="580" cellpadding="0" cellspacing="0" border="0">
													<tbody><tr>
														<td class="w580" width="580">
															<p align="left" class="article-title"><singleline label="Title">Status Change!</singleline></p>
															<div align="left" class="article-content">
																<multiline label="Description">The Status of the File that you uploaded: <i>'.$file.'</i> <br/> Has been changed to: '.$status.'<br/>To be ran: '.$runtime.'</multiline>
															</div>
														</td>
													</tr>
													<tr><td class="w580" width="580" height="10"></td></tr>
												</tbody></table>
											</layout>
										</repeater>
									</td>
								</tr>
						</tbody>
					</table>
				</td></tr>
                <tr>
                <td class="w640" width="640">
				<table id="footer" class="w640" width="640" cellpadding="0" cellspacing="0" border="0" bgcolor="#000000">
					<tbody>
					<tr><td class="w30" width="30"></td><td class="w580 h0" width="360" height="30"></td><td class="w0" width="60"></td><td class="w0" width="160"></td><td class="w30" width="30"></td></tr>
					</tbody>
				</table>
				</td>
				</tr>
				</tbody>
			</table>

</tbody></table></body></html>
';

	$headers = "From: SBTApps@sky.uk\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	mail($emailaddress, 'File Status Change', $message, $headers);
}

?>
