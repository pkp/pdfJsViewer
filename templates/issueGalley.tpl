{**
 * plugins/generic/pdfJsViewer/templates/issueGalley.tpl
 *
 * Copyright (c) 2014-2019 Simon Fraser University
 * Copyright (c) 2003-2019 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Embedded viewing of a PDF galley.
 *}
{capture assign="pdfUrl"}{url op="download" path=$issue->getBestIssueId($currentJournal)|to_array:$galley->getBestGalleyId($currentJournal) escape=false}{/capture}
{capture assign="parentUrl"}{url page="issue" op="view" path=$issue->getBestIssueId($currentJournal)}{/capture}
{include file=$displayTemplateResource title=$issue->getIssueIdentification() parentUrl=$parentUrl pdfUrl=$pdfUrl}
