<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface Element extends CRUDInterface, DoctrineAwareInterface
{
    /**
     * Class name that represent entity. It might be returned in Symfony2 style:
     * FSiDemoBundle:News
     * or as a full class name
     * \FSi\Bundle\DemoBundle\Entity\News
     *
     * @return string
     */
    public function getClassName();

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function getObjectManager();

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository();

    /**
     * @param ManagerRegistry $registry
     * @return mixed
     */
    public function setManagerRegistry(ManagerRegistry $registry);
}
