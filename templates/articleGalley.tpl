{**
 * plugins/generic/pdfJsViewer/templates/articleGalley.tpl
 *
 * Copyright (c) 2014-2019 Simon Fraser University
 * Copyright (c) 2003-2019 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Embedded viewing of a PDF galley.
 *}
{capture assign="pdfUrl"}{url op="download" path=$bestId|to_array:$galley->getBestGalleyId($currentJournal):$galleyFile->getId() escape=false}{/capture}
{capture assign="parentUrl"}{url page="article" op="view" path=$bestId}{/capture}
{include file=$displayTemplateResource title=$article->getLocalizedTitle() parentUrl=$parentUrl pdfUrl=$pdfUrl}
