<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
	<div id="comments">
		<div id="title"><xsl:value-of select="count(post/comments/comment)"/> comment(s):</div>
		<xsl:for-each select="post/comments/comment">
		<div class="comment">
			<div class="author" >
				<xsl:value-of select="author"/> 
				<span> <xsl:value-of select="date"/></span>
				<div class="cmreply" onclick="addReply(this);" >Reply</div>
				<div class="cmid" style="display:none;" ><xsl:value-of select="id"/></div>
				<div class="text" >
					<xsl:value-of select="body"/>
				</div>
			</div>
			<xsl:call-template name="response" />
		</div>
    	</xsl:for-each>
	</div>
</xsl:template>
<xsl:template name="response" >
	<xsl:for-each select="responses/comment">
		<div class="response">
			<div class="author" >
				<xsl:value-of select="author"/>
				<span> <xsl:value-of select="date"/></span>
				<div class="cmreply" onclick="addReply(this);" >Reply</div>
				<div class="cmid" style="display:none;" ><xsl:value-of select="id"/></div>
				<div class="text" >
					<xsl:value-of select="body"/>
				</div>
			</div>
			<xsl:call-template name="response" />
		</div>
	</xsl:for-each>
</xsl:template>
</xsl:stylesheet>