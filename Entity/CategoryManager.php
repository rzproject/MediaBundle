<?php

namespace Rz\MediaBundle\Entity;

use Rz\MediaBundle\Model\CategoryManager as ModelCategoryManager;
use Rz\MediaBundle\Model\CategoryInterface;

use Doctrine\ORM\EntityManager;

class CategoryManager extends ModelCategoryManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param string                      $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em    = $em;
        $this->class = $class;
    }

    /**
     * {@inheritDoc}
     */
    public function save(CategoryInterface $category)
    {
        $this->em->persist($category);
        $this->em->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function findOneBy(array $criteria)
    {
        return $this->em->getRepository($this->class)->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria)
    {
        return $this->em->getRepository($this->class)->findBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(CategoryInterface $category)
    {
        $this->em->remove($category);
        $this->em->flush();
    }
}
