<?php
include 'database.php';
function getResponse ($id,$resto,$db)
{
	$query = mysql_query("SELECT * FROM commentsbykk WHERE state = 1 AND post_id = '$id' AND id = '$resto' ORDER BY id  DESC ;", $db);
	if (!$query)
		die("Error reading query: ".mysql_error());
	
	while($rows=mysql_fetch_row($query))
	{
		$xml .= "<comment>";
		$xml .= "<id>".$rows[0]."</id>";
		$xml .= "<author>".$rows[2]."</author>";
		$xml .= "<date>".$rows[7]."</date>";
		$xml .= "<body><![CDATA[".$rows[3]."]]></body>";
		if($rows[5] != '0')
		{
			$xml .= "<responses>";
			$temp = explode("|", $rows[5]);
			foreach ($temp as $resto)
			{
				if(strlen($resto) > 1)
					$xml .= getResponse($id,$resto,$db);
			}
			$xml .= "</responses>";
			//do sth
		}
		$xml .= "</comment>";
	}
	return $xml;
}
$xmlcontent = '';
$currentPath = "http://localhost/Zend/workspaces/DefaultWorkspace10/hw4/";
if($_GET["mode"] == 'blogs')
{
	if(isset($_GET["tag"]) && $_GET["tag"] != 'all')
	{
		$tag = $_GET["tag"];
		$query = mysql_query("SELECT * FROM blogsbykk WHERE state = 1 AND category = '$tag' ORDER BY id  DESC ;", $db);
		if (!$query)
			die("Error reading query: ".mysql_error());
	}
	else 
	{
		$query = mysql_query("SELECT * FROM blogsbykk WHERE state = 1 ORDER BY id  DESC ;", $db);
		if (!$query)
			die("Error reading query: ".mysql_error());
	}
	$xmlcontent = "<blogs>";
	while($rows=mysql_fetch_row($query))
	{
		$xmlcontent .= "<blog>";
		$xmlcontent .= "<id>".$rows[0]."</id>";
		$xmlcontent .= "<authorid>".$rows[1]."</authorid>";
		$xmlcontent .= "<url>".htmlentities($currentPath."index/getxml.php?mode=blog&id=".$rows[0], ENT_QUOTES)."</url>";
		$xmlcontent .= "</blog>";
	}
	$xmlcontent .= "</blogs>";
}
else if($_GET["mode"] == 'blog')
{
	$id = $_GET["id"];
	$query = mysql_query("SELECT * FROM blogsbykk WHERE state = 1 AND id = '$id' ORDER BY id  DESC ;", $db);
	if (!$query)
		die("Error reading query: ".mysql_error());
	
	$xmlcontent = "<post>";
	while($rows=mysql_fetch_row($query))
	{
		$xmlcontent .= "<id>".$rows[0]."</id>";
		$xmlcontent .= "<name><![CDATA[".$rows[3]."]]></name>";
		$mquery = mysql_query("SELECT name FROM registerbykk WHERE state = 1 AND id = '$rows[1]' ORDER BY id  DESC ;", $db);
		if (!$mquery)
			die("Error reading query: ".mysql_error());
		if($row=mysql_fetch_row($mquery))
		{
			$xmlcontent .= "<author>".$row[0]."</author>";
		}
		$xmlcontent .= "<description><![CDATA[".$rows[4]."]]></description>";
		$xmlcontent .= "<tags><tag>".$rows[7]."</tag></tags>";
		$xmlcontent .= "<headerpic>".htmlentities($rows[6], ENT_QUOTES)."</headerpic>";
	}
	
	$query = mysql_query("SELECT * FROM postsbykk WHERE state = 1 AND blogid = '$id' ORDER BY id  DESC ;", $db);
	if (!$query)
		die("Error reading query: ".mysql_error());
	$xmlcontent .= "<posts>";
	while($rows=mysql_fetch_row($query))
	{
		$xmlcontent .= "<post>";
		$xmlcontent .= "<id>".$rows[0]."</id>";
		$xmlcontent .= "<date>".$rows[6]." ".$rows[7]."</date>";
		$xmlcontent .= "<url>".htmlentities($currentPath."index/getxml.php?mode=post&id=".$rows[0], ENT_QUOTES)."</url>";
		$xmlcontent .= "</post>";
	}
	$xmlcontent .= "</posts>";
	$xmlcontent .= "</post>";
}
else if($_GET["mode"] == 'post')
{
	$id = $_GET["id"];
	$query = mysql_query("SELECT * FROM postsbykk WHERE state = 1 AND id = '$id' ORDER BY id  DESC ;", $db);
	if (!$query)
		die("Error reading query: ".mysql_error());
	
	$xmlcontent = "<post>";
	while($rows=mysql_fetch_row($query))
	{
		$xmlcontent .= "<id>".$rows[0]."</id>";
		$xmlcontent .= "<ppass>".$rows[9]."</ppass>";
		$xmlcontent .= "<title><![CDATA[".$rows[2]."]]></title>";
		$xmlcontent .= "<date>".$rows[6]." ".$rows[7]."</date>";
		$xmlcontent .= "<body><![CDATA[";
		$f = fopen("../".$rows[3], "r");
		if($f===false)
			die("'".$rows[3]."' doesn't exist.");
		else
		while(!feof($f))
		{
			$buf = fgets($f , 4096);
			$buf = htmlspecialchars_decode($buf, ENT_QUOTES);
			$xmlcontent .= $buf;
		}
		fclose($f);
		$xmlcontent .= "]]></body>";
	}
	$xmlcontent .= "<comments>";
	$query = mysql_query("SELECT * FROM commentsbykk WHERE state = 1 AND post_id = '$id' AND resto = 0 ORDER BY id  DESC ;", $db);
	if (!$query)
		die("Error reading query: ".mysql_error());
	
	while($rows=mysql_fetch_row($query))
	{
		$xmlcontent .= "<comment>";
		$xmlcontent .= "<id>".$rows[0]."</id>";
		$xmlcontent .= "<author>".$rows[2]."</author>";
		$xmlcontent .= "<date>".$rows[7]."</date>";
		$xmlcontent .= "<body><![CDATA[".$rows[3]."]]></body>";
		if($rows[5] != '0')
		{
			$xmlcontent .= "<responses>";
			$temp = explode("|", $rows[5]);
			foreach ($temp as $resto)
			{
				if(strlen($resto) > 1)
					$xmlcontent .= getResponse($id,$resto,$db);
			}
			$xmlcontent .= "</responses>";
		}
		$xmlcontent .= "</comment>";
	}
	
	$xmlcontent .= "</comments>";
	$xmlcontent .= "</post>";
}
echo $xmlcontent;
header("Content-type: text/xml");
?>