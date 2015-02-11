{**
 * plugins/generic/pdfJsViewer/templates/issueGalley.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Issue galley view for PDF files (embedded using pdf.js).
 *}
{include file="issue/header.tpl"}
{url|assign:"pdfUrl" op="viewFile" path=$issueId|to_array:$galley->getBestGalleyId($currentJournal)}

<div id="pdfDownloadLinkContainer">
	<a class="action pdf" id="pdfDownloadLink" target="_parent" href="{url op="download" path=$issueId|to_array:$galley->getBestGalleyId($currentJournal)}">{translate key="article.pdf.download"}</a>
</div>

{include file="$pluginTemplatePath/pdfViewer.tpl" pdfUrl=$pdfUrl}

{include file="common/footer.tpl"}
