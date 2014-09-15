<?php
/*
* This file is part of the RzMediaBundle package.
*
* (c) mell m. zamora <mell@rzproject.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Rz\MediaBundle\Admin;

use Sonata\AdminBundle\Admin\AdminExtension;

class MediaAdminExtension extends AdminExtension
{
    /**
     * {@inheritDoc}
     */
    public function configureRoutes(\Sonata\AdminBundle\Admin\AdminInterface $admin, \Sonata\AdminBundle\Route\RouteCollection $collection)
    {
        $collection->add('browser', 'browser');
        $collection->add('upload', 'upload');
    }
}