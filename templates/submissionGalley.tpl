{**
 * plugins/generic/pdfJsViewer/templates/submissionGalley.tpl
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Embedded viewing of a PDF galley.
 *}
{capture assign="pdfUrl"}{strip}
	{if $isLatestPublication}
		{url op="download" path=$bestId|to_array:$galley->getBestGalleyId($currentJournal):$galleyFile->getId() escape=false}
	{else}
		{url op="download" path=$bestId|to_array:'version':$galleyPublication->getId():$galley->getBestGalleyId($currentJournal):$galleyFile->getId() escape=false}
	{/if}
{/strip}{/capture}
{capture assign="parentUrl"}{url page=$submissionNoun op="view" path=$bestId}{/capture}
{capture assign="galleyTitle"}{translate key="submission.representationOfTitle" representation=$galley->getLabel() title=$publication->getLocalizedFullTitle()|escape}{/capture}
{capture assign="datePublished"}{translate key="submission.outdatedVersion" datePublished=$galleyPublication->getData('datePublished')|date_format:$dateFormatLong urlRecentVersion=$parentUrl}{/capture}
{include file=$displayTemplateResource title=$submission->getLocalizedTitle() parentUrl=$parentUrl pdfUrl=$pdfUrl galleyTitle=$galleyTitle datePublished=$datePublished}
