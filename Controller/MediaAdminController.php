<?php

/*
 * This file is part of the RzMediaBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\MediaBundle\Controller;

use Sonata\MediaBundle\Controller\MediaAdminController as Controller;
use Symfony\Component\HttpFoundation\Request;

class MediaAdminController extends Controller
{


    /**
     * return the Response object associated to the list action
     *
     * @param Request $request
     * @return Response
     * @throws AccessDeniedException
     */
    public function listAction(Request $request = null)
    {

        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        if ($listMode = $request->get('_list_mode')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();

        $filters = $request->get('filter');

        // set the default context
        if (!$filters) {
            $context = $this->admin->getPersistentParameter('context',  $this->get('sonata.media.pool')->getDefaultContext());
        } else {
            $context = $filters['context']['value'];
        }

        $datagrid->setValue('context', null, $context);

        // retrieve the main category for the tree view
        $category = $this->container->get('sonata.classification.manager.category')->getRootCategory($context);

        if ($request->get('category')) {
            $contextInCategory = $this->container->get('sonata.classification.manager.category')->findBy(array(
                'id'      => (int) $request->get('category'),
                'context' => $context
            ));
            if (!empty($contextInCategory)) {
                $datagrid->setValue('category', null, $request->get('category'));
            } else {
                $datagrid->setValue('category', null, $category->getId());
            }
        }

        if (!$this->getRequest()->get('filter') && $this->admin->getPersistentParameter('provider')) {
            $datagrid->setValue('providerName', null, $this->admin->getPersistentParameter('provider'));
        }

        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
            'action'        => 'list',
            'form'          => $formView,
            'datagrid'      => $datagrid,
            'root_category' => $category,
            'csrf_token'    => $this->getCsrfToken('sonata.batch'),
        ));
    }

    /**
     * Gets a template
     *
     * @param  string $name
     * @return string
     */
    private function getTemplate($name)
    {
        $templates = $this->container->getParameter('rz_media.configuration.templates');

        if (isset($templates[$name])) {
            return $templates[$name];
        }

        return null;
    }

    /**
     * Returns the response object associated with the browser action
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     */
    public function browserAction(Request $request = null)
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $datagrid = $this->admin->getDatagrid();
        $datagrid->setValue('context', null, $this->admin->getPersistentParameter('context'));
        $datagrid->setValue('providerName', null, $this->admin->getPersistentParameter('provider'));

        // Store formats
        $formats = array();
        foreach ($datagrid->getResults() as $media) {
            $formats[$media->getId()] = $this->get('sonata.media.pool')->getFormatNamesByContext($media->getContext());
        }

        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->getTemplate('browser'), array(
            'action' => 'browser',
            'form' => $formView,
            'datagrid' => $datagrid,
            'formats' => $formats,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
            'browser_inner_list_row' => $this->getTemplate('browser_inner_list_row')
        ));
    }

    /**
     * Returns the response object associated with the upload action
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     */
    public function uploadAction(Request $request = null)
    {
        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $mediaManager = $this->get('sonata.media.manager.media');

        $provider = $request->get('provider');
        $file = $request->files->get('upload');

        if (!$request->isMethod('POST') || !$provider || null === $file) {
            throw $this->createNotFoundException();
        }

        $context = $request->get('context', $this->get('sonata.media.pool')->getDefaultContext());

        $media = $mediaManager->create();
        $media->setBinaryContent($file);

        $mediaManager->save($media, $context, $provider);
        $this->admin->createObjectSecurity($media);

        return $this->render($this->getTemplate('upload'), array(
            'action' => 'list',
            'object' => $media
        ));
    }

}
