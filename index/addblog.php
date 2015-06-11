<?php
include 'database.php';
if($isLoggedIn)
{
	if($_GET["mode"] == 'add')
	{
		$ajaxDir = "../";
			
		$name = $_POST["blog_name"];
	
		$category = $_POST["category"];
		if($category == 'other')
		{
			$category = $_POST["category_name"];
		}
		
		$summary = $_POST["description"];
		$summary = explode("\n", $summary);
		$summary = implode("<br>", $summary);
		$summary = mysql_real_escape_string($summary);
		
		$address = $_POST["blog_address"];
		$address = strtolower($address);
		$address = explode(' ',$address);
		$address = implode('', $address);
			
		$picture=$_FILES["picture"]["name"];
		$tmp_file=$_FILES["picture"]["tmp_name"];
		$type=$_FILES["picture"]["type"];
			
		$query = mysql_query("SELECT id FROM blogsbykk ORDER BY id  DESC ;", $db);
		if (!$query)
			die("Error reading query: ".mysql_error());

		if( $rows=mysql_fetch_row($query) )
			$id = $rows[0] + 1;
		else
			$id = 1000;
				
		$address_picture = '';
		$thumb_name = '';
		if($type == "image/jpeg" || $type == "image/png" || $type == "image/gif" )
		{
			$picture=explode(' ',$picture);
			$picture=implode('',$picture);
			$address_picture= "blogs/".$id.rand(1000000,9999999).".jpg";;
			move_uploaded_file($tmp_file, $ajaxDir.$address_picture);
				
				
			//$image_name = $address_picture;    // Full path and image name with extension
			$thumb_name = "blogs/".$id."thumb".rand(1000000,9999999).".jpg";;   // Generated thumbnail name without extension
				
			$image = imagecreatefromstring(file_get_contents($ajaxDir.$address_picture));
				
			$filename = $ajaxDir.$thumb_name;

			$thumb_width = 1000;
			$thumb_height = 150;
				
			$width = imagesx($image);
			$height = imagesy($image);
				
			$original_aspect = $width / $height;
			$thumb_aspect = $thumb_width / $thumb_height;
				
			if ( $original_aspect >= $thumb_aspect )
			{
				// If image is wider than thumbnail (in aspect ratio sense)
				$new_height = $thumb_height;
				$new_width = $width / ($height / $thumb_height);
			}
			else
			{
				// If the thumbnail is wider than the image
				$new_width = $thumb_width;
				$new_height = $height / ($width / $thumb_width);
			}
				
			$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

			imagefilter($thumb, IMG_FILTER_COLORIZE, 232, 232, 232);

			// Resize and crop
			imagecopyresampled($thumb,
					$image,
					0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
					0 - ($new_height - $thumb_height) / 2, // Center the image vertically
					0, 0,
					$new_width, $new_height,
					$width, $height);
			imagejpeg($thumb, $filename, 90);
			unlink($ajaxDir.$address_picture);
		}
				
		mkdir($ajaxDir.$address);
		
		$redirect_path = $ajaxDir.$address."/default.php";
		$f=fopen($redirect_path, "w");
		fputs($f , "<?php header( 'Location: ../index.php?selectedblog=".$id."' ) ; ?>");
		fclose($f);
				
		$date = date("F d, Y");
		$time = date("G:i");
			
		$sql = "INSERT INTO `blogsbykk` (`id`, `author_id`, `blog_url`, `name`, `description`, 
		`blog_address`, `header_pic`, `category`, `date`, `time`, `state`)
		VALUES ('$id', ".$_SESSION["userbykk"][0].", '$address', '$name', '$summary', 
		'$address', '$thumb_name', '$category', '$date', '$time', '1');";
			
		$result = mysql_query($sql, $db);
		if($result == false )
			echo mysql_error();
		else
		{
			?>
			<img alt="correct" src="images/correct.png" style="float: left;position: relative;height: 60px;"/>
			<div style="font-size: 15px;color: #333;padding: 20px 5px;font-weight: bold;float:left;">Blog Added</div>
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
			
			$name = $_POST["blog_name"];
	
			$category = $_POST["category"];
			if($category == 'other')
			{
				$category = $_POST["category_name"];
			}
			
			$summary = $_POST["description"];
			$summary = explode("\n", $summary);
			$summary = implode("<br>", $summary);
			$summary = mysql_real_escape_string($summary);
			
			$address = $_POST["blog_address"];
			$address = strtolower($address);
			$address = explode(' ',$address);
			$address = implode('', $address);
				
			$picture=$_FILES["picture"]["name"];
			$tmp_file=$_FILES["picture"]["tmp_name"];
			$type=$_FILES["picture"]["type"];
			
			if($_POST["delete_picture"])
			{
				if($$_POST["default_picture_thumb"] != '')
					unlink($ajaxDir.$_POST["default_picture_thumb"]);
				$address_picture = '';
				$thumb_name = '';
			}
			else
			{
				$address_picture = '';
				$thumb_name = $_POST["default_picture_thumb"];
			}
			if($picture != '')
			{
				if($type == "image/jpeg" || $type == "image/png" || $type == "image/gif" )
				{
					$picture=explode(' ',$picture);
					$picture=implode('',$picture);
					$address_picture= "blogs/".$id.rand(1000000,9999999).".jpg";
					move_uploaded_file($tmp_file, $ajaxDir.$address_picture);
				
				
					//$image_name = $address_picture;    // Full path and image name with extension
					$thumb_name = "blogs/".$id."thumb".rand(1000000,9999999).".jpg";  // Generated thumbnail name without extension
				
					$image = imagecreatefromstring(file_get_contents($ajaxDir.$address_picture));
				
					$filename = $ajaxDir.$thumb_name;
				
					$thumb_width = 1000;
					$thumb_height = 150;
				
					$width = imagesx($image);
					$height = imagesy($image);
				
					$original_aspect = $width / $height;
					$thumb_aspect = $thumb_width / $thumb_height;
				
					if ( $original_aspect >= $thumb_aspect )
					{
						// If image is wider than thumbnail (in aspect ratio sense)
						$new_height = $thumb_height;
						$new_width = $width / ($height / $thumb_height);
					}
					else
					{
						// If the thumbnail is wider than the image
						$new_width = $thumb_width;
						$new_height = $height / ($width / $thumb_width);
					}
						
					$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
			
					imagefilter($thumb, IMG_FILTER_COLORIZE, 255, 255, 255);
			
					// Resize and crop
					imagecopyresampled($thumb,
							$image,
							0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
							0 - ($new_height - $thumb_height) / 2, // Center the image vertically
							0, 0,
							$new_width, $new_height,
							$width, $height);
					imagejpeg($thumb, $filename, 90);
					
					unlink($address_picture);
				}	
			}
			
			
			
			$query = mysql_query("UPDATE blogsbykk SET category = '$category', name = '$name', description = '$summary', 
									header_pic='$thumb_name' WHERE id='$id' ;", $db);
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
			
		$query = mysql_query("SELECT * FROM blogsbykk WHERE state = 1 AND id = '$id' ;", $db);
		if (!$query)
			die("Error reading query: ".mysql_error());
		if($nrow=mysql_fetch_row($query))
		{
		?>
		<script type="text/javascript">
		</script>
			<div style="width: 100%;overflow: hidden;box-shadow:0 0 1px #444;" id="addblog-container">
				<div class="div-title">EDIT BLOG</div>
				<form action="index/addblog.php?mode=applyedit" method="POST" enctype="multipart/form-data" id="addblog_form" >
					<input type="hidden" name="id" value="<?php echo $nrow[0]; ?>">
					<div class="div-row">
						<div class="input-div" >
							<div class="lable" ><span class="red" >*</span>Blog Name</div>
							<input type="text" class="textbykk" id="blog_name" name="blog_name" value="<?php echo $nrow[3]; ?>" style="width: 400px;" >
						</div>
						<div class="input-div" style="width: 412px;" >
							<div class="lable" ><span class="red" >*</span>Category</div>
							<select class="textbykk" id="category" name="category" >
								<option  ></option>
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
											<option value='".$row[0]."' "; 
											if($row[0] == $nrow[7])
												echo "selected='selected'";
											echo " >".getCategory($row[0])."</option>
											";
											$category = $row[0];
										}
									}
								?>
								<option value="other" >other...</option>
							</select>
							<input type="text" class="textbykk" id="category_name" name="category_name" >
						</div>
					</div>
					<div class="div-row">
						<div class="input-div" >
							<div class="lable" ><span class="red" >*</span>Blog Address <span style="color: red;font-size: 11px;display: none;float: right;" id="eaddress"> This address is already taken!</span></div>
							localhost/.../HW4/<?php echo $nrow[5]; ?>
						</div>
					</div>
					<div class="div-row">
						<div class="input-div" style="width: 845px;" >
							<div class="lable" >Description</div>
							<textarea class="textbykk" name="description" id="description" style="width: 840px;height: 75px;"><?php echo $nrow[4]; ?></textarea>
						</div>
					</div>
					<div class="div-row" >
						<div class="input-div" style="width:840px;" >
							<div class="lable" >Header Pic</div>
							<input type="file" class="textbykk" id="picture" name="picture" style="width: 400px;" >
							<input type="hidden" name="default_picture_thumb" value="<?php echo $nrow[6]; ?>">
							<?php 
						if($nrow[6] != '')
						{
							?>
							<div style="position: relative;float: right;margin-left: 25px;">
								<img src="<?php echo $nrow[6]; ?>" style="height:20px;float:right;box-shadow:0 1px 3px rgba(0,0,0,0.5);" />
								<span style="z-index:299;position: absolute;float: left;bottom:0px;right:0px;left:0;background-color: rgba(0,0,0,0.6);color:#fff;padding: 1px 5px;font-size: 10px;vertical-align: middle;"><input type="checkbox" name="delete_picture" style="margin: 1px;float: left;" ><span style="float: left;margin-bottom: 3px;">DEL</span></span>
							</div>
							<?php 
						}
						?>
						</div>
						
					</div>
					<div class="div-row" >
						<div class="input-div" >
							<input type="submit" class="btnbykk" value="Apply" style="width: 412px;">
						</div>
					</div>
					
				</form>
			</div>
			<script type="text/javascript">
			//$("#news_index").jqte();
			function newsBeforeSend()
			{
				var sCounter = 0;
				if($("#addblog_form #blog_name").val().length < 2)
				{
					$("#addblog_form #blog_name").removeClass("green").addClass("red");
				}
				else
				{
					$("#addblog_form #blog_name").removeClass("red").addClass("green");
					sCounter++;
				}

				if($("#addblog_form #category").val().length < 2)
				{
					$("#addblog_form #category").removeClass("green").addClass("red");
				}
				else
				{
					if($("#addblog_form #category").val() == 'other')
					{
						if($("#addblog_form #category_name").val().length < 2)
						{
							$("#addblog_form #category_name").removeClass("green").addClass("red");
						}
						else
						{
							$("#addblog_form #category_name").removeClass("red").addClass("green");
							sCounter++;
						}
					}
					else
					{
						$("#addblog_form #category").removeClass("red").addClass("green");
						sCounter++;
					}
				}

				if(sCounter>=2)
				{
					progressBar(300, 60);
					return true;
				}
				else
					return false;
			}
			$(document).ready(function() { 
			    $('#addblog_form').ajaxForm({ 
			        target: '#main', 
			        success: function() { 
	            		progressBar(300, 100);
	            		$('#index-loader').animate({'opacity':'1'},300);
			        	//$('#success_dialog').css({'display':'block'}).animate({'opacity' : '1'},300);
			        } 
			    	,
			    	beforeSubmit: newsBeforeSend
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
		$query = mysql_query("UPDATE blogsbykk SET state = '0' WHERE id='$id' ;", $db);
		if(!$query)
			echo mysql_error();
	}
	else if($_GET["mode"] == 'checkaddress')
	{
		$address = $_GET["eaddress"];
		$address = strtolower($address);
		$address = explode(' ',$address);
		$address = implode('', $address);
		$query = mysql_query("SELECT id FROM blogsbykk WHERE blog_address = '$address' ;", $db);
		if( $rows=mysql_fetch_row($query) )
		{
			?>
			<script type="text/javascript">
				eaddress = true;
			</script>
			<?php
		}
		else 
		{
			?>
			<script type="text/javascript">
				eaddress = false;
			</script>
			<?php
		}
			
	}
	else
	{
	?>
	<div style="opacity:0;width: 100%;height: 100%;" id="index-loader">
	<script type="text/javascript">
	</script>
		<div id="success_dialog" style="display: none;opacity:0;top: 50%;height: 60px;">
		</div>
		<div style="width: 100%;overflow: hidden;box-shadow:0 0 1px #444;" id="addblog-container">
			<div class="div-title">ADD BLOG</div>
			<form action="index/addblog.php?mode=add" method="POST" enctype="multipart/form-data" id="addblog_form" >
				<div style="display: none;" id="abtemp"></div>
				<div class="div-row">
					<div class="input-div" >
						<div class="lable" ><span class="red" >*</span>Blog Name</div>
						<input type="text" class="textbykk" id="blog_name" name="blog_name" style="width: 400px;" >
					</div>
					<div class="input-div" style="width: 412px;" >
						<div class="lable" ><span class="red" >*</span>Category</div>
						<select class="textbykk" id="category" name="category" >
							<option  ></option>
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
							<option value="other" >other...</option>
						</select>
						<input type="text" class="textbykk" id="category_name" name="category_name" >
					</div>
				</div>
				<div class="div-row">
					<div class="input-div" >
						<div class="lable" ><span class="red" >*</span>Blog Address <span style="color: red;font-size: 11px;display: none;float: right;" id="eaddress"> This address is already taken!</span></div>
						localhost/.../HW4/<input type="text" class="textbykk" id="blog_address" name="blog_address" style="width: 295px;" >
					</div>
				</div>
				<div class="div-row">
					<div class="input-div" style="width: 845px;" >
						<div class="lable" >Description</div>
						<textarea class="textbykk" name="description" id="description" style="width: 840px;height: 75px;"></textarea>
					</div>
				</div>
				<div class="div-row" >
					<div class="input-div" >
						<div class="lable" >Header Pic</div>
						<input type="file" class="textbykk" id="picture" name="picture" style="width: 400px;" >
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
		var eaddress = true;
		$("#blog_address").blur(function(){
			$("#abtemp").load("index/addblog.php?mode=checkaddress&eaddress="+$("#blog_address").val(),function(response){
				if(eaddress)
					$("#eaddress").fadeIn(100);
				else
					$("#eaddress").fadeOut(100);
			});
		});
		function newsBeforeSend()
		{
			var sCounter = 0;
			if($("#addblog_form #blog_name").val().length < 2)
			{
				$("#addblog_form #blog_name").removeClass("green").addClass("red");
			}
			else
			{
				$("#addblog_form #blog_name").removeClass("red").addClass("green");
				sCounter++;
			}

			if($("#addblog_form #blog_address").val().length < 2)
			{
				$("#addblog_form #blog_address").removeClass("green").addClass("red");
			}
			else
			{
				$("#addblog_form #blog_address").removeClass("red").addClass("green");
				sCounter++;
			}

			if($("#addblog_form #category").val().length < 2)
			{
				$("#addblog_form #category").removeClass("green").addClass("red");
			}
			else
			{
				if($("#addblog_form #category").val() == 'other')
				{
					if($("#addblog_form #category_name").val().length < 2)
					{
						$("#addblog_form #category_name").removeClass("green").addClass("red");
					}
					else
					{
						$("#addblog_form #category_name").removeClass("red").addClass("green");
						sCounter++;
					}
				}
				else
				{
					$("#addblog_form #category").removeClass("red").addClass("green");
					sCounter++;
				}
			}

			if(sCounter>=3 && !eaddress)
			{
				progressBar(300, 60);
				return true;
			}
			else
				return false;
		}
		$(document).ready(function() { 
		    $('#addblog_form').ajaxForm({ 
		        target: '#success_dialog', 
		        success: function() { 
		        	progressBar(300, 80);
		        	$('#addblog-container').animate({'opacity':'0.0','height':'600px'},1000, function(){
	            		$('#addblog-container').remove();
	            		progressBar(300, 100);
	               	});
		        	$('#success_dialog').css({'display':'block'}).animate({'opacity' : '1'},300);
		        } 
		    	,
		    	beforeSubmit: newsBeforeSend
		    }); 
		});
		</script>
		</div>
<?php 
	}
}
?>