<?php

/**
 * @file plugins/generic/pdfJsViewer/PdfJsViewerPlugin.inc.php
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PdfJsViewerPlugin
 *
 * @brief This plugin enables embedding of the pdf.js viewer for PDF display
 */

import('classes.plugins.ViewableFilePlugin');

class PdfJsViewerPlugin extends ViewableFilePlugin {
	/**
	 * @copydoc Plugin::getDisplayName
	 */
	function getDisplayName() {
		return __('plugins.generic.pdfJsViewer.name');
	}

	/**
	 * @copydoc Plugin::getDescription
	 */
	function getDescription() {
		return __('plugins.generic.pdfJsViewer.description');
	}

	/**
	 * @see ViewableFilePlugin::displayArticleGalley
	 */
	function displayArticleGalley($templateMgr, $request, $params) {
		$templatePath = $this->getTemplatePath();
		$templateMgr->assign('pluginTemplatePath', $templatePath);
		$templateMgr->assign('pluginUrl', $request->getBaseUrl() . '/' . $this->getPluginPath());
		$galley = $templateMgr->get_template_vars('galley');
		$galleyFiles = $galley->getLatestGalleyFiles();
		assert(count($galleyFiles)==1);
		$templateMgr->assign('firstGalleyFile', array_shift($galleyFiles));
		return parent::displayArticleGalley($templateMgr, $request, $params);
	}

	/**
	 * Get the template path
	 * @return string
	 */
	function getTemplatePath() {
		return parent::getTemplatePath() . 'templates/';
	}
}

?>
