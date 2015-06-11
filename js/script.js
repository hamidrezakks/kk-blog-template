var mainURL = "http://localhost/Zend/workspaces/DefaultWorkspace10/hw4/index/getxml.php?mode=blogs";
var	isLoggedIn = false;
var blogsCache=null, tagList = new Array();
	//0~> id
	//1~> tag
var xmlCounter = 1;
var xmlCache = new Array(); 
	//0~> DATA
	//1~> URL
$("document").ready(function(){
	//loadMain();
	
	$("#mainpage").click(function(){
		$("#main").empty().append('<div id="navigator" ><div class="tags" >TAGS:<select id="tags-list"><option value="all">All</option></select></div></div><div id="blogs-container" >No Connection!!!</div>')
		$("#blogs-container").animate({'opacity':'0'},300,function(){
			loadMain('All');
			$("#blogs-container").animate({'opacity':'1'},300);
		});
	});
	
	$("#favposts").click(function(){
		$("#main").empty().append('<div id="navigator" ><div class="tags" >TAGS:<select id="tags-list"><option value="all">All</option></select></div></div><div id="blogs-container" >No Connection!!!</div>')
		$("#blogs-container").animate({'opacity':'0'},300,function(){
			showFav();
			$("#blogs-container").animate({'opacity':'1'},300);
		});
	});
	
	$("#posts .post .fav").click(function(){
		if($(this).hasClass("star"))
			$(this).removeClass("star");
		else
			$(this).addClass("star");
	});
});

function checkContainTag(i,tag)
{
	try
	{
		return (tagList[i][0].indexOf(tag) > -1)? true: false;
	}
	catch(e)
	{
		return false;
	}
		
}
function loadMain(tag, isMyBlog, myID)
{
	$("#main").empty().append('<div id="navigator" ><div class="tags" >TAGS:<select id="tags-list"><option value="all">All</option></select></div></div><div id="blogs-container" >No Connection!!!</div>')
	if(isMyBlog == null)
		isMyBlog = false;
	var tagsCount = 0;
	/*while(!blogsCache)
	{*/
		blogsCache = getXML(mainURL);
	//}
	blogs = blogsCache.responseXML.documentElement.getElementsByTagName("blog");
	$("#blogs-container").empty();
	//getXML("http://ceit.aut.ac.ir/~bakhshis/IE/S93-HW-3/blogs/blog-2.xml");
	for(var i=0; i < blogs.length; i++)
	{
		var authorid = $(blogsCache.responseXML).find("blog > authorid").eq(i).text();
		if(authorid != myID && isMyBlog)
		{
			i++;
			continue;
		}
		/*if(isMyBlog)
		{
			$("#controlpanel_container .controls").empty().css({'opacity':'0.0'}).append(
				"<div class='btn add' onclick='addPost("+myID+");' title='Post' ></div>"
				).animate({'opacity':'1'},300);
		}*/
		if(tag == null || tag == 'All')
		{
			var blogUrl = blogs[i].getElementsByTagName("url")[0].firstChild.nodeValue;
			var id = $(blogsCache.responseXML).find("blog > id").eq(i).text();
			var blogData = null;
			/*while(!blogData)
			{*/
				blogData = getXML(blogUrl);
			//}
			var xmlDoc = blogData.responseXML;
			//blogUrl =  encodeURIComponent(blogUrl);
			
			/*if(tag == null)
			{
				var sTag = "All";
				for(var j=0; j<$(xmlDoc).find("tag").length; j++)
				{
					sTag = sTag + "," + $(xmlDoc).find("tag").eq(j).text();
				}
				tagsCount++;
				tagList.push([sTag, $(xmlDoc).find("id").text()]);
			}*/
			
			$("#blogs-container").append("<div class='item' >"+
					"<div class='info' >"+
					"<h1>Blog#"+(i+1)+": "+$(xmlDoc).find("name").text()+" </h1>"+
					"<h2>Description: <i>"+$(xmlDoc).find("description").text()+"<br><br>"+
					"Number of Posts: "+$(xmlDoc).find("posts > post").length+"</i></h2><span>K.K.</span>"+
				"</div>"+
				"<div class='link button' style='left:82%;' onclick=\"loadBlog('"+blogUrl+"', null, null, "+id+");\" >Show Blog!!!</div>"+
				((isMyBlog == true)?"<div class='link button' style='left:18%;' onclick=\"changeBlog("+id+");\" >Change Blog</div>":"")+
			"</div>");
		}
	}
	/*var tags =  Array(1000), ttag;
	if(tag == null)
	{
		var num = 0, check = false;
		for(var i=0; i< tagsCount; i++)
		{
			ttag = tagList[i][0].split(",");
			for(var j=0; j < ttag.length; j++)
			{
				check = false;
				for(var k=0; k<= num; k++)
				{
					if(ttag[j] == tags[k])
					{
						check = true;
						break;
					}
				}
				if(!check)
				{
					tags[num] = ttag[j];
					num++;
				}
			}
		}
		
		$("#tags-list").empty();
		for(var i=0; i< num; i++)
		{
			$("#tags-list").append("<option value='"+tags[i]+"' >"+tags[i]+"</option>");
		}
	}*/
	$("#tags-list").load("index/getcategory.php");
	/*if(tag == null || tag == 'All')
	{
		$("#mainpage").animate({'opacity':'0'},300).css({'display':'none'});
	}
	else
	{
		$("#mainpage").css({'display':'block'}).animate({'opacity':'1'},300);
	}*/
	$("#favposts").css({'display':'block'}).animate({'opacity':'1'},300);
	
	$("#tags-list").on('change', function() {
		var tag = this.value;
		mainURL = "http://localhost/Zend/workspaces/DefaultWorkspace10/hw4/index/getxml.php?mode=blogs&tag="+tag;
		//alert(mainURL);
		$("#blogs-container").animate({'opacity':'0'},300,function(){
			loadMain();
			$("#blogs-container").animate({'opacity':'1'},300);
		});
	});
}
function gotoBlog(url, page, archive, id)
{
	var xmlCache=null;
	/*while(!xmlCache)
	{*/
		xmlCache = getXML(url);
	//}
	if(page == null && archive == null)
	{
		$("#controlpanel_container .controls").empty().css({'opacity':'0.0'}).append(
			"<div class='btn add' onclick='addPost("+id+");' title='Add Post' ></div>"
			).animate({'opacity':'1'},300);
		page = 1;
	
		$("#blogs-container").empty();
		var xmlDoc = xmlCache.responseXML;
		var str="", author;
		var dateArchive = new Array();
		//[0] ~> year
		//[1] ~> month
		
		str ='<div id="title">'+$(xmlDoc).find("name").text()+'</div>';
		$("#blogs-container").append(str);
		
		$("#blogs-container").append('<div id="posts" ></div>');
		
		author = $(xmlDoc).find("author").text();
		var posts = $(xmlDoc).find("posts > post");
		//alert(posts.length);
		for(var i=(page-1)*2; i< (page)*2 && i< posts.length ; i++)
		{
			var postUrl = posts.eq(i).children("url").text();
			var postData = null;
			/*while(!postData)
			{*/
				postData = getXML(postUrl);
			//}
			var postDoc = postData.responseXML;
			var comment = $(postDoc).find("comments > comment").length;
			var favId = id+"_"+posts.eq(i).children("id").text();
			var fav;
			if(checkFav(favId))
				fav = ' id="f-'+favId+'" class="fav star" title="Remove from favorite" onclick="removeFav(\''+favId+'\')" ';
			else
				fav = ' id="f-'+favId+'" class="fav" title="Add to favorites" onclick="addFav(\''+favId+'\')" ';
			comment = (comment > 1)? comment+" comments" : comment+" comment";
			str =	'<div class="post" >'+
						'<div '+fav+' ></div>'+
						'<h2 onclick="showPost(\''+postUrl+'\', '+id+', \''+author+'\');" >'+$(postDoc).find("post title").text()+'</h2>'+
						'<div class="text" >'+(($(postDoc).find("post > body").text().length > 500)?$(postDoc).find("post > body").text().substr(0,500)+' <br><b onclick="showPost(\''+postUrl+'\', '+id+', \''+author+'\');" >More...</b>':$(postDoc).find("post > body").text())+'</div>'+
						'<div class="info" >By '+author+' &nbsp;&nbsp; '+posts.eq(i).children("date").text()+' - '+comment+'</div>'+
					'</div>';
			$("#blogs-container #posts").append(str);
		}
		
		var temp;
		for(var i=0; i<posts.length ; i++)
		{
			temp = posts.eq(i).children("date").text().split("-");
			dateArchive.push([temp[0], temp[1]]);
		}
		
		for(var i=0; i < posts.length; i++)
		{
			for(var j=0; j <= i; j++)
			{
				if(parseInt(dateArchive[i][0])*1000 + parseInt(dateArchive[i][1]) > parseInt(dateArchive[j][0])*1000 + parseInt(dateArchive[j][1]))
				{
					temp = dateArchive[i][1];
					dateArchive[i][1] = dateArchive[j][1];
					dateArchive[j][1] = temp;
					
					temp = dateArchive[i][0];
					dateArchive[i][0] = dateArchive[j][0];
					dateArchive[j][0] = temp;
				}
			}
		}
		
		str='';
		var cYear='', cMonth='', check=false;
		for(var i=0; i < posts.length; i++)
		{
			if(cYear != dateArchive[i][0])
			{
				if(check)
					str += '</ul></ul><ul><li onclick="loadBlog(\''+url+'\', null,\''+dateArchive[i][0]+'\', '+id+');" >'+dateArchive[i][0]+'</li><ul>';
				else
					str += '<ul><li onclick="loadBlog(\''+url+'\', null,\''+dateArchive[i][0]+'\', '+id+');" >'+dateArchive[i][0]+'</li><ul>';
				cYear = dateArchive[i][0];
				check = true;
			}
			if(cMonth != dateArchive[i][1])
			{
				str += '<li onclick="loadBlog(\''+url+'\', null,\''+dateArchive[i][0]+'-'+dateArchive[i][1]+'\', '+id+');" >'+getMonthName(dateArchive[i][1])+'</li>';
				cMonth = dateArchive[i][1];
			}
		}
		str += '</ul></ul>';
		
		$("#blogs-container").append('<div id="archive" ><div id="title">Blog Archive</div></div>');
		$("#blogs-container #archive").append(str);
		
		
		if(posts.length > 2)
		{
			str = '';
			for(var i=1; i <= (posts.length+1)/2; i++)
			{
				if(i == 1)
					str += '<span class="page selected" onclick="loadBlog(\''+url+'\' ,'+i+',  null, '+id+');" >'+i+'</span>';
				else
					str += '<span class="page" onclick="loadBlog(\''+url+'\', '+i+', null, '+id+');" >'+i+'</span>';
			}
			$("#blogs-container").append('<div id="pages"></div>');
			$("#blogs-container #pages").append(str);
		}
	}
	else
	{
		var xmlDoc = xmlCache.responseXML;
		var str="", author, text;
		
		$("#blogs-container #posts").empty();
		author = $(xmlDoc).find("author").text();
		var posts = $(xmlDoc).find("posts > post");
		
		if(archive == null)
		{
			for(var i=(page-1)*2; i< (page)*2 && i< posts.length ; i++)
			{
				var postUrl = posts.eq(i).children("url").text();
				var postData = null;
				/*while(!postData)
				{*/
					postData = getXML(postUrl);
				//}
				var postDoc = postData.responseXML;
				var comment = $(postDoc).find("comments > comment").length;
				comment = (comment > 1)? comment+" comments" : comment+" comment";
				var favId = id+"_"+posts.eq(i).children("id").text();
				var fav;
				if(checkFav(favId))
					fav = ' id="f-'+favId+'" class="fav star" title="Remove from favorite" onclick="removeFav(\''+favId+'\')" ';
				else
					fav = ' id="f-'+favId+'" class="fav" title="Add to favorites" onclick="addFav(\''+favId+'\')" ';
				comment = (comment > 1)? comment+" comments" : comment+" comment";
				str =	'<div class="post" >'+
					'<div '+fav+' ></div>'+
					'<h2 onclick="showPost(\''+postUrl+'\', '+id+', \''+author+'\');" >'+$(postDoc).find("post title").text()+'</h2>'+
					'<div class="text" >'+(($(postDoc).find("post > body").text().length > 500)?$(postDoc).find("post > body").text().substr(0,500)+' <br><b onclick="showPost(\''+postUrl+'\', '+id+', \''+author+'\');" >More...</b>':$(postDoc).find("post > body").text())+'</div>'+
					'<div class="info" >By '+author+' &nbsp;&nbsp; '+posts.eq(i).children("date").text()+' - '+comment+'</div>'+
				'</div>';
				$("#blogs-container #posts").append(str);
			}
		}
		else
		{
			if(page == null)
				page = 1;
			var num=-1;
			for(var i=0; i< posts.length ; i++)
			{
				if(posts.eq(i).children("date").text().indexOf(archive) > -1)
				{
					num++;
					if(num >= (page-1)*2 && num < (page)*2 )
					{
						var postUrl = posts.eq(i).children("url").text();
						var postData = null;
						/*while(!postData)
						{*/
							postData = getXML(postUrl);
						//}
						var postDoc = postData.responseXML;
						var comment = $(postDoc).find("comments > comment").length;
						comment = (comment > 1)? comment+" comments" : comment+" comment";
						var favId = id+"_"+posts.eq(i).children("id").text();
						var fav;
						if(checkFav(favId))
							fav = ' id="f-'+favId+'" class="fav star" title="Remove from favorite" onclick="removeFav(\''+favId+'\')" ';
						else
							fav = ' id="f-'+favId+'" class="fav" title="Add to favorites" onclick="addFav(\''+favId+'\')" ';
						comment = (comment > 1)? comment+" comments" : comment+" comment";
						str =	'<div class="post" >'+
									'<div '+fav+' ></div>'+
									'<h2 onclick="showPost(\''+postUrl+'\', '+id+', \''+author+'\');" >'+$(postDoc).find("post title").text()+'</h2>'+
									'<div class="text" >'+$(postDoc).find("post > body").text()+'</div>'+
									'<div class="info" >By '+author+' &nbsp;&nbsp; '+posts.eq(i).children("date").text()+' - '+comment+'</div>'+
								'</div>';
						$("#blogs-container #posts").append(str);	
					}
				}
			}
			if(page == 1 && num > 1)
			{
				str = '';
				for(var i=1; i <= (num+2)/2; i++)
				{
					if(i == 1)
						str += '<span class="page selected" onclick="loadBlog(\''+url+'\' ,'+i+',  \''+archive+'\', '+id+');" >'+i+'</span>';
					else
						str += '<span class="page" onclick="loadBlog(\''+url+'\', '+i+', \''+archive+'\', '+id+');" >'+i+'</span>';
				}
				$("#blogs-container #pages").html(str);
			}
			else if(num <=1 )
			{
				$("#blogs-container #pages").empty();
			}
		}
	}
	$("#blogs-container #pages .page").click(function(){
		$("#blogs-container #pages .page").removeClass("selected");
		$(this).addClass("selected");
	});
}
function loadBlog(url, page, archive, id)
{
	/*$("#mainpage").css({'display':'block'}).animate({'opacity':'1'},300);
	$("#favposts").css({'display':'block'}).animate({'opacity':'1'},300);*/
	if(page == null && archive == null)
	{
		$("#blogs-container").animate({'opacity':'0'},300,function(){
			gotoBlog(url, page, archive, id);
			$("#blogs-container").animate({'opacity':'1'},300);
		});
	}
	else
	{
		$("#blogs-container #posts").animate({'opacity':'0'},300,function(){
			gotoBlog(url, page, archive, id);
			$("#blogs-container #posts").animate({'opacity':'1'},300);
		});
	}
}
function queryXML(XML, query, val)
{
	var parent = query.split(' ');
	var lists = $(XML.responseXML).find(parent[0]);
	for(var i=0; i  < lists.length; i++)
	{
		if(lists.eq(i).children(parent[1]).text() == val)
		{
			return lists.eq(i);
		}
	}
}
function gotoFav(page, archive, update)
{
	var str = getCookie("favorite");
	var part = str.split("|");
	var favPosts = new Array();
	
	var dateArchive = new Array();
	//[0] ~> year
	//[1] ~> month
	
	for(var i=0; i <= part.length; i++)
	{
		if(part[i] != null && part[i] != '')
		{
			var temp = part[i].split('_');
			favPosts.push([temp[0], temp[1]]);
		}
	}
	
	var blogList = null, postList = null, postXML = null;
	/*while(!blogList)
	{*/
		blogList = getXML(mainURL);
	//}
	
	var favBlog, favBlogURL;
	
	if(page == null && archive == null)
	{
		page = 1;
		
		$("#blogs-container").empty();

		$("#blogs-container").append('<div id="title">Favorite posts</div>');
		
		$("#blogs-container").append('<div id="posts" ></div>');
		
		for(var i=(page-1)*2; i< (page)*2 && i < favPosts.length; i++)
		{
			favBlog = queryXML(blogList, "blogs>blog id", favPosts[i][0]);
			favBlogURL = favBlog.children("url").text();
			var id = favPosts[i][0];
			
			postList = null;
			/*while(!postList)
			{*/
				postList = getXML(favBlogURL);
			//}
			
			
			postURL = queryXML(postList, "post>posts>post id", favPosts[i][1]);
			postURL = postURL.children("url").text();
			
			var name = $(postList.responseXML).find("name").text();
			var author = $(postList.responseXML).find("author").text();
			
			postXML = null;
			/*while(!postXML)
			{*/
				postXML = getXML(postURL);
			//}
			
			var postDoc = postXML.responseXML;
			var comment = $(postDoc).find("comments > comment").length;
			var favId = favPosts[i][0]+"_"+favPosts[i][1];
			var fav;
			if(checkFav(favId))
				fav = ' id="f-'+favId+'" class="fav star" title="Remove from favorite" onclick="removeFav(\''+favId+'\', 1, null)" ';
			else
				fav = ' id="f-'+favId+'" class="fav" title="Add to favorites" onclick="addFav(\''+favId+'\')" ';
			comment = (comment > 1)? comment+" comments" : comment+" comment";
			
			str =	'<div class="post" >'+
						'<div '+fav+' ></div>'+
						'<h2 onclick="showPost(\''+postURL+'\', '+id+', \''+author+'\');" >'+$(postDoc).find("post title").text()+' <span>(In '+name+')</span></h2>'+
						'<div class="text" >'+$(postDoc).find("post > body").text()+'</div>'+
						'<div class="info" >By '+author+' &nbsp;&nbsp; '+$(postDoc).find("post > date").text()+' - '+comment+'</div>'+
					'</div>';
			$("#blogs-container #posts").append(str);
			
		}
		
		for(var i=0; i < favPosts.length; i++)
		{
			favBlog = queryXML(blogList, "blogs>blog id", favPosts[i][0]);
			favBlogURL = favBlog.children("url").text();
			
			postList = null;
			/*while(!postList)
			{*/
				postList = getXML(favBlogURL);
			//}
			
			postDate = queryXML(postList, "post>posts>post id", favPosts[i][1]);
			postDate = postDate.children("date").text();
			
			postDate = postDate.split("-");
			dateArchive.push([postDate[0], postDate[1]]);
		}
		
		for(var i=0; i < favPosts.length; i++)
		{
			for(var j=0; j <= i; j++)
			{
				if(parseInt(dateArchive[i][0])*1000 + parseInt(dateArchive[i][1]) > parseInt(dateArchive[j][0])*1000 + parseInt(dateArchive[j][1]))
				{
					temp = dateArchive[i][1];
					dateArchive[i][1] = dateArchive[j][1];
					dateArchive[j][1] = temp;
					
					temp = dateArchive[i][0];
					dateArchive[i][0] = dateArchive[j][0];
					dateArchive[j][0] = temp;
				}
			}
		}
		
		str='';
		var cYear='', cMonth='', check=false;
		for(var i=0; i < favPosts.length; i++)
		{
			if(cYear != dateArchive[i][0])
			{
				if(check)
					str += '</ul></ul><ul><li onclick="showFav(null, \''+dateArchive[i][0]+'\');" >'+dateArchive[i][0]+'</li><ul>';
				else
					str += '<ul><li onclick="showFav(null, \''+dateArchive[i][0]+'\');" >'+dateArchive[i][0]+'</li><ul>';
				cYear = dateArchive[i][0];
				check = true;
			}
			if(cMonth != dateArchive[i][1])
			{
				str += '<li onclick="showFav(null, \''+dateArchive[i][0]+'-'+dateArchive[i][1]+'\');" >'+getMonthName(dateArchive[i][1])+'</li>';
				cMonth = dateArchive[i][1];
			}
		}
		str += '</ul></ul>';
		
		$("#blogs-container").append('<div id="archive" ><div id="title">Favorites Archive</div></div>');
		$("#blogs-container #archive").append(str);
		
		if(favPosts.length > 2)
		{
			str = '';
			for(var i=1; i <= (favPosts.length+1)/2; i++)
			{
				if(i == 1)
					str += '<span class="page selected" onclick="showFav('+i+',  null);" >'+i+'</span>';
				else
					str += '<span class="page" onclick="showFav('+i+', null);" >'+i+'</span>';
			}
			$("#blogs-container").append('<div id="pages"></div>');
			$("#blogs-container #pages").append(str);
		}
	}
	else
	{
		$("#blogs-container #posts").empty();
		
		if(archive == null)
		{
			for(var i=(page-1)*2; i< (page)*2 && i < favPosts.length; i++)
			{
				favBlog = queryXML(blogList, "blogs>blog id", favPosts[i][0]);
				favBlogURL = favBlog.children("url").text();
				var id = favPosts[i][0];
				
				postList = null;
				/*while(!postList)
				{*/
					postList = getXML(favBlogURL);
				//}
				
				
				postURL = queryXML(postList, "post>posts>post id", favPosts[i][1]);
				postURL = postURL.children("url").text();
				
				var name = $(postList.responseXML).find("name").text();
				var author = $(postList.responseXML).find("author").text();
				
				postXML = null;
				/*while(!postXML)
				{*/
					postXML = getXML(postURL);
				//}
				
				var postDoc = postXML.responseXML;
				var comment = $(postDoc).find("comments > comment").length;
				var favId = favPosts[i][0]+"_"+favPosts[i][1];
				var fav;
				if(checkFav(favId))
					fav = ' id="f-'+favId+'" class="fav star" title="Remove from favorite" onclick="removeFav(\''+favId+'\', '+page+', null)" ';
				else
					fav = ' id="f-'+favId+'" class="fav" title="Add to favorites" onclick="addFav(\''+favId+'\')" ';
				comment = (comment > 1)? comment+" comments" : comment+" comment";
				
				str =	'<div class="post" >'+
							'<div '+fav+' ></div>'+
							'<h2 onclick="showPost(\''+postURL+'\', '+id+', \''+author+'\');" >'+$(postDoc).find("post title").text()+' <span>(In '+name+')</span></h2>'+
							'<div class="text" >'+$(postDoc).find("post > body").text()+'</div>'+
							'<div class="info" >By '+author+' &nbsp;&nbsp; '+$(postDoc).find("post > date").text()+' - '+comment+'</div>'+
						'</div>';
				$("#blogs-container #posts").append(str);
				
			}
			if(update)
			{
				str = '';
				for(var i=1; i <= (favPosts.length+1)/2; i++)
				{
					if(i == 1)
						str += '<span class="page selected" onclick="showFav('+i+',  null);" >'+i+'</span>';
					else
						str += '<span class="page" onclick="showFav('+i+', null);" >'+i+'</span>';
				}
				$("#blogs-container #pages").html(str);
			}
		}
		else
		{
			var num = -1;
			for(var i=0; i < favPosts.length; i++)
			{
				favBlog = queryXML(blogList, "blogs>blog id", favPosts[i][0]);
				favBlogURL = favBlog.children("url").text();
				var id = favPosts[i][0];
				
				postList = null;
				/*while(!postList)
				{*/
					postList = getXML(favBlogURL);
				//}
				
				
				var posts = queryXML(postList, "post>posts>post id", favPosts[i][1]);
				postURL = posts.children("url").text();
				
				var name = $(postList.responseXML).find("name").text();
				var author = $(postList.responseXML).find("author").text();
				
				if(page == null)
					page = 1;
				
				if(posts.children("date").text().indexOf(archive) > -1)
				{
					num++;
					if(num >= (page-1)*2 && num < (page)*2 )
					{
						postXML = null;
						/*while(!postXML)
						{*/
							postXML = getXML(postURL);
						//}
						
						var postDoc = postXML.responseXML;
						var comment = $(postDoc).find("comments > comment").length;
						var favId = favPosts[i][0]+"_"+favPosts[i][1];
						var fav;
						if(checkFav(favId))
							fav = ' id="f-'+favId+'" class="fav star" title="Remove from favorite" onclick="removeFav(\''+favId+'\', '+page+', \''+archive+'\')" ';
						else
							fav = ' id="f-'+favId+'" class="fav" title="Add to favorites" onclick="addFav(\''+favId+'\')" ';
						comment = (comment > 1)? comment+" comments" : comment+" comment";
						
						str =	'<div class="post" >'+
									'<div '+fav+' ></div>'+
									'<h2 onclick="showPost(\''+postURL+'\', '+id+', \''+author+'\');" >'+$(postDoc).find("post title").text()+' <span>(In '+name+')</span></h2>'+
									'<div class="text" >'+$(postDoc).find("post > body").text()+'</div>'+
									'<div class="info" >By '+author+' &nbsp;&nbsp; '+$(postDoc).find("post > date").text()+' - '+comment+'</div>'+
								'</div>';
						$("#blogs-container #posts").append(str);
					}
				}
			}
			if(page == 1 && num > 1)
			{
				str = '';
				for(var i=1; i <= (num+2)/2; i++)
				{
					if(i == 1)
						str += '<span class="page selected" onclick="showFav('+i+',  \''+archive+'\');" >'+i+'</span>';
					else
						str += '<span class="page" onclick="showFav('+i+',  \''+archive+'\');" >'+i+'</span>';
				}
				$("#blogs-container #pages").html(str);
			}
			else if(num <=1 )
			{
				$("#blogs-container #pages").empty();
			}
			if(update)
			{
				for(var i=0; i < favPosts.length; i++)
				{
					favBlog = queryXML(blogList, "blogs>blog id", favPosts[i][0]);
					favBlogURL = favBlog.children("url").text();
					
					postList = null;
					while(!postList)
					{
						postList = getXML(favBlogURL);
					}
					
					postDate = queryXML(postList, "post>posts>post id", favPosts[i][1]);
					postDate = postDate.children("date").text();
					
					postDate = postDate.split("-");
					dateArchive.push([postDate[0], postDate[1]]);
				}
				
				for(var i=0; i < favPosts.length; i++)
				{
					for(var j=0; j <= i; j++)
					{
						if(parseInt(dateArchive[i][0])*1000 + parseInt(dateArchive[i][1]) > parseInt(dateArchive[j][0])*1000 + parseInt(dateArchive[j][1]))
						{
							temp = dateArchive[i][1];
							dateArchive[i][1] = dateArchive[j][1];
							dateArchive[j][1] = temp;
							
							temp = dateArchive[i][0];
							dateArchive[i][0] = dateArchive[j][0];
							dateArchive[j][0] = temp;
						}
					}
				}
				
				str='';
				var cYear='', cMonth='', check=false;
				for(var i=0; i < favPosts.length; i++)
				{
					if(cYear != dateArchive[i][0])
					{
						if(check)
							str += '</ul></ul><ul><li onclick="showFav(null, \''+dateArchive[i][0]+'\');" >'+dateArchive[i][0]+'</li><ul>';
						else
							str += '<ul><li onclick="showFav(null, \''+dateArchive[i][0]+'\');" >'+dateArchive[i][0]+'</li><ul>';
						cYear = dateArchive[i][0];
						check = true;
					}
					if(cMonth != dateArchive[i][1])
					{
						str += '<li onclick="showFav(null, \''+dateArchive[i][0]+'-'+dateArchive[i][1]+'\');" >'+getMonthName(dateArchive[i][1])+'</li>';
						cMonth = dateArchive[i][1];
					}
				}
				str += '</ul></ul>';
				
				$("#blogs-container #archive").html('<div id="title">Favorites Archive</div>');
				$("#blogs-container #archive").append(str);
			}
		}
	}
	$("#blogs-container #pages .page").click(function(){
		$("#blogs-container #pages .page").removeClass("selected");
		$(this).addClass("selected");
	});
	
}
function showFav(page, archive, update)
{
	if(page == null && archive == null)
	{
		$("#blogs-container").animate({'opacity':'0'},300,function(){
			gotoFav(page, archive, update);
			$("#blogs-container").animate({'opacity':'1'},300);
		});
	}
	else
	{
		$("#blogs-container #posts").animate({'opacity':'0'},300,function(){
			gotoFav(page, archive, update);
			$("#blogs-container #posts").animate({'opacity':'1'},300);
		});
	}
	/*if(archive != null)
	{
		$("#favposts").css({'display':'block'}).animate({'opacity':'1'},300);
		$("#mainpage").css({'display':'block'}).animate({'opacity':'1'},300);
	}
	else
	{
		$("#favposts").animate({'opacity':'0'},300).css({'display':'none'});
		$("#mainpage").css({'display':'block'}).animate({'opacity':'1'},300);
	}*/
}
function checkFav(id)
{
	var str = getCookie("favorite");
	if(str.indexOf(id)> -1)
		return true;
	else
		return false;
}
function addFav(id)
{
	$("#f-"+id).addClass("star").attr("onclick","removeFav(\'"+id+"\')").attr("title","Remove from favorites");
	var str = getCookie("favorite");
	str += "|"+id;
	setCookie("favorite", str, 365);
}
function removeFav(id, page, archive)
{
	$("#f-"+id).removeClass("star").attr("onclick","addFav(\'"+id+"\')").attr("title","Add to favorites");
	var str = getCookie("favorite");
	var part = str.split("|"+id);
	str = part[0]+part[1];
	setCookie("favorite", str, 365);
	if(page != null)
	{
		showFav(page, archive, true);
	}
}
function checkCachedXML(url)
{
	try
	{
		for(var i=1; i <= xmlCounter + 1; i++)
		{
			if(xmlCache[i][1] == url)
				return i;
		}
		return false;
	}
	catch(e)
	{
		return false;
	}
}
var currentPost = new Array();
function gotoPost(url, id, author)
{
	$("#favposts").css({'display':'block'}).animate({'opacity':'1'},300);
	var xml = getXML(url);
	xhttp = new XMLHttpRequest();
	xhttp.open("GET", "xml/post.xsl", false);
	try {xhttp.responseType = "msxml-document"; } catch(err) {} // Helping IE11
	xhttp.send("");
	var xsl = xhttp.responseXML;
	
	xml = xml.responseXML;
	var result;
	
	var xsltProcessor = new XSLTProcessor();
	xsltProcessor.importStylesheet(xsl);
	result = xsltProcessor.transformToFragment(xml, document);
	var response = xml;
	var xtitle = $(response).find("post > title").text();
	var xbody = $(response).find("post > body").text();
	var xdate = $(response).find("post > date").text();
	var xid = $(response).find("post > id").text();
	var ppass = $(response).find("post > ppass").text();
	
	if(ppass != '')
	{		
		pass = prompt("Please enter post pass","password");
		if(ppass != pass)
		{
			alert("Wrong pass");
			return false;
		}
	}
	

	var output = '<div id="post" >'
		+'<div  class="fav" id="pfav" ></div>'
		+(isLoggedIn==true?'<div  class="change-post" onclick=\"changePost('+xid+');\"  >Change It</div>':'')
		+'<h2>'+xtitle+'</h2>'
		+'<div class="text">'
			+xbody
		+'</div>'
		+'<div class="info">'
		+xdate
		+'</div>'
	+'</div>';
	
	currentPost[0] = url;
	currentPost[1] = id;
	currentPost[2] = author;
	currentPost[3] = xid;
	
	$("#blogs-container").empty().append(output);
	$("#blogs-container").append(result);
	$("#blogs-container").append('<input type="submit" class="btnbykk" onclick="popComment('+xid+');" value="Add Comment" style="margin-left:10px;width: 200px;">');
	
	$("#blogs-container #post .info").prepend("By "+author+" &nbsp;&nbsp; ");
	var favId = id+"_"+$(xml).find("post > id").text();
	if(checkFav(favId))
	{
		$("#pfav").attr("onclick",'removeFav(\''+favId+'\')').attr("title",'Remove from favorite').addClass("star").attr("id","f-"+favId);
	}
	else
	{
		$("#pfav").attr("onclick",'addFav(\''+favId+'\')').attr("title",'Add to favorite').attr("id","f-"+favId);
	}
	
}
function showPost(url, id, author)
{
	$("#blogs-container").animate({'opacity':'0'},300,function(){
		gotoPost(url, id, author);
		$("#blogs-container").animate({'opacity':'1'},300);
	});
}
function getXML(url)
{
	/*var id=checkCachedXML(url);
	if(id)
	{
		return xmlCache[id][0];
	}
	else
	{*/
		var xmlhttp;
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		/*xmlhttp.onreadystatechange=function()
		{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
					
					xmlCache.push([xmlhttp, url]);
					console.log(xmlhttp);
					xmlCounter++;
					return xmlhttp;
			}
		}*/
        //var load_link = "reader.php?address=" + url;
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
		
		if(xmlhttp.status == 200)
		{
			console.log(xmlhttp);
			return xmlhttp;
		}
		//return false;
	//}
	
}
function setCookie(cname,cvalue,exdays)
{
	var d = new Date();
	d.setTime(d.getTime()+(exdays*24*60*60*1000));
	var expires = "expires="+d.toGMTString();
	document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname)
{
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) 
	{
		var c = ca[i].trim();
		if (c.indexOf(name)==0) return c.substring(name.length,c.length);
	}
	return "";
}
function getMonthName(num)
{
	var mon = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
	num = parseInt(num);
	return mon[num-1];
}