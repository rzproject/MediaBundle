<?php

namespace Rz\MediaBundle\Controller\Api;

use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Route;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Provider\MediaProviderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Sonata\MediaBundle\Model\Media;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Sonata\MediaBundle\Provider\Pool;

use Sonata\MediaBundle\Controller\Api\MediaController as BaseMediaController;

/**
 * Class MediaController
 *
 * Note: Media is plural, medium is singular (at least according to FOSRestBundle route generator)
 *
 * @package Sonata\MediaBundle\Controller\Api
 *
 * @author Hugo Briand <briand@ekino.com>
 */
class MediaController extends BaseMediaController
{

    /**
     * Retrieves the list of medias (paginated)
     *
     * @ApiDoc(
     *  resource=true,
     *  output={"class"="Rz\MediaBundle\Model\Media", "groups"="sonata_api_read"}
     * )
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page for media list pagination")
     * @QueryParam(name="count", requirements="\d+", default="10", description="Number of medias by page")
     * @QueryParam(name="enabled", requirements="0|1", nullable=true, strict=true, description="Enabled/Disabled medias filter")
     * @QueryParam(name="orderBy", array=true, requirements="ASC|DESC", nullable=true, strict=true, description="Order by array (key is field, value is direction)")
     *
     * @View(serializerGroups="sonata_api_read", serializerEnableMaxDepthChecks=true)
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Media[]
     */
    public function getMediaAction(ParamFetcherInterface $paramFetcher)
    {
        return parent::getMediaAction($paramFetcher);
    }

    /**
     * Retrieves a specific media
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="media id"}
     *  },
     *  output={"class"="Sonata\MediaBundle\Model\Media", "groups"="sonata_api_read"},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when media is not found"
     *  }
     * )
     *
     * @View(serializerGroups="sonata_api_read", serializerEnableMaxDepthChecks=true)
     *
     * @param $id
     *
     * @return Media
     */
    public function getMediumAction($id)
    {
        return $this->getMedium($id);
    }







    /**
     * Updates a medium
     * If you need to upload a file (depends on the provider) you will need to do so by sending content as a multipart/form-data HTTP Request
     * See documentation for more details
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="medium identifier"}
     *  },
     *  input={"class"="sonata_media_api_form_media", "name"="", "groups"={"sonata_api_write"}},
     *  output={"class"="Rz\MediaBundle\Entity\Media", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while medium update",
     *      404="Returned when unable to find medium"
     *  }
     * )
     *
     * @param integer $id      A Medium identifier
     * @param Request $request A Symfony request
     *
     * @return Media
     *
     * @throws NotFoundHttpException
     */
    public function putMediumAction($id, Request $request)
    {
        return parent::putMediumAction($id, $request);
    }

    /**
     * Adds a medium of given provider
     * If you need to upload a file (depends on the provider) you will need to do so by sending content as a multipart/form-data HTTP Request
     * See documentation for more details
     *
     * @ApiDoc(
     *  resource=true,
     *  input={"class"="sonata_media_api_form_media", "name"="", "groups"={"sonata_api_write"}},
     *  output={"class"="Rz\MediaBundle\Entity\Media", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while medium creation",
     *      404="Returned when unable to find medium"
     *  }
     * )
     *
     * @Route(requirements={"provider"="[A-Za-z0-9.]*"})
     *
     * @param string  $provider A media provider
     * @param Request $request A Symfony request
     *
     * @return Media
     *
     * @throws NotFoundHttpException
     */
    public function postProviderMediumAction($provider, Request $request)
    {
        return parent::postProviderMediumAction($provider, $request);
    }
}