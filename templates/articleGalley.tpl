{**
 * plugins/generic/pdfJsViewer/templates/articleGalley.tpl
 *
 * Copyright (c) 2014-2018 Simon Fraser University
 * Copyright (c) 2003-2018 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Embedded viewing of a PDF galley.
 *}
{capture assign="parentUrl"}{url page="article" op="view" path=$article->getBestArticleId($currentJournal)}{/capture}
{include file=$displayTemplateResource title=$article->getLocalizedTitle() parentUrl=$parentUrl pdfUrl=$pdfUrl}
