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
	 * Install default settings on journal creation.
	 * @return string
	 */
	function getContextSpecificPluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

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
	 * Determine whether this plugin can handle the specified content.
	 * @param $galley ArticleGalley|IssueGalley
	 * @return boolean True iff the plugin can handle the content
	 */
	function canHandle($galley) {
		if (is_a($galley, 'ArticleGalley') && $galley->getGalleyType() == $this->getName()) {
			return true;
		} elseif (is_a($galley, 'IssueGalley') && $galley->getFileType() == 'application/pdf') {
			return true;
		}
		return false;
	}

	/**
	 * @copydoc ViewableFilePlugin::displayArticleGalley
	 */
	function displayArticleGalley($request, $issue, $article, $galley) {
		$templateMgr = TemplateManager::getManager($request);
		$galleyFiles = $galley->getLatestGalleyFiles();
		assert(count($galleyFiles)==1);
		$templateMgr->assign(array(
			'pluginTemplatePath' => $this->getTemplatePath(),
			'pluginUrl' => $request->getBaseUrl() . '/' . $this->getPluginPath(),
			'firstGalleyFile' => array_shift($galleyFiles),
		));
		return parent::displayArticleGalley($request, $issue, $article, $galley);
	}

	/**
	 * @copydoc ViewableFilePlugin::displayArticleGalley
	 */
	function displayIssueGalley($request, $issue, $galley) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign(array(
			'pluginTemplatePath' => $this->getTemplatePath(),
			'pluginUrl' => $request->getBaseUrl() . '/' . $this->getPluginPath(),
		));
		return parent::displayIssueGalley($request, $issue, $galley);
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
