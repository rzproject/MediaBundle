<?php

/*
 * This file is part of the RzMediaBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\MediaBundle\Provider;

  
use Sonata\MediaBundle\Provider\YouTubeProvider as BaseYoutubeVideoProvider;
use Sonata\MediaBundle\Model\MediaInterface;

class YouTubeProvider extends BaseYoutubeVideoProvider
{ 
    /**
     * {@inheritdoc}
     */
    public function updateMetadata(MediaInterface $media, $force = false)
    {
        $url       = sprintf('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=%s&format=json', $media->getProviderReference());
	$maxResUrl = sprintf("https://i.ytimg.com/vi/%s/maxresdefault.jpg",$media->getProviderReference()); 

        try {
            $metadata		    = $this->getMetadata($media, $url); 
	    $metadata['maxres_url'] = $maxResUrl; 
        } catch (\RuntimeException $e) {
            $media->setEnabled(false);
            $media->setProviderStatus(MediaInterface::STATUS_ERROR);

            return;
        }

        $media->setProviderMetadata($metadata);

        if ($force) {
            $media->setName($metadata['title']);
            $media->setAuthorName($metadata['author_name']);
        }

        $media->setHeight($metadata['height']);
        $media->setWidth($metadata['width']);
        $media->setContentType('video/x-flv');
    } 
	
	/**
     * {@inheritdoc}
     */
    public function getReferenceImage(MediaInterface $media, $url = "thumbnail_url")
    {   
        return $media->getMetadataValue($url);
    }
	
	 /**
     * {@inheritdoc}
     */
    public function getReferenceFile(MediaInterface $media)
    {  
        $key = $this->generatePrivateUrl($media, 'reference');

        // the reference file is remote, get it and store it with the 'reference' format
        if ($this->getFilesystem()->has($key)) {
            $referenceFile = $this->getFilesystem()->get($key);
        } else {
            $referenceFile = $this->getFilesystem()->get($key, true);
            $metadata = $this->metadata ? $this->metadata->get($media, $referenceFile->getName()) : array();
			
			//get HD first 
			$highestRes = $this->fetchHighestRes($media->getProviderReference());
			if($highestRes){ 
				$mediaData = $this->browser->get($this->getReferenceImage($media,'maxres_url'))->getContent(); 
			}else{
				$mediaData = $this->browser->get($this->getReferenceImage($media))->getContent(); 
			}
			
			$referenceFile->setContent($mediaData, $metadata);
        }

        return $referenceFile;
    }  	
	
	 /**
     * {@inheritdoc}
     */
	protected function fetchHighestRes($videoid) {    
		$imgUrl = sprintf("https://i.ytimg.com/vi/%s/maxresdefault.jpg",$videoid);
		if(@getimagesize($imgUrl)){
			return $imgUrl;
		}
		
		return null;
	}
	
}
