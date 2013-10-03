<?php

namespace Rz\MediaBundle\Controller;

use Sonata\AdminBundle\Controller\HelperController as BaseHelperController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Admin\AdminHelper;

class HelperController extends BaseHelperController
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var \Sonata\AdminBundle\Admin\AdminHelper
     */
    protected $helper;

    /**
     * @var \Sonata\AdminBundle\Admin\Pool
     */
    protected $pool;

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \RuntimeException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getShortObjectDescriptionAction(Request $request)
    {
        $code     = $request->get('code');
        $objectId = $request->get('objectId');
        $uniqid   = $request->get('uniqid');

        $admin = $this->pool->getInstance($code);

        if (!$admin) {
            throw new NotFoundHttpException();
        }

        $admin->setRequest($request);

        if ($uniqid) {
            $admin->setUniqid($uniqid);
        }

        $object = $admin->getObject($objectId);

        if (!$object) {
            throw new NotFoundHttpException();
        }

        if ('json' == $request->get('_format')) {
            return new JsonResponse(array('result' => array(
                'id'    => $admin->id($object),
                'label' => $admin->toString($object)
            )));
        } elseif ('html' == $request->get('_format')) {
            return new Response($this->twig->render('RzMediaBundle:Helper:short-object-description.html.twig', array(
                                                                                                       'admin'       => $admin,
                                                                                                       'description' => $admin->toString($object),
                                                                                                       'object'      => $object,
                                                                                                   )));
        } else {
            throw new \RuntimeException('Invalid format');
        }
    }
}
