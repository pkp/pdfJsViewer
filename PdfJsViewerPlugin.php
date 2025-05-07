<?php

/**
 * @file plugins/generic/pdfJsViewer/PdfJsViewerPlugin.php
 *
 * Copyright (c) 2013-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class PdfJsViewerPlugin
 *
 * @brief This plugin enables embedding of the pdf.js viewer for PDF display
 */

namespace APP\plugins\generic\pdfJsViewer;

use APP\core\Application;
use APP\template\TemplateManager;
use Exception;
use PKP\plugins\Hook;

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
                Hook::add('PreprintHandler::view::galley', $this->submissionCallback(...), Hook::SEQUENCE_LAST);
                // For OJS
                Hook::add('ArticleHandler::view::galley', $this->submissionCallback(...), Hook::SEQUENCE_LAST);
                Hook::add('IssueHandler::view::galley', $this->issueCallback(...), Hook::SEQUENCE_LAST);
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
        $request = &$args[0];
        $application = Application::get();
        switch ($application->getName()) {
            case 'ojs2':
                $issue = &$args[1];
                $galley = &$args[2];
                $submission = &$args[3];
                $submissionNoun = 'article';
                break;
            case 'ops':
                $galley = &$args[1];
                $submission = &$args[2];
                $submissionNoun = 'preprint';
                $issue = null;
                break;
            default: throw new Exception('Unknown application!');
        }

        if ($galley && $galley->getFileType() === 'application/pdf') {
            $galleyPublication = null;
            foreach ($submission->getData('publications') as $publication) {
                if ($publication->getId() === $galley->getData('publicationId')) {
                    $galleyPublication = $publication;
                    break;
                }
            }
            $templateMgr = TemplateManager::getManager($request);

            if ($galleyPublication) {
                $title = $galleyPublication->getLocalizedTitle(null, 'html');
            }

            $pdfUrl = $request->url(
                null,
                $submissionNoun,
                'download',
                [$submission->getBestId(), $galley->getBestGalleyId(), $galley->getFile()->getId()]
            );

            $parentUrl = $request->url(null, $submissionNoun, 'view', [$submission->getBestId()]);

            $galleyTitle = __('submission.representationOfTitle', [
                'representation' => $galley->getLabel(),
                'title' => $galleyPublication->getLocalizedFullTitle(),
            ]);

            $datePublished = __('submission.outdatedVersion', [
                'datePublished' => $galleyPublication->getData('datePublished'),
                'urlRecentVersion' => $parentUrl,
            ]);

            $templateMgr->assign([
                'displayTemplateResource' => $this->getTemplateResource('display.tpl'),
                'pluginUrl' => $request->getBaseUrl() . '/' . $this->getPluginPath(),
                'galleyFile' => $galley->getFile(),
                'issue' => $issue,
                'submission' => $submission,
                'submissionNoun' => $submissionNoun,
                'bestId' => $galleyPublication->getData('urlPath') ?? $submission->getId(),
                'galley' => $galley,
                'currentVersionString' => $application->getCurrentVersion()->getVersionString(false),
                'isLatestPublication' => $submission->getData('currentPublicationId') === $galley->getData('publicationId'),
                'galleyPublication' => $galleyPublication,
                'title' => $title,
                'pdfUrl' => $pdfUrl,
                'parentUrl' => $parentUrl,
                'galleyTitle' => $galleyTitle,
                'datePublished' => $datePublished,
                'isTitleHtml' => true,
            ]);

            $templateMgr->display($this->getTemplateResource('display.tpl'));

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
        $request = &$args[0];
        $issue = &$args[1];
        $galley = &$args[2];

        if ($galley && $galley->getFileType() === 'application/pdf') {
            $templateMgr = TemplateManager::getManager($request);
            $application = Application::get();

            $pdfUrl = $request->url(
                null,
                'issue',
                'download',
                [$issue->getBestIssueId(), $galley->getBestGalleyId()]
            );

            $parentUrl = $request->url(null, 'issue', 'view', [$issue->getBestIssueId()]);

            $galleyTitle = __('submission.representationOfTitle', [
                'representation' => $galley->getLabel(),
                'title' => $issue->getIssueIdentification(),
            ]);

            $datePublished = __('submission.outdatedVersion', [
                'datePublished' => $issue->getData('datePublished'),
                'urlRecentVersion' => $parentUrl,
            ]);

            $title = $issue->getIssueIdentification();

            $templateMgr->assign([
                'displayTemplateResource' => $this->getTemplateResource('display.tpl'),
                'pluginUrl' => $request->getBaseUrl() . '/' . $this->getPluginPath(),
                'galleyFile' => $galley->getFile(),
                'issue' => $issue,
                'galley' => $galley,
                'currentVersionString' => $application->getCurrentVersion()->getVersionString(false),
                'isLatestPublication' => true,
                'pdfUrl' => $pdfUrl,
                'parentUrl' => $parentUrl,
                'galleyTitle' => $galleyTitle,
                'datePublished' => $datePublished,
                'title' => $title,
                'isTitleHtml' => false,
            ]);

            $templateMgr->display($this->getTemplateResource('display.tpl'));

            return true;
        }

        return false;
    }
}
