<?php

namespace Rz\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GalleryController extends Controller
{
    /**
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     */
    public function indexAction()
    {
        return $this->renderGalleries(array(), array(), 10);
    }

    /**
     *
     * @param $page
     *
     * @return Response
     */
    public function galleryPageAction($page)
    {
        return $this->renderGalleries(array(), array('page'=>$page), 10);
    }


    /**
     * @param array $criteria
     * @param array $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderGalleries(array $criteria = array(), array $parameters = array(), $maxPerPage = 10)
    {
        $page = array_key_exists('page', $parameters) ? $parameters['page'] : 1;

        $pager = $this->get('sonata.media.manager.gallery')->getGalleries(array('enabled' => true), $page, 1);

//        $pager->setMaxPerPage($maxPerPage);
//        $pager->setCurrentPage($page, false, true);

        return $this->render('RzMediaBundle:Gallery:index.html.twig', array(
            'pager'   => $pager,
        ));
    }

    /**
     * @param string $id
     *
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function viewAction($id)
    {
        $gallery = $this->get('sonata.media.manager.gallery')->findOneBy(array(
            'id'      => $id,
            'enabled' => true
        ));

        if (!$gallery) {
            throw new NotFoundHttpException('unable to find the gallery with the id');
        }

        return $this->render('RzMediaBundle:Gallery:view.html.twig', array(
            'gallery'   => $gallery,
        ));
    }
}
