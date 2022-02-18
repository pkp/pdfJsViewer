<?php

/**
 * @file plugins/generic/pdfJsViewer/PdfJsViewerPlugin.inc.php
 *
 * Copyright (c) 2013-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PdfJsViewerPlugin
 *
 * @brief This plugin enables embedding of the pdf.js viewer for PDF display
 */

use APP\core\Application;
use APP\template\TemplateManager;
use PKP\plugins\HookRegistry;

class PdfJsViewerPlugin extends \PKP\plugins\GenericPlugin
{
    /**
     * @copydoc Plugin::register()
     *
     * @param null|mixed $mainContextId
     */
    public function register($category, $path, $mainContextId = null)
    {
        if (parent::register($category, $path, $mainContextId)) {
            if ($this->getEnabled($mainContextId)) {
                // For OPS
                HookRegistry::register('PreprintHandler::view::galley', [$this, 'submissionCallback'], HOOK_SEQUENCE_LAST);
                // For OJS
                HookRegistry::register('ArticleHandler::view::galley', [$this, 'submissionCallback'], HOOK_SEQUENCE_LAST);
                HookRegistry::register('IssueHandler::view::galley', [$this, 'issueCallback'], HOOK_SEQUENCE_LAST);
            }
            return true;
        }
        return false;
    }

    /**
     * Install default settings on context creation.
     *
     * @return string
     */
    public function getContextSpecificPluginSettingsFile()
    {
        return $this->getPluginPath() . '/settings.xml';
    }

    /**
     * @copydoc Plugin::getDisplayName
     */
    public function getDisplayName()
    {
        return __('plugins.generic.pdfJsViewer.name');
    }

    /**
     * @copydoc Plugin::getDescription
     */
    public function getDescription()
    {
        return __('plugins.generic.pdfJsViewer.description');
    }

    /**
     * Callback that renders the submission galley.
     *
     * @param string $hookName
     * @param array $args
     *
     * @return bool
     */
    public function submissionCallback($hookName, $args)
    {
        $request = & $args[0];
        $application = Application::get();
        switch ($application->getName()) {
            case 'ojs2':
                $issue = & $args[1];
                $galley = & $args[2];
                $submission = & $args[3];
                $submissionNoun = 'article';
                break;
            case 'ops':
                $galley = & $args[1];
                $submission = & $args[2];
                $submissionNoun = 'preprint';
                $issue = null;
                break;
            default: throw new Exception('Unknown application!');
        }

        if (!$galley) {
            return false;
        }

        $submissionFile = $galley->getFile();
        if ($submissionFile->getData('mimetype') === 'application/pdf') {
            $galleyPublication = null;
            foreach ($submission->getData('publications') as $publication) {
                if ($publication->getId() === $galley->getData('publicationId')) {
                    $galleyPublication = $publication;
                    break;
                }
            }
            $templateMgr = TemplateManager::getManager($request);
            $templateMgr->assign([
                'displayTemplateResource' => $this->getTemplateResource('display.tpl'),
                'pluginUrl' => $request->getBaseUrl() . '/' . $this->getPluginPath(),
                'galleyFile' => $submissionFile,
                'issue' => $issue,
                'submission' => $submission,
                'submissionNoun' => $submissionNoun,
                'bestId' => $submission->getBestId(),
                'galley' => $galley,
                'currentVersionString' => $application->getCurrentVersion()->getVersionString(false),
                'isLatestPublication' => $submission->getData('currentPublicationId') === $galley->getData('publicationId'),
                'galleyPublication' => $galleyPublication,
            ]);
            $templateMgr->display($this->getTemplateResource('submissionGalley.tpl'));
            return true;
        }

        return false;
    }

    /**
     * Callback that renders the issue galley.
     *
     * @param string $hookName
     * @param array $args
     *
     * @return bool
     */
    public function issueCallback($hookName, $args)
    {
        $request = & $args[0];
        $issue = & $args[1];
        $galley = & $args[2];

        $templateMgr = TemplateManager::getManager($request);
        if ($galley && $galley->getFileType() == 'application/pdf') {
            $application = Application::get();
            $templateMgr->assign([
                'displayTemplateResource' => $this->getTemplateResource('display.tpl'),
                'pluginUrl' => $request->getBaseUrl() . '/' . $this->getPluginPath(),
                'galleyFile' => $galley->getFile(),
                'issue' => $issue,
                'galley' => $galley,
                'currentVersionString' => $application->getCurrentVersion()->getVersionString(false),
                'isLatestPublication' => true,
            ]);
            $templateMgr->display($this->getTemplateResource('issueGalley.tpl'));
            return true;
        }

        return false;
    }
}
