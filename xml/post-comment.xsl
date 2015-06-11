<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
	<div  >
		<div ></div>
		<h2><xsl:value-of select="post/title"/></h2>
		<div>
			<xsl:copy-of select="post/body"/>
		</div>
		<div >
		<xsl:value-of select="post/date"/>
		</div>
	</div>
	<div id="comments">
		<div id="title">comment</div>
		<xsl:for-each select="post/comments/comment">
		<div class="comment">
			<div class="author" >
				<xsl:value-of select="author"/> 
				<span> <xsl:value-of select="date"/></span>
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
		<div >
			<div  >
				<xsl:value-of select="author"/>
				<span> <xsl:value-of select="date"/></span>
				<div  >
					<xsl:value-of select="body"/>
				</div>
			</div>
			<xsl:call-template name="response" />
		</div>
	</xsl:for-each>
</xsl:template>
</xsl:stylesheet>