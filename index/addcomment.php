<?php
include 'database.php';
if($_GET["mode"] == 'add')
{
	$postid = $_GET["postid"];
	$author = $_GET["cmname"];
	$body = $_GET["cmbody"];
	
	$body = mysql_real_escape_string($body);
	
	$query = mysql_query("SELECT id FROM commentsbykk ORDER BY id  DESC ;", $db);
	if (!$query)
		die("Error reading query: ".mysql_error());
	
	if( $rows=mysql_fetch_row($query) )
		$id = $rows[0] + 1;
	else
		$id = 1000;
	
	$date = date("Y-m-d G:i");
	
	
	$sql = "INSERT INTO `commentsbykk` (`id`, `post_id`, `author`, `body`, `date`)
	VALUES ('$id', '$postid' , '$author', '$body', '$date');";
	
	$result = mysql_query($sql, $db);
	if($result == false )
		echo mysql_error();
}
else if($_GET["mode"] == 'addres')
{
	$postid = $_GET["postid"];
	$author = $_GET["cmname"];
	$body = $_GET["cmbody"];
	$resto = $_GET["cmresto"];
	
	$body = mysql_real_escape_string($body);
	
	$query = mysql_query("SELECT id FROM commentsbykk ORDER BY id  DESC ;", $db);
	if (!$query)
		die("Error reading query: ".mysql_error());
	
	if( $rows=mysql_fetch_row($query) )
		$id = $rows[0] + 1;
	else
		$id = 1000;
	
	$date = date("Y-m-d G:i");
	
	
	$sql = "INSERT INTO `commentsbykk` (`id`, `post_id`, `author`, `body`, `resto`, `date`)
	VALUES ('$id', '$postid' , '$author', '$body', '$resto', '$date');";
	
	$result = mysql_query($sql, $db);
	if($result == false )
		echo mysql_error();
	
	$query = mysql_query("SELECT tores FROM commentsbykk WHERE state = 1 AND id = '$resto' ORDER BY id  DESC ;", $db);
	if (!$query)
		die("Error reading query: ".mysql_error());
	if($rows=mysql_fetch_row($query))
	{
		$dappend = $rows[0];
		$dappend .= "|".$id;
		
		$sql = "UPDATE `commentsbykk` SET tores = '$dappend' WHERE id = '$resto' ; ";
		
		$result = mysql_query($sql, $db);
		if($result == false )
			echo mysql_error();
	}
	
}
?>