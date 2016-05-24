{**
 * plugins/generic/pdfJsViewer/articleGalley.tpl
 *
 * Copyright (c) 2014-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Embedded viewing of a PDF galley.
 *}
{url|assign:"pdfUrl" op="download" path=$article->getBestArticleId($currentJournal)|to_array:$galley->getBestGalleyId($currentJournal):$galleyFile->getId() escape=false}
{url|assign:"parentUrl" page="issue" op="view" path=$issue->getBestIssueId($currentJournal)}
{include file="$pluginTemplatePath/display.tpl" title=$article->getLocalizedTitle() parentUrl=$parentUrl pdfUrl=$pdfUrl}
