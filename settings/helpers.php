<?php
error_reporting(E_ALL);

define('ADMIN_SESSION_ID', 'WORKOUTPLANAPPADMINid');


function DBConnection()
{
	// $mysqlcon = new mysqli('localhost', 'medisoft_imeds', 'imeds123','medisoft_imeds');
	$mysqlcon = new mysqli('localhost', 'root', 'root','workoutplans');

	if ($mysqlcon->connect_errno)
	{
		 error_log("Failed to connect to MySQL: (" . $mysqlcon->connect_errno . ") " . $mysqlcon->connect_error);
		 return false;
	}

	$mysqlcon->query("SET GLOBAL sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");

	$mysqlcon->query("SET SESSION sql_mode='STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");

	return $mysqlcon;
}


function generateHash($password)
{
          if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH)
          {
                    $salt = '$2y$11$' . substr(md5('WORKOUTPLANAPP'), 0, 22);
                    return crypt($password, $salt);
          }
}

function sessionValidate($roleid,$id=NULL)
{
	session_start();

	if(!isset($_SESSION[$roleid]))
	{
		return false;
	}

	if(!empty($id) && $_SESSION[$roleid] !== $id)
	{
		return false;
	}

	$id = $_SESSION[$roleid];

	if((time() - $_SESSION['LOGIN_TIME']) >= 7200)
	{
		error_log('Session Expired: '.$id.' from '.$_SERVER['REMOTE_ADDR']);
		session_destroy();
		return false;
	}


	return $id;
}


function mysql_fix_string($string,$mysqlcon)
{
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	return $mysqlcon->real_escape_string(trim($string));
}

function getDBConnectorPhrase($query)
{
  	if(!stristr($query,'WHERE'))
	{
		return " WHERE ";
	}
	else
	{
		return " AND ";
	}
}

function getDBORConnectorPhrase($query)
{
  	if(!stristr($query,'WHERE'))
	{
		return " WHERE ";
	}
	else
	{
		return " OR ";
	}
}

function uploadFilePOST($dest,$filename)
{

	// print_r($_FILES);
	error_log(json_encode($_FILES));
	if (!move_uploaded_file($_FILES[$filename]['tmp_name'], $dest))
	{
		error_log('Could not upload '.$filename);
		return false;
	}

	return true;

}

function uploadFilePUT($dest)
{

	/* PUT data comes in on the stdin stream */
	if(!($putdata = fopen("php://input", "r")))
	{
		error_log('Could not read Input data');
	}

	/* Open a file for writing */
	if(!($fp = fopen($dest, "w")))
	{
		error_log('Could not open output file: '.$dest);
	}

	/* Read the data 1 KB at a time  and write to the file */
	while ($data = fread($putdata, 1024))
  		fwrite($fp, $data);

	/* Close the streams */
	fclose($fp);
	fclose($putdata);

	return true;
}

function checkValidEmail($email)
{
	if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
	     return true;
	} else {
	     return false;
	}
}

function sendEmail($emaildata)
{

	date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d H:i:s');

    $ip = $_SERVER['REMOTE_ADDR'];
	$location_details = file_get_contents('http://freegeoip.net/json/'.$ip);
	
	$location_details = json_decode($location_details, true);

	$to      = trim($emaildata['email']);
	$name  =    trim($emaildata['name']);
	$subject =  trim($emaildata['subject']);
	
	$headers = 'From:'. $name . '<'. trim($emaildata['email']). '>' . "\r\n" .
	    'Reply-To: info@workoutapp.com' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


	$message = trim($emaildata['message']) . "<br><br>";
	$message .= "<br><br><hr>";
	$message .= '<b>User Details</b>' . "<br>";
	$message .= '<i>Country<i>: '. "\t" . $location_details['country_name']. "<br>";
	$message .= '<i>Region<i>: '. "\t" . $location_details['region_name']. "<br>";
	$message .= '<i>City<i>: '. "\t" . $location_details['city']. "<br>";
	$message .= '<i>IP<i>: '. "\t" . $location_details['ip']. "<br>";
 
	if(mail($to, $subject, $message, $headers))
	{
		 $result = array("success" => "Email sent successfully");

	}
	else
	{
		 $result = array("error" => "Email not sent");
	}

	 return $result;

	
}





 ?>
