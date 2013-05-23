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

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\DependencyInjection\ContainerAware,
    Symfony\Component\Security\Core\SecurityContext,
    Symfony\Component\HttpFoundation\JsonResponse;

use FOS\RestBundle\Controller\FOSRestController;

class MediaServiceRestController extends FOSRestController
{
    protected $admin;

    /**
     * fetch all media
     */
    public function getMediaAction($context, $format = 'json')
    {
        $this->getAdmin();
        $view = $this->fetchMedia($context, 'small');

        return $view;
    }

    protected function fetchMedia($context = null, $format = 'small')
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $mediaService = $this->container->get('rz_media.manager.media');
        $pool = $this->container->get('sonata.media.pool');

        $medias = $mediaService->fetchMedia();
        //$contexts = $mediaService->getPool()->getContexts();

        $result = array();
        //TODO: create cached copy & support different format
        foreach ($medias as $media) {
              $provider = $pool->getProvider($media->getProviderName());
              array_push($result, array('thumb'=>$provider->generatePublicUrl($media, 'admin'),
                                        'image'=>$provider->generatePublicUrl($media,  $provider->getFormatName($media, 'small')),
                                        'folder'=>$media->getProviderName()));
        }

        return new JsonResponse($result);
    }

    public function getAdmin()
    {
        $this->admin = $this->container->get('sonata.media.admin.media');
    }

    /**
     * Returns the correct RESTful verb, given either by the request itself or
     * via the "_method" parameter.
     *
     * @return string HTTP method, either
     */
    protected function getRestMethod()
    {
        $request = $this->getRequest();
        if (Request::getHttpMethodParameterOverride() || !$request->request->has('_method')) {
            return $request->getMethod();
        }

        return $request->request->get('_method');
    }
}
