<?php
include 'database.php';
?>
<html>
	<head>
		<title>HW4</title>
		<link href="css/global.css" rel="stylesheet" type="text/css" />
		<script type='text/javascript' src='js/jquery-2.1.0.min.js'></script>
		<script type='text/javascript' src='js/script.js'></script>
		<script type='text/javascript' src='js/jquery.form.min.js'></script>
		<script type='text/javascript' src='js/jquery.history.js'></script>
		<script type="text/javascript" src="js/jquery-te-1.4.0.min.js" charset="utf-8"></script>
		<link rel="stylesheet" href="css/jquery-te-1.4.0.css" media="screen" />
	</head>
	<body>
		<div id="login-overlay" >
			<div id="login-form" >
				<span class="cancelbykk" id="login-cancel">
				</span>
				<form action="?login=1" method="POST" >
					<div style="padding: 5px 5%;">
						<div style="width: 100%;color: #555;margin: 0 0 2px 0;">Email</div>
						<input type="text"  style="width: 258px;" class="textbykk" name="lemail" >
					</div>
					<div style="padding: 5px 5%;">
						<div style="width: 100%;color: #555;margin: 0 0 2px 0;">Password</div>
						<input type="password"  style="width: 258px;" class="textbykk" name="lpassword" >
					</div>
					<div style="padding: 5px 5%;">
						<div style="width: 100%;color: #555;margin: 0 0 2px 0;"><input type="checkbox" style="float: left;margin: 5px;" name="rememberme" ><div style="margin: 3px;float: left;">Remember</div></div>
						<input type="submit" value="Login" style="width: 268px;" class="btnbykk" >
						<input type="hidden" name="login" value="1" >
					</div>
				</form>
			</div>
		</div>
		<div id="comment-overlay" >
			<div id="comment-form" >
				<span class="cancelbykk" id="comment-cancel">
				</span>
					<div style="padding: 5px 5%;">
						<div style="width: 100%;color: #555;margin: 0 0 2px 0;">Name</div>
						<input type="text"  style="width: 450px;" class="textbykk" id="cmname" >
					</div>
					<div style="padding: 5px 5%;">
						<div style="width: 100%;color: #555;margin: 0 0 2px 0;">Comment</div>
						<textarea id="cmbody" class="textbykk" style="width: 450px;height: 100px;"></textarea>
					</div>
					<div style="padding: 5px 5%;">
						<input type="submit" value="Post" style="width: 450px;" class="btnbykk" id="cmbutton" >
					</div>
			</div>
		</div>
		<div id="signup-overlay" >
			<?php 
			if(!$isLoggedIn)
			{
			?>
			<div id="signup-form" >
				<span class="cancelbykk" id="signup-cancel">
				</span>
				<div class="ajax-index">
				</div>
			</div>
			<?php 
			}
			else
			{
			?>
			<div id="profile-form" >
				<span class="cancelbykk" id="profile-cancel">
				</span>
				<div class="ajax-index">
				</div>
			</div>
			<?php 
			}
			?>
		</div>
		<div id="toolbar" >
			<div class="inner" >
				<div id="toolbar-menu" >
				<?php 
				if(!$isLoggedIn)
				{
				?>
					<div id="login-pop-up" class="login">Login</div>
					<div id="signup-pop-up" class="signup">Sign up</div>
				<?php 
				}
				else
				{
				?>
					<div class="logout" ><a href="?logout=1" style="color:#ccc;" class="download" >Logout</a></div>
					<div  class="profile" id="profile-pop-up">Profile [<?php if(isset($_SESSION["adminbykk"])) echo $_SESSION["adminbykk"][1]; else echo $_SESSION["userbykk"][1]; ?>]</div>
				<?php 
				}
				?>
				</div>
				<div id="toolbar-nav" >
					<div class="navitem" id="mainpage" >Home</div>
					<div class="navitem" id="favposts" >Favorite Post</div>
					<?php 
					if($isLoggedIn)
					{
					?>
					<div class="navitem" id="myblogs" >My Blogs</div>
					<div class="navitem" id="addblog" onclick="gotoPage('index/addblog.php');" >Add blog</div>
					<?php 
					}
					?>
				</div>
			</div>
			<?php 
			if(isset($_SESSION["login_msg"]))
			{
			?>
				<div id="login-alert" class="<?php if($_SESSION["login_msg"][0] == '1') echo "green"; else echo "red";?>" ><?php echo $_SESSION["login_msg"][1];?></div>
				<script type="text/javascript">
				<?php 
				if($_SESSION["login_msg"][0] != '2')
				{
				?>
					$("#login-alert").click(function(){
						$("#login-alert").animate({'opacity':'0'},300,function(){
							$("#login-alert").css({'display':'none'});
						});
					});
				<?php 
				}
				?>
				var marginLeft = $("#login-alert").outerWidth()/2;
				setTimeout(function(){
					$("#login-alert").css({'margin-left':-marginLeft+"px"});
				},10);
				setTimeout(function(){
					$("#login-alert").animate({'opacity':'0'},300,function(){
						$("#login-alert").css({'display':'none'});
					});
				},<?php if($_SESSION["login_msg"][0] == '2') echo "10000"; else echo "2500"; ?>);
				</script>
			<?php 
			unset($_SESSION["login_msg"]);
			}
			?>
			<?php 
				if($isLoggedIn)
				{
				?>
				<div id="controlpanel_container" >
					<span class="lable" >control panel</span>
					<div class="controls">
					</div>
				</div>
				<?php 
				}
			?>
		</div>
		<div id="nav-shadow" >
			<div id="progress-bar"></div>
		</div>
		<div id="main" >
			<div id="navigator" >
				<!-- <div class="item" id="mainpage">
					main page
				</div>
				<div class="item" id="favposts">
					show favorite posts
				</div> -->
				<div class="tags" >
					TAGS:
					<select id="tags-list">
						<option value="all">All</option>
					</select>
				</div>
			</div>
			<div id="blogs-container" >
				No Connection!!!
			</div>
		</div>
		<div id="archive">
		<div id="recent">
		<div id="title" >Recents</div>
		<ul>
		Memebers
		<?php 
		$query = mysql_query("SELECT name FROM registerbykk WHERE state = 1 ORDER BY id  DESC ;", $db);
		if (!$query)
			die("Error reading query: ".mysql_error());
		$i = 0;
		while($rows=mysql_fetch_row($query))
		{
			$i++;
			if($i>7) break;
			echo "<li>".$rows[0]."</li>";
		}
		?>
		</ul>
		
		<ul>
		Posts
		<?php 
		$query = mysql_query("SELECT post_title FROM postsbykk WHERE state = 1 ORDER BY id  DESC ;", $db);
		if (!$query)
			die("Error reading query: ".mysql_error());
		$i = 0;
		while($rows=mysql_fetch_row($query))
		{
			$i++;
			if($i>7) break;
			echo "<li>".$rows[0]."</li>";
		}
		?>
		</ul>
		</div>
			
		</div>
		<div id="cmtemp" style="display: none;" >
		</div>
		<script type="text/javascript">
		
		function disableATag()
		{
			$("a").not(".download").click(function(e){
				e.preventDefault();
			});
			
		}
		function progressBar(speed,percentage)
		{
			$("#progress-bar").stop().animate({'width': percentage+'%'},speed,"linear",function(){
				if(percentage > 99)
				{
					$("#progress-bar").animate({'opacity':'0'},200,function(){
						$("#progress-bar").css({'width':'0%','opacity':'1'});
					});
					disableATag();
				}
				else
				{
					$("#progress-bar").stop().animate({'width': (percentage+5)+'%'},5000,"linear");
				}
			});
		}
		
		function gotoPage(url)
		{
			progressBar(300,50);
			$("#index-loader").animate({'opacity':'0'},300);
			$("#main").load(url,function(){
				$("#index-loader").animate({'opacity':'1'},300);
				progressBar(300,100);
			});
		}
				
		var isInsideLoginForm = false, isInsideSignupForm = false, isInsideProfileForm = false, isInsideCommentForm = false;
		$("#login-form").mouseenter(function(){
			isInsideLoginForm = true;
		}).mouseleave(function(){
			isInsideLoginForm = false;
		});
		$("#comment-form").mouseenter(function(){
			isInsideCommentForm = true;
		}).mouseleave(function(){
			isInsideCommentForm = false;
		});
		<?php 
		if(!$isLoggedIn)
		{
		?>
		$("#signup-form").mouseenter(function(){
			isInsideSignupForm = true;
		}).mouseleave(function(){
			isInsideSignupForm = false;
		});
		<?php 
		}
		else
		{
		?>
		$("#profile-form").mouseenter(function(){
			isInsideProfileForm = true;
		}).mouseleave(function(){
			isInsideProfileForm = false;
		});
		<?php 
		}
		?>
		$(document).click(function(){
			if($("#login-form").hasClass("visible") && !isInsideLoginForm)
			{
				$("#login-overlay").animate({'opacity':'0'},300,function(){
					$(this).css({'display':'none'});
					$("#login-form").removeClass("visible");
				});
			}

			if($("#comment-form").hasClass("visible") && !isInsideCommentForm)
			{
				$("#comment-overlay").animate({'opacity':'0'},300,function(){
					$(this).css({'display':'none'});
					$("#comment-form").removeClass("visible");
				});
			}
			<?php 
			if(!$isLoggedIn)
			{
			?>
			if($("#signup-form").hasClass("visible") && !isInsideSignupForm)
			{
				$("#signup-overlay").animate({'opacity':'0'},300,function(){
					$(this).css({'display':'none'});
					$("#signup-form").removeClass("visible");
				});
			}
			<?php 
			}
			else
			{
			?>
			if($("#profile-form").hasClass("visible") && !isInsideProfileForm)
			{
				$("#signup-overlay").animate({'opacity':'0'},300,function(){
					$(this).css({'display':'none'});
					$("#profile-form").removeClass("visible");
				});
			}
			<?php 
			}
			?>
		});
		<?php 
		if(!$isLoggedIn)
		{
		?>
		$("#signup-cancel").click(function(){
			$("#signup-overlay").animate({'opacity':'0'},300,function(){
				$(this).css({'display':'none'});
				$("#signup-form").removeClass("visible");
			});
		});
		$("#signup-pop-up").click(function(){
			if(!$("#signup-form").hasClass("loaded"))
			{
				progressBar(300, 75);
				$("#signup-form .ajax-index").load('index/register.php',function(){
					$("#signup-form").addClass("loaded");
					progressBar(300, 100);
				});
			}
			$("#signup-overlay").css({'display':'block','opacity':'0'}).animate({'opacity':'1'},300,function(){
				$("#signup-form").addClass("visible");
			});
		});
		<?php 
		}
		else
		{
		?>
		$("#profile-cancel").click(function(){
			$("#signup-overlay").animate({'opacity':'0'},300,function(){
				$(this).css({'display':'none'});
				$("#profile-form").removeClass("visible");
			});
		});
		$("#profile-pop-up").click(function(){
			if(!$("#profile-form").hasClass("loaded"))
			{
				progressBar(300, 75);
				$("#profile-form .ajax-index").load('index/register.php?mode=profile',function(){
					$("#profile-form").addClass("loaded");
					progressBar(300, 100);
				});
			}
			$("#signup-overlay").css({'display':'block','opacity':'0'}).animate({'opacity':'1'},300,function(){
				$("#profile-form").addClass("visible");
			});
		});
		<?php 
		}
		?>
		$("#login-cancel").click(function(){
			$("#login-overlay").animate({'opacity':'0'},300,function(){
				$(this).css({'display':'none'});
				$("#login-form").removeClass("visible");
			});
		});
		$("#login-pop-up").click(function(){
			$("#login-overlay").css({'display':'block','opacity':'0'}).animate({'opacity':'1'},300,function(){
				$("#login-form").addClass("visible");
				progressBar(300,1000);
			});
		});
		$("#comment-cancel").click(function(){
			$("#comment-overlay").animate({'opacity':'0'},300,function(){
				$(this).css({'display':'none'});
				$("#comment-form").removeClass("visible");
			});
		});
		<?php 
		if($isLoggedIn)
		{
		?>
		isLoggedIn = true;
		$("#myblogs").click(function(){
			mainURL = "http://localhost/Zend/workspaces/DefaultWorkspace10/hw4/index/getxml.php?mode=blogs";
			loadMain("All",true,<?php echo $_SESSION["userbykk"][0]; ?>);
		});

		function changeBlog(id)
		{
			gotoPage('index/addblog.php?mode=edit&id='+id);
		}

		function addPost(id)
		{
			gotoPage('index/addpost.php?blogid='+id);
		}

		function changePost(id)
		{
			gotoPage('index/addpost.php?mode=edit&id='+id);
		}
		<?php 
		}
		?>
		var cmMode = 'new', resId, cmPostId;
		function popComment(postId, cmId)
		{
			cmPostId = postId;
			if(cmId == null)
			{
				cmMode = 'new';
			}
			else
			{
				cmMode = 'res';
				resId = cmId;
			}
			$("#comment-overlay").css({'display':'block','opacity':'0'}).animate({'opacity':'1'},300,function(){
				$("#comment-form").addClass("visible");
				progressBar(300,1000);
			});
		}

		function addComment()
		{
			name = $("#cmname").val();
			name = encodeURIComponent(name);
			body = $("#cmbody").val();
			body = encodeURIComponent(body);
			if(name == '' || body == '')
			{
				alert("Please fill the fields");
				return false;
			}
			if(cmMode == 'new')
			{
				console.log("index/addcomment.php?mode=add&postid="+cmPostId+"&cmname="+name+"&cmbody="+body);
				$("#cmtemp").load("index/addcomment.php?mode=add&postid="+cmPostId+"&cmname="+name+"&cmbody="+body,function(){
					//alert("Your comment have been posted!");
					showPost(currentPost[0], currentPost[1], currentPost[2]);
					$("#comment-overlay").animate({'opacity':'0'},300,function(){
						$(this).css({'display':'none'});
						$("#comment-form").removeClass("visible");
					});
					$("#cmname").val('');
					$("#cmbody").val('');
				});
			}
			else
			{
				$("#cmtemp").load("index/addcomment.php?mode=addres&postid="+cmPostId+"&cmresto="+resId+"&cmname="+name+"&cmbody="+body,function(){
					//alert("Your comment have been posted!");
					showPost(currentPost[0], currentPost[1], currentPost[2]);
					$("#comment-overlay").animate({'opacity':'0'},300,function(){
						$(this).css({'display':'none'});
						$("#comment-form").removeClass("visible");
					});
					$("#cmname").val('');
					$("#cmbody").val('');
				});
			}
		}

		function addReply(myref)
		{
			var id = $(myref).parent().children(".cmid").text();
			popComment(currentPost[3],id);
		}

		$("#cmbutton").click(function(){
			addComment();
		});
		</script>
	</body>
</html>