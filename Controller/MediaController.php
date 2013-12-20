<?php

namespace Rz\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sonata\MediaBundle\Controller\MediaController as BaseMediaController;

class MediaController extends BaseMediaController
{
    /**
     *
     * @param $galleryId
     * @param string $id
     * @param string $format
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return Response
     */
    public function mediaViewAction($galleryId, $id, $format = 'reference')
    {
        $media = $this->getMedia($id);

        if (!$media) {
            throw new NotFoundHttpException(sprintf('unable to find the media with the id : %s', $id));
        }

        return $this->render('SonataMediaBundle:Media:view.html.twig', array(
            'galleryId' => $galleryId,
            'media'     => $media,
            'formats'   => $this->get('sonata.media.pool')->getFormatNamesByContext($media->getContext()),
            'format'    => $format
        ));
    }
}
