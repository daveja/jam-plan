<?php

include_once 'mailchimp.php';
 
$name = filter_var($_POST['fname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
$lastname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
 
$mc = new \Drewm\MailChimp('[API KEY GOES HERE]');
$mvars = array('optin_ip'=> $_SERVER['REMOTE_ADDR'], 'FNAME' => $name, 'LNAME' => $lastname, 'MMERGE3' => 'VALUE_FOR_OPT_MERGEFIELD');
$result = $mc->call('lists/subscribe', array(
		'id'                => '[ID GOES HERE]',
		'email'             => array('email'=>$email),
		'merge_vars'        => $mvars,
		'double_optin'      => false,
		'update_existing'   => false,
		'replace_interests' => false,
		'send_welcome'      => false
	)
);

if (!empty($result['euid'])) {
	header('Location: ../subscribed.html');
} else {
	if (isset($result['status'])) {
		switch ($result['code']) {
			case 214:
			header('Location: ../thank-you.html');

			break;
			// check the MailChimp API if you like to add more options
			default:
			header('Location: ../404.html');

			break;
		}
	}
}

?>
