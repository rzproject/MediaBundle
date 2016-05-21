<?php

namespace Rz\MediaBundle\Controller;

use Sonata\MediaBundle\Controller\GalleryAdminController as Controller;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;


class GalleryAdminController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request = null)
    {

        $this->admin->checkAccess('list');

        $preResponse = $this->preList($request);
        if ($preResponse !== null) {
            return $preResponse;
        }

        if ($listMode = $request->get('_list_mode', 'mosaic')) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();

        $filters = $request->get('filter');

        // set the default context
        if (!$filters || !array_key_exists('context', $filters) || !$filters['context']['value']) {
            $context = $this->admin->getPersistentParameter('context',  $this->get('sonata.media.pool')->getDefaultContext());
        } else {
            $context = $filters['context']['value'];
        }

        $context = $this->get('sonata.classification.manager.context')->findOneBy(array('id'=>$context));

        if(!$context) {
            throw new \Exception('Context should be defined');
        }

        $datagrid->setValue('context', null, $context->getId());


        $collectiontManager = $this->get('sonata.classification.manager.collection');
        $currentCollection = null;

        $galleryContext = $this->container->getParameter('rz.media.gellery.context');

        if ($collection = $request->get('collection')) {
            $currentCollection = $collectiontManager->findOneBy(array('slug'=>$collection, 'context'=>$galleryContext));
        } else {
            $currentCollection = $collectiontManager->findOneBy(array('context'=>$galleryContext));
        }

        $collections = $collectiontManager->findBy(array('context'=>$galleryContext));

        if(count($collections)>0) {

            if (!$currentCollection) {
                $currentCollection = current(array_shift($collections));
            }

            if ($this->admin->getPersistentParameter('collection')) {
                $collection = $collectiontManager->findOneBy(array('slug'=>$this->admin->getPersistentParameter('collection')));
                $datagrid->setValue('collection', null, $collection->getId());
            } else {
                $datagrid->setValue('collection', null, $currentCollection->getId());
            }
        }

        $formView = $datagrid->getForm()->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());

        return $this->render($this->admin->getTemplate('list'), array(
            'action'                => 'list',
            'current_collection'    => $currentCollection,
            'collections'           => $collections,
            'context'               => $context,
            'form'                  => $formView,
            'datagrid'              => $datagrid,
            'csrf_token'            => $this->getCsrfToken('sonata.batch'),
        ), null, $request);
    }
}
