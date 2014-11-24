{**
 * plugins/generic/pdfJsViewer/index.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Embedded PDF viewer using pdf.js.
 *}

<div id="pdfDownloadLinkContainer">
	<a class="action pdf" id="pdfDownloadLink" target="_parent" href="{url op="download" path=$articleId|to_array:$galley->getBestGalleyId($currentJournal)}">{translate key="article.pdf.download"}</a>
</div>

<script type="text/javascript" src="{$baseUrl}/plugins/generic/pdfJsViewer/pdf.js/build/pdf.js"></script>

{url|assign:"pdfUrl" op="viewFile" path=$articleId|to_array:$galley->getBestGalleyId($currentJournal) escape=false}
<script type="text/javascript">
	{literal}
		$(document).ready(function() {
			PDFJS.workerSrc='{/literal}{$baseUrl}/plugins/generic/pdfJsViewer/pdf.js/build/pdf.worker.js{literal}';
			PDFJS.getDocument({/literal}'{$pdfUrl|escape:"javascript"}'{literal}).then(function(pdf) {
				// Using promise to fetch the page
				pdf.getPage(1).then(function(page) {
					var scale = 1.5;
					var viewport = page.getViewport(scale);
					var canvas = document.getElementById('pdfCanvas');
					var context = canvas.getContext('2d');
					var pdfCanvasContainer = $('#pdfCanvasContainer');
					canvas.height = pdfCanvasContainer.height();
					canvas.width = pdfCanvasContainer.width()-2; // 1px border each side
					var renderContext = {
						canvasContext: context,
						viewport: viewport
					};
					page.render(renderContext);
				});
			});
		});
	{/literal}
</script>

<div id="pdfCanvasContainer" style="min-height: 500px;">
	<canvas id="pdfCanvas" style="border:1px solid black;"/>
</div>
