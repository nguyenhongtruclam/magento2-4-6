<?php
/**
 * Copyright © Wiki, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mage\Cate\Block;

use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class Categories extends \Mage\Cate\Block\Widget\AbstractWidget
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $_categoryCollection;
    /**
     * @var string
     */
    protected $_template = "categories_list_with_image.phtml";

    

    public function __construct(
        CategoryCollectionFactory $categoryCollection,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\AbstractResource $entityResource = null
    )
    {
        $this->_categoryCollection = $categoryCollection;
        parent::__construct($context, $entityResource);
    }
    
    /**
     * @throws LocalizedException
     */
    public function collectionCategory()
    {
        $categoryCollection = $this->_categoryCollection->create()
            ->addAttributeToSelect('*')
            ->addFieldtoFilter('entity_id', array('eq', explode(',', $this->getCategoryId())));
        return $categoryCollection;
    }
}