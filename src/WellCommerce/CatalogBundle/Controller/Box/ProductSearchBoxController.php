<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\CatalogBundle\Controller\Box;

use WellCommerce\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\CoreBundle\Controller\Box\BoxControllerInterface;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;
use WellCommerce\LayoutBundle\Collection\LayoutBoxSettingsCollection;

/**
 * Class ProductSearchBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductSearchBoxController extends AbstractBoxController implements BoxControllerInterface
{
    /**
     * @var \WellCommerce\CatalogBundle\Manager\Front\ProductSearchManager
     */
    protected $manager;

    /**
     * {@inheritdoc}
     */
    public function indexAction(LayoutBoxSettingsCollection $boxSettings)
    {
        $dataset       = $this->get('product_search.dataset.front');
        $conditions    = new ConditionsCollection();
        $requestHelper = $this->getRequestHelper();
        $limit         = $this->manager->getRequestHelper()->getAttributesBagParam('limit', $boxSettings->getParam('per_page', 12));
        $conditions    = $this->manager->addSearchConditions($conditions);
        $conditions    = $this->getLayeredNavigationHelper()->addLayeredNavigationConditions($conditions);

        $products = $dataset->getResult('array', [
            'limit'      => $limit,
            'page'       => $requestHelper->getAttributesBagParam('page', 1),
            'order_by'   => $requestHelper->getAttributesBagParam('orderBy', 'score'),
            'order_dir'  => $requestHelper->getAttributesBagParam('orderDir', 'asc'),
            'conditions' => $conditions,
        ]);

        return $this->displayTemplate('index', [
            'dataset' => $products,
        ]);
    }
}
