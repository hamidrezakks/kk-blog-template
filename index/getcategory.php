<?php
include 'database.php';
?>
<option value="all"  >All</option>
<?php 
$query = mysql_query("SELECT category FROM blogsbykk WHERE state = 1  ORDER BY category ASC ; ", $db);
if (!$query)
	die("Error reading query: ".mysql_error());
	
$category = "";
while($row=mysql_fetch_row($query))
	{
	if($category != $row[0] )
		{
			echo "
			<option value='".$row[0]."' >".getCategory($row[0])."</option>
			";
			$category = $row[0];
		}
	}
?>