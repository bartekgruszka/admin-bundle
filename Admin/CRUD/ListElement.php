<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;

interface ListElement extends ElementInterface, DataSourceAwareInterface, DataGridAwareInterface
{
    /**
     * @return \FSi\Component\DataGrid\DataGrid|null
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function createDataGrid();

    /**
     * @return \FSi\Component\DataSource\DataSource|null
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function createDataSource();

    /**
     * This method should be used inside of admin objects to retrieve DataIndexerInterface.
     *
     * @return \FSi\Component\DataIndexer\DataIndexerInterface
     */
    public function getDataIndexer();

    /**
     * Method called after DataGrid update at listAction in CRUDController.
     * Mostly it should only call flush at ObjectManager.
     *
     * @return mixed
     */
    public function saveDataGrid();
}
