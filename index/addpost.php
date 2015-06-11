<?php
include 'database.php';
$blogId = $_GET["blogid"];
if($isLoggedIn)
{
	if($_GET["mode"] == 'add')
	{
		$ajaxDir = "../";
			
		$name = $_POST["post_name"];
		
		$ppass = $_POST["post_pass"];
	
		$post_index = $_POST["post_index"];
		if (get_magic_quotes_gpc()) $post_index = stripslashes($post_index);
		$post_index = htmlspecialchars($post_index, ENT_QUOTES,"UTF-8");
			
		$query = mysql_query("SELECT id FROM postsbykk ORDER BY id  DESC ;", $db);
		if (!$query)
			die("Error reading query: ".mysql_error());

		if( $rows=mysql_fetch_row($query) )
			$id = $rows[0] + 1;
		else
			$id = 1000;
				
		$address_index="posts/".$id."index.dtx" ;
		$post_index=explode(chr(13),$post_index);
		$f=fopen($ajaxDir.$address_index , "w");
		foreach($post_index as $buf )
		{
			fputs($f , $buf);
		}
		fclose($f);
				
		$date = date("Y-m-d");
		$time = date("G:i");
			
		$sql = "INSERT INTO `postsbykk` (`id`, `blogid`, `post_title`, `post_body`, `post_comments`, 
		`date`, `time`, `state`, `ppass`)
		VALUES ('$id', '$blogId' , '$name', '$address_index', '', 
		'$date', '$time', '1', '$ppass');";
			
		$result = mysql_query($sql, $db);
		if($result == false )
			echo mysql_error();
		else
		{
			?>
			<img alt="correct" src="images/correct.png" style="float: left;position: relative;height: 60px;"/>
			<div style="font-size: 15px;color: #333;padding: 20px 5px;font-weight: bold;float:left;">Post Added</div>
			<script>
			setTimeout(function(){
				//gotoPage('full',500,'index/news.php');
			},1000);
			</script>
			<?php
		}
	}
	else if($_GET["mode"] == 'edit' || $_GET["mode"] == 'applyedit')
	{
		?>
		<div style="opacity:0;width: 100%;height: 100%;" id="index-loader">
		<?php
		if($_GET["mode"] == 'applyedit')
		{
			$ajaxDir = "../";
				
			$id = $_POST["id"];
			
			$name = $_POST["post_name"];
			
			$ppass = $_POST["post_pass"];
	
			$post_index = $_POST["post_index"];
			if (get_magic_quotes_gpc()) $post_index = stripslashes($post_index);
			$post_index = htmlspecialchars($post_index, ENT_QUOTES,"UTF-8");
			
			$address_index="posts/".$id."index.dtx" ;
			$post_index=explode(chr(13),$post_index);
			$f=fopen($ajaxDir.$address_index , "w");
			foreach($post_index as $buf )
			{
				fputs($f , $buf);
			}
			fclose($f);
			
			$query = mysql_query("UPDATE postsbykk SET post_title = '$name', post_body = '$address_index' , ppass = '$ppass' WHERE id='$id' ;", $db);
			if($query)
			{
			?>
			<div id="success_dialog" style="display: block;opacity:1;top: 40%;height: 60px;">
			<img alt="correct" src="images/correct.png" style="float: left;position: relative;height: 60px;"/>
			<div style="font-size: 15px;color: #333;padding: 20px 5px;font-weight: bold;float:left;">Changes Applied!</div>
			</div>
			<script type="text/javascript">
			$("#success_dialog").click(function(){
					$(this).remove();
				});
			setTimeout(function () {
				$("#success_dialog").animate({'opacity':'0.0'},300,function(){
					$("#success_dialog").remove();
				});
			},2000);
			</script>
			<?php
			}
		}
		
		if(isset($_GET['id']))
			$id = $_GET['id'];
		else
			$id = $_POST['id'];
			
		$query = mysql_query("SELECT * FROM postsbykk WHERE state = 1 AND id = '$id' ;", $db);
		if (!$query)
			die("Error reading query: ".mysql_error());
		if($nrow=mysql_fetch_row($query))
		{
		?>
		<script type="text/javascript">
		</script>
			<div style="width: 100%;overflow: hidden;box-shadow:0 0 1px #444;" id="addpost-container">
				<div class="div-title">EDIT POST</div>
				<form action="index/addpost.php?mode=applyedit" method="POST" enctype="multipart/form-data" id="addpost_form" >
					<input type="hidden" name="id" value="<?php echo $nrow[0]; ?>">
					<div style="display: none;" id="abtemp"></div>
				<div class="div-row">
					<div class="input-div" >
						<div class="lable" ><span class="red" >*</span>Tile</div>
						<input type="text" class="textbykk" id="post_name" name="post_name" value="<?php echo $nrow[2]; ?>" style="width: 400px;" >
					</div>
					<div class="input-div" >
						<div class="lable" >Password</div>
						<input type="text" class="textbykk" id="post_pass" name="post_pass" value="<?php echo $nrow[9]; ?>" style="width: 400px;" >
					</div>
				</div>
				<div class="div-row" >
					<div class="input-div" style="width: 845px;" >
						<div class="lable" >Content</div>
						<textarea name="post_index" id="post_index" ><?php
						$f = fopen("../".$nrow[3], "r");
						if($f===false)
							die("'".$nrow[3]."' doesn't exist.");
						else
						while(!feof($f))
						{
							$buf = fgets($f , 4096);
							$buf = htmlspecialchars_decode($buf, ENT_QUOTES);
							echo $buf;
						}
						fclose($f);
						 ?></textarea>
					</div>
				</div>
				<div class="div-row" >
					<div class="input-div" >
						<input type="submit" class="btnbykk" value="Apply!" style="width: 412px;">
					</div>
				</div>
				</form>
			</div>
			<script type="text/javascript">
			$("#post_index").jqte();
			function postBeforeSend()
			{
				var sCounter = 0;
				if($("#addpost_form #post_name").val().length < 2)
				{
					$("#addpost_form #post_name").removeClass("green").addClass("red");
				}
				else
				{
					$("#addpost_form #post_name").removeClass("red").addClass("green");
					sCounter++;
				}

				if(sCounter>=1)
				{
					progressBar(300, 60);
					return true;
				}
				else
					return false;
			}
			$(document).ready(function() { 
			    $('#addpost_form').ajaxForm({ 
			        target: '#main', 
			        success: function() { 
	            		progressBar(300, 100);
	            		$('#index-loader').animate({'opacity':'1'},300);
			        	//$('#success_dialog').css({'display':'block'}).animate({'opacity' : '1'},300);
			        } 
			    	,
			    	beforeSubmit: postBeforeSend
			    }); 
			});
			</script>
			</div>
		<?php
		}
	}
	else if($_GET["mode"] == 'delete')
	{
		//include 'database.php';
		$id = $_GET["id"];
		$query = mysql_query("UPDATE postbykk SET state = '0' WHERE id='$id' ;", $db);
		if(!$query)
			echo mysql_error();
	}
	else
	{
	?>
	<div style="opacity:0;width: 100%;height: 100%;" id="index-loader">
	<script type="text/javascript">
	</script>
		<div id="success_dialog" style="display: none;opacity:0;top: 50%;height: 60px;">
		</div>
		<div style="width: 100%;overflow: hidden;box-shadow:0 0 1px #444;" id="addpost-container">
			<div class="div-title">ADD POST</div>
			<form action="index/addpost.php?mode=add&blogid=<?php echo $blogId; ?>" method="POST" id="addpost_form" >
				<div style="display: none;" id="abtemp"></div>
				<div class="div-row">
					<div class="input-div" >
						<div class="lable" ><span class="red" >*</span>Tile</div>
						<input type="text" class="textbykk" id="post_name" name="post_name" style="width: 400px;" >
					</div>
					<div class="input-div" >
						<div class="lable" ><span class="red" >*</span>Password</div>
						<input type="text" class="textbykk" id="post_pass" name="post_pass" style="width: 400px;" >
					</div>
				</div>
				<div class="div-row" >
					<div class="input-div" style="width: 845px;" >
						<div class="lable" >Content</div>
						<textarea name="post_index" id="post_index" ></textarea>
					</div>
				</div>
				<div class="div-row" >
					<div class="input-div" >
						<input type="submit" class="btnbykk" value="Add!" style="width: 412px;">
					</div>
				</div>
				
			</form>
		</div>
		<script type="text/javascript">
		$("#post_index").jqte();
		function postBeforeSend()
		{
			var sCounter = 0;
			if($("#addpost_form #post_name").val().length < 2)
			{
				$("#addpost_form #post_name").removeClass("green").addClass("red");
			}
			else
			{
				$("#addpost_form #post_name").removeClass("red").addClass("green");
				sCounter++;
			}

			if(sCounter>=1)
			{
				progressBar(300, 60);
				return true;
			}
			else
				return false;
		}
		$(document).ready(function() { 
		    $('#addpost_form').ajaxForm({ 
		        target: '#success_dialog', 
		        success: function() { 
		        	progressBar(300, 80);
		        	$('#addpost-container').animate({'opacity':'0.0','height':'600px'},1000, function(){
	            		$('#addpost-container').remove();
	            		progressBar(300, 100);
	               	});
		        	$('#success_dialog').css({'display':'block'}).animate({'opacity' : '1'},300);
		        } 
		    	,
		    	beforeSubmit: postBeforeSend
		    }); 
		});
		</script>
		</div>
<?php 
	}
}
?>