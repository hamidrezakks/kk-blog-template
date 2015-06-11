function showPost(url, author)
{
	var xml = getXML(url);//inja xmle post o bede, bara man az fucntin e getXML migereft
	xhttp = new XMLHttpRequest();
	xhttp.open("GET", "post-comment.xsl", false);
	xhttp.send("");
	var xsl = xhttp.responseXML;
	
	xml = xml.responseXML;
	var result;
	
	var xsltProcessor = new XSLTProcessor();
	xsltProcessor.importStylesheet(xsl);
	result = xsltProcessor.transformToFragment(xml, document);
	$("#blogs-container").empty().append(result);
	$("#blogs-container #post .info").prepend("By "+author+" &nbsp;&nbsp; ");
	
}