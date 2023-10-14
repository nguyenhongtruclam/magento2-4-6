<?php
/**
 * Copyright © Wiki, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mage\Cate\Block;

use Magento\Framework\Exception\LocalizedException;

class Categories extends \Mage\Cate\Block\Widget\AbstractWidget
{
    /**
     * @var string
     */
    protected $_template = "categories_list_with_image.phtml";

    /**
     * @throws LocalizedException
     */
    public function getChildrenCategories(): array
    {
        return $this->themeData->getChildren($this->getCategoryId(), false, $this->getLimit());
    }

}