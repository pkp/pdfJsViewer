{**
 * plugins/generic/pdfJsViewer/templates/articleGalley.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Embedded PDF viewer using pdf.js for article galleys.
 *}
<div id="pdfDownloadLinkContainer">
	<a class="action pdf" id="pdfDownloadLink" target="_parent" href="{url op="download" path=$articleId|to_array:$galley->getBestGalleyId($currentJournal)}">{translate key="article.pdf.download"}</a>
</div>

{url|assign:"pdfUrl" op="viewFile" path=$articleId|to_array:$galley->getBestGalleyId($currentJournal) escape=false}
{include file="$pluginTemplatePath/pdfViewer.tpl" pdfUrl=$pdfUrl}
