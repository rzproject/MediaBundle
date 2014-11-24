<?php

namespace Rz\MediaBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Sonata\MediaBundle\Model\GalleryInterface;
use Sonata\MediaBundle\Model\GalleryHasMediaInterface;

class PrePersistRelationValidator extends ConstraintValidator
{

    /**
     * @param string                          $value
     * @param PasswordRequirements|Constraint $constraint
     */
    public function validate($entity, Constraint $constraint)
    {

        if (null === $entity || '' === $entity) {
            return;
        }

        if(!$entity instanceof GalleryInterface && $entity->getId() ) {
            return;
        }

        if($entity instanceof GalleryInterface) {
            // Check Media
            if($entity->getGalleryHasMedias()->count() > 0) {
                $medias = $entity->getGalleryHasMedias();
                $meds = array();
                $maps = array();
                foreach ($medias as $media) {
                    if($media instanceof GalleryHasMediaInterface && $media->getMedia() != null && $id = $media->getMedia()->getId()) {
                        $meds[] = $id;
                        $maps[$id] = $media->getMedia()->getName();
                    }
                }

                $meds = array_count_values($meds);
                $errors = array();
                foreach($meds as $key=>$value) {
                    if($value > 1) {
                        $errors[] = $maps[$key];
                    }
                }

                if(count($errors) > 0) {
                    if ($this->context instanceof ExecutionContextInterface) {
                        $this->context->buildViolation($constraint->unique)
                            ->setParameter('{{ entity_name }}', 'media')
                            ->setParameter('{{ value }}', implode(", ", $errors))
                            ->atPath('galleryHasMedias')
                            ->addViolation();
                    } else {
                        $this->context->addViolationAt('galleryHasMedias', $constraint->unique, array('{{ entity_name }}' => 'media', '{{ value }}' => implode(", ", $errors)));
                    }

                }
            }

        }

        return;
    }
}
