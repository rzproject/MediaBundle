<?php

/*
 * This file is part of the RzMediaBundle package.
 *
 * (c) mell m. zamora <mell@rzproject.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rz\MediaBundle\Entity;

use Sonata\MediaBundle\Entity\GalleryManager as BaseGalleryManager;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr;

use Sonata\ClassificationBundle\Model\CollectionInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Sonata\DoctrineORMAdminBundle\Datagrid\Pager;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;


class GalleryManager extends BaseGalleryManager
{
    public function getGalleries(array $criteria, $page = 0, $maxPerPage=10)
    {
        if (!isset($criteria['enabled'])) {
            $criteria['enabled'] = true;
        }

        $parameters = array();
        $query = $this->getRepository()
                      ->createQueryBuilder('g')
                      ->select('g')
                      ->orderby('g.updatedAt', 'DESC');



        if ($criteria['enabled'] == true) {
            // enabled
            $criteria['enabled'] = isset($criteria['enabled']) ? $criteria['enabled'] : true;
            $query->andWhere('g.enabled = :enabled');
            $parameters['enabled'] = $criteria['enabled'];
        }

        $query->setParameters($parameters);

        try {
            return new Pagerfanta(new DoctrineORMAdapter($query));
        } catch (NoResultException $e) {
            return null;
        }

//        $pager = new Pager();
//        $pager->setMaxPerPage($maxPerPage);
//        $pager->setQuery(new ProxyQuery($query));
//        $pager->setPage($page);
//        $pager->init();
//
//        return $pager;
    }
}
