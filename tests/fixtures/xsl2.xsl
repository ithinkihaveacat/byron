<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:output encoding="UTF-8" indent="yes" method="xml" />
	
	<xsl:param name="greeting"/>

	<xsl:template match="/">
	  <qqqqqq><xsl:value-of select="$greeting"/></qqqqqq>
	</xsl:template>
</xsl:stylesheet>
