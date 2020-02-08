<?php

/**
 * @defgroup plugins_viewableFile_pdfJsViewer
 */

/**
 * @file plugins/viewableFile/pdfJsViewer/index.php
 *
 * Copyright (c) 2013-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @ingroup plugins_viewableFile_pdfJsViewer
 * @brief Wrapper for pdf.js-based viewer.
 *
 */

require_once('PdfJsViewerPlugin.inc.php');
return new PdfJsViewerPlugin();

