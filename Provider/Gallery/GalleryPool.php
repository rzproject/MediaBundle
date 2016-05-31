<?php

namespace Rz\MediaBundle\Provider\Gallery;

use Sonata\CoreBundle\Validator\ErrorElement;

class GalleryPool extends Pool
{

    /**
     * @param string $name
     * @param array $provider
     * @param null $defaultTemplate
     * @param array $templates
     *
     * @return void
     */
    public function addCollection($name, $provider = null, $mediaLookupContext = null, $hideContext = null, $mediaLookupCategory = null)
    {
        if($this->slugify) {
            $name = $this->slugify->slugify($name);
        }

        if (!$this->hasGroup($name)) {
            $this->groups[$name] = array('provider' => null);
        }

        $this->groups[$name]['provider'] = $provider;
        if($mediaLookupContext) {
            $this->groups[$name]['media_lookup_context'] = $mediaLookupContext;
        }

        if(isset($hideContext)) {
            $this->groups[$name]['media_lookup_hide_context'] = $hideContext;
        }

        if($mediaLookupCategory) {
            $this->groups[$name]['media_lookup_category'] = $mediaLookupCategory;
        }
    }

    public function getMediaLookupContextByCollection($name)
    {
        $group = $this->getGroup($name);

        if (!$group) {
            return null;
        }

        return $group['media_lookup_context'];
    }

    public function getMediaLookupHideContextByCollection($name)
    {
        $group = $this->getGroup($name);

        if (!$group) {
            return null;
        }

        return $group['media_lookup_hide_context'];
    }

    public function getMediaLookupCategoryByCollection($name)
    {
        $group = $this->getGroup($name);

        if (!$group) {
            return null;
        }

        return $group['media_lookup_category'];
    }
}