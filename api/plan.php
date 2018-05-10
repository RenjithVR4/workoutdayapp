<?php
/*********************************************************
   Author:      Renjith VR
   Version:     1.0
   Date:        05-May-2018
   FileName:    plan.php
   Description : Actions for plans
**********************************************************/
header("Content-Type:application/json");
error_reporting(E_ALL);

require_once("../settings/helpers.php");
require_once("../settings/plansettings.php");
require_once("../settings/usersettings.php");

function rest_put($request)
{
	$parts = parse_url($request);
	$path_parts = pathinfo($parts['path']);
	$id = $path_parts['filename'];
	$result =  array();

	if(empty(trim($id)))
	{
		header('HTTP/1.1' .'  '. 400 .'  ' .  'Bad Request');
		exit("Missing Parameters");
	}

	$put_vars = json_decode(file_get_contents('php://input'), true);

	error_log(json_encode($put_vars));

	$result = updateEducation($id, $put_vars);

	if(isset($result['error']))
	{
		header('HTTP/1.1' .'  '. 400 .'  ' .  'Bad Request');
        	exit("Error while updating user");
	}

	print json_encode($result);
}

function rest_post($request)
{
	$parts = parse_url($request);
	$path_parts = pathinfo($parts['path']);
	$id = $path_parts['filename'];

	error_log("Plan =>".json_encode($_POST));

	if((count($_POST['userids']) == 0) || (count($_POST['daynames']) == 0) )
	{
		header('HTTP/1.1' .'  '. 400 .'  ' .  'Bad Request');
		exit("Missing Parameters");
	}


	if(count($_POST['excerciseset']) == 0 )
	{
		header('HTTP/1.1' .'  '. 400 .'  ' .  'Bad Request');
		exit("Missing Parameters");
	}


	$result = createPlan($_POST);

	print json_encode($result);
}

function rest_get($request)
{
	$parts = parse_url($request);
	$path_parts = pathinfo($parts['path']);
	$id = $path_parts['filename'];

	error_log(json_encode($path_parts));

	$page = 1;
	$user = "";

	if(!empty($_GET['page']))
	{
		$page = trim($_GET['page']);
	}

	if($id === "listing")
	{
		$result = listPlan($page);
	}
	else
	{
		$result = getPlan($id);
	}

	if(isset($result['error']))
	{
		error_log("Error:".json_encode($result));
		return false;
	}

	print json_encode($result);
}

function rest_delete($request)
{
	$path_parts = pathinfo($request);
	$id = $path_parts['filename'];

	$put_vars = json_decode(file_get_contents("php://input"),true);

	error_log($put_vars);

	if(empty(trim($id)))
	{
		header('HTTP/1.1' .'  '. 400 .'  ' .  'Bad Request');
		exit("Missing Parameters");
	}

	$result = deleteUser(trim($id));

	if(isset($result['error']))
	{
		header('HTTP/1.1' .'  '. 400 .'  ' .  'Bad Request');
        	exit("Error while deleting user");
	}

	print json_encode($result);
}

function rest_head($request)
{
	header('HTTP/1.1' .'  '. 405 .'  ' .  'Bad Request');
    	exit("This method is not allowed");
}

function rest_options($request)
{
	header('HTTP/1.1' .'  '. 405 .'  ' .  'Bad Request');
    	exit("This method is not allowed");
}

function rest_error($request)
{
	header('HTTP/1.1' .'  '. 405 .'  ' .  'Bad Request');
    	exit("This method is not allowed");
}

//First check what is the method
if(!isset($_SERVER['REQUEST_METHOD']) || !isset($_SERVER['REQUEST_URI']))
{
    header('HTTP/1.1' .'  '. 400 .'  ' .  'Bad Request');
    exit("HTTP Method or request URI is not set");
}

$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

switch($method)
{
	case 'POST':
	rest_post($request);
	break;

	case 'GET':
	rest_get($request);
	break;

	case 'PUT':
	rest_put($request);
	break;

	case 'DELETE':
	rest_delete($request);
	break;

	case 'HEAD':
	rest_head($request);
	break;

	case 'OPTIONS':
	rest_options($request);
	break;

	default:
	rest_error($request);
	break;
}

exit(0);

?>