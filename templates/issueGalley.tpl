{**
 * plugins/viewableFiles/pdfJsViewer/issueGalley.tpl
 *
 * Copyright (c) 2014-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Embedded viewing of a PDF galley.
 *}
{url|assign:"pdfUrl" op="download" path=$issue->getBestIssueId($currentJournal)|to_array:$galley->getBestGalleyId($currentJournal) escape=false}
{url|assign:"parentUrl" page="issue" op="view" path=$issue->getBestIssueId($currentJournal)}
{include file="$pluginTemplatePath/display.tpl" title=$issue->getIssueSeries() parentUrl=$parentUrl pdfUrl=$pdfUrl}
