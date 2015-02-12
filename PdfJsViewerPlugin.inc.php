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

import('lib.pkp.classes.plugins.GenericPlugin');

class PdfJsViewerPlugin extends GenericPlugin {
	/**
	 * Register the plugin.
	 * @param $category string Plugin category
	 * @param $path string Plugin path
	 * @return boolean true for success
	 */
	function register($category, $path) {
		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
				HookRegistry::register('TemplateManager::include', array(&$this, '_includeCallback'));
				HookRegistry::register('TemplateManager::display', array(&$this, '_displayCallback'));
			}

			return true;
		}
		return false;
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
	 * Hook callback function for TemplateManager::include
	 * @param $hookName string Hook name
	 * @param $args array Hook arguments
	 */
	function _includeCallback($hookName, $args) {
		if ($this->getEnabled()) {
			$templateMgr =& $args[0];
			$params =& $args[1];

			if (!isset($params['smarty_include_tpl_file'])) return false;

			switch ($params['smarty_include_tpl_file']) {
				case 'article/pdfViewer.tpl':
					$templatePath = $this->getTemplatePath();
					$templateMgr->assign('pluginTemplatePath', $templatePath);
					$templateMgr->assign('pluginUrl', Request::getBaseUrl() . DIRECTORY_SEPARATOR . $this->getPluginPath());
					$params['smarty_include_tpl_file'] = $templatePath . 'articleGalley.tpl';
					break;
			}
			return false;
		}
	}

	/**
	 * Hook callback function for TemplateManager::display
	 * @param $hookName string Hook name
	 * @param $args array Hook arguments
	 */
	function _displayCallback($hookName, $args) {
		if ($this->getEnabled()) {
			$templateMgr =& $args[0];
			$template =& $args[1];

			switch ($template) {
				case 'issue/issueGalley.tpl':
					$templatePath = $this->getTemplatePath();
					$templateMgr->assign('pluginTemplatePath', $templatePath);
					$templateMgr->assign('pluginUrl', Request::getBaseUrl() . DIRECTORY_SEPARATOR . $this->getPluginPath());
					$template = $templatePath . 'issueGalley.tpl';
					break;
			}
			return false;
		}
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
