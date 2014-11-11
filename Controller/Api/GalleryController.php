<?php

namespace Rz\MediaBundle\Controller\Api;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Sonata\MediaBundle\Model\Gallery;
use Sonata\MediaBundle\Model\GalleryHasMedia;
use Sonata\MediaBundle\Model\GalleryHasMediaInterface;
use Sonata\MediaBundle\Model\GalleryInterface;
use Sonata\MediaBundle\Model\GalleryManagerInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Sonata\MediaBundle\Model\MediaManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View as FOSRestView;

use Sonata\MediaBundle\Controller\Api\GalleryController as BaseGalleryController;

/**
 * Class GalleryController
 *
 * @package Sonata\MediaBundle\Controller\Api
 *
 * @author Hugo Briand <briand@ekino.com>
 */
class GalleryController extends BaseGalleryController
{
    /**
     * Retrieves the list of galleries (paginated)
     *
     * @ApiDoc(
     *  resource=true,
     *  output={"class"="Rz\MediaBundle\Model\Gallery", "groups"="sonata_api_read"}
     * )
     *
     * @QueryParam(name="page", requirements="\d+", default="1", description="Page for gallery list pagination")
     * @QueryParam(name="count", requirements="\d+", default="10", description="Number of galleries by page")
     * @QueryParam(name="enabled", requirements="0|1", nullable=true, strict=true, description="Enabled/Disabled galleries filter")
     * @QueryParam(name="orderBy", array=true, requirements="ASC|DESC", nullable=true, strict=true, description="Order by array (key is field, value is direction)")
     *
     * @View(serializerGroups="sonata_api_read", serializerEnableMaxDepthChecks=true)
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Gallery[]
     */
    public function getGalleriesAction(ParamFetcherInterface $paramFetcher)
    {
        return parent::getGalleriesAction($paramFetcher);
    }

    /**
     * Retrieves a specific gallery
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="gallery id"}
     *  },
     *  output={"class"="Rz\MediaBundle\Entity\Gallery", "groups"="sonata_api_read"},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when gallery is not found"
     *  }
     * )
     *
     * @View(serializerGroups="sonata_api_read", serializerEnableMaxDepthChecks=true)
     *
     * @param $id
     *
     * @return Gallery
     */
    public function getGalleryAction($id)
    {
        return parent::getGalleryAction($id);
    }

    /**
     * Retrieves the medias of specified gallery
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="gallery id"}
     *  },
     *  output={"class"="Rz\MediaBundle\Entity\Media", "groups"="sonata_api_read"},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when gallery is not found"
     *  }
     * )
     *
     * @View(serializerGroups="sonata_api_read", serializerEnableMaxDepthChecks=true)
     *
     * @param $id
     *
     * @return Media[]
     */
    public function getGalleryMediasAction($id)
    {
        return parent::getGalleryMediasAction($id);
    }

    /**
     * Adds a gallery
     *
     * @ApiDoc(
     *  input={"class"="sonata_media_api_form_gallery", "name"="", "groups"={"sonata_api_write"}},
     *  output={"class"="Rz\MediaBundle\Entity\Gallery", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while gallery creation",
     *  }
     * )
     *
     * @param Request $request A Symfony request
     *
     * @return GalleryInterface
     *
     * @throws NotFoundHttpException
     */
    public function postGalleryAction(Request $request)
    {
        return parent::postGalleryAction($request);
    }

    /**
     * Updates a gallery
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="id", "dataType"="integer", "requirement"="\d+", "description"="gallery identifier"}
     *  },
     *  input={"class"="sonata_media_api_form_gallery", "name"="", "groups"={"sonata_api_write"}},
     *  output={"class"="Rz\MediaBundle\Entity\Gallery", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while gallery creation",
     *      404="Returned when unable to find gallery"
     *  }
     * )
     *
     * @param int     $id      User id
     * @param Request $request A Symfony request
     *
     * @return GalleryInterface
     *
     * @throws NotFoundHttpException
     */
    public function putGalleryAction($id, Request $request)
    {
        return parent::putGalleryAction($id, $request);
    }

    /**
     * Adds a media to a gallery
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="galleryId", "dataType"="integer", "requirement"="\d+", "description"="gallery identifier"},
     *      {"name"="mediaId", "dataType"="integer", "requirement"="\d+", "description"="media identifier"}
     *  },
     *  input={"class"="sonata_media_api_form_gallery_has_media", "name"="", "groups"={"sonata_api_write"}},
     *  output={"class"="Rz\MediaBundle\Entity\Gallery", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      400="Returned when an error has occurred while gallery/media attachment",
     *  }
     * )
     *
     * @param integer $galleryId A gallery identifier
     * @param integer $mediaId   A media identifier
     *
     * @param Request $request A Symfony request

     * @return GalleryInterface
     *
     * @throws NotFoundHttpException
     */
    public function postGalleryMediaGalleryhasmediaAction($galleryId, $mediaId, Request $request)
    {
        return parent::postGalleryMediaGalleryhasmediaAction($galleryId, $mediaId, $request);
    }

    /**
     * Updates a media to a gallery
     *
     * @ApiDoc(
     *  requirements={
     *      {"name"="galleryId", "dataType"="integer", "requirement"="\d+", "description"="gallery identifier"},
     *      {"name"="mediaId", "dataType"="integer", "requirement"="\d+", "description"="media identifier"}
     *  },
     *  input={"class"="sonata_media_api_form_gallery_has_media", "name"="", "groups"={"sonata_api_write"}},
     *  output={"class"="Rz\MediaBundle\Entity\Gallery", "groups"={"sonata_api_read"}},
     *  statusCodes={
     *      200="Returned when successful",
     *      404="Returned when an error if media cannot be found in gallery",
     *  }
     * )
     *
     * @param integer $galleryId A gallery identifier
     * @param integer $mediaId   A media identifier
     *
     * @param Request $request A Symfony request

     * @return GalleryInterface
     *
     * @throws NotFoundHttpException
     */
    public function putGalleryMediaGalleryhasmediaAction($galleryId, $mediaId, Request $request)
    {
        return parent::putGalleryMediaGalleryhasmediaAction($galleryId, $mediaId, $request);
    }
}
