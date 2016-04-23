<?php

session_start();

$shapre = "";
$shapost = "";

function db_query()
{
	$sql = func_get_arg(0);
	$parameters = array_slice(func_get_args(), 1);
	static $handle;
	if (!isset($handle)) {
		try {
			$handle = new PDO("mysql:dbname=DATABASENAME;host=localhost", "USERNAME", "PASSWORD");
			$handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
		}
		catch (Exception $e) {
			return false;
			exit;
		}
	}
	$statement = $handle->prepare($sql);
	if ($statement === false) {
		return false;
		exit;
	}
	$results = $statement->execute($parameters);
	if ($results !== false) {
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	} else return false;
}

function randomString($len){
	$result = "";
	$chars = "😀|😁|😂|😃|😄|😅|😆|😇|😈|😉|😊|😋|😌|😍|😎|😏|😐|😑|😒|😓|😔|😕|😖|😗|😘|😙|😚|😛|😜|😝|😞|😟|😠|😡|😢|😣|😤|😥|😦|😧|😨|😩|😪|😫|😬|😭|😮|😯|😰|😱|😲|😳|😴|😵|😶|😷|😸|😹|😺|😻|😼|😽|😾|😿|🙀|🙅|🙆|🙇|🙈|🙉|🙊|🙋|🙌|🙍|🙎|🙏|💀|👽|💩|🚶|🏃|👫|👬|👭|💏|👆|👇|👌|👍|👎|👊|👋|👏|👂|👃|👣|💢|💨|👜|🐶|🐱|🐴|🐤|🐧|🌸|🌷|🍀|🍁|🍎|🍞|🍔|🍳|🍙|🎂|🍰|🍵|🍶|🍴|🗻|🏠|🏢|🏣|🏥|🏦|🏨|🏪|🏫|🌃|🎨|🚃|🚌|🚗|🚲|🚥|🚢|🌙|🌀|🌂|🌊|🎄|🎀|🎁|🏁|🈵|🈳|🆗|🆕|🆔|🚭|🚻|🏧|🚬|🔑|📝|💰|📖|💡|🔍|📷|📺|🎬|🎥|💿|💻|📠|📲|📱|🎧|🎤|🎶|🎵|🔔|🎿|🎾|🏀|🎫";
	$charArray = explode("|", $chars);
	for($i = 0; $i < $len; $i++){
		$result .= $charArray[mt_rand(0, count($charArray) - 1)];
	}
	return $result;
}

function autoRandomString($len) {
	db_query('DELETE FROM `links` WHERE `link` = "" AND expiry_timestamp < CURRENT TIMESTAMP - 1 day');
	while (1) {
		$string = randomString($len);
		if (empty(db_query("SELECT `short` FROM `links` WHERE `short`=(?)", hash("sha512", $shapre . $string . $shapost, false)))) {
			return $string;
		}
	}
}

function check_recaptcha()
{
	$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=   <!-- Insert your private key here -->    &response=".$_POST['cr']);
	$data = json_decode($response);
	if (isset($data->success) AND $data->success==true) {
		return true;
	} else {
		return false;
	}
}



if (empty($_POST["a"])) exit();

switch ($_POST["a"]) {
	case 'b3p1': // Beta 3 - Part 1
		if (!check_recaptcha()) exit();
		$generated = autoRandomString(6);
		$_SESSION["shortlink"] = hash("sha512", $shapre . hash("sha512", $generated, false) . $shapost, false);
		db_query('INSERT INTO `links`(`short`, `version`) VALUES (?,"b03")', $_SESSION["shortlink"]);
		echo $generated;
		break;
	case 'b3p2': // Beta 3 - Part 2
		if (empty($_POST["link"])) exit();
		if (strlen($_POST["link"]) < 0 or strlen($_POST["link"]) > 1024) exit();
		db_query("UPDATE `links` SET `link`=(?) WHERE `short`=(?)", $_POST["link"], $_SESSION["shortlink"]);
		$_SESSION["shortlink"] = "";
		exit("ok");
		break;
	case 'b3f': // Beta 3 - fetch
		$data = db_query('SELECT `link`, `clicks` FROM `links` WHERE `short`=(?) AND `version` = "b03"', $_POST["shorturl"]);
		if (empty($data)) exit("error");
		if ($data[0]["clicks"] > 1) {
			db_query('UPDATE `links` SET `clicks`=`clicks`-1 WHERE `short`=(?) AND `version` = "b03"', $_POST["shorturl"]);
		} else {
			db_query('DELETE FROM `links` WHERE `short`=(?) AND `version` = "b03"', $_POST["shorturl"]);
		}
		echo $data[0]["link"];
		break;
	default:
		exit();
		break;
}

?>