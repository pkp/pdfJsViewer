<?php

/**
 * @defgroup plugins_viewableFile_pdfJsViewer
 */

/**
 * @file plugins/viewableFile/pdfJsViewer/index.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_viewableFile_pdfJsViewer
 * @brief Wrapper for pdf.js-based viewer.
 *
 */

require_once('PdfJsViewerPlugin.inc.php');
return new PdfJsViewerPlugin();

?>
