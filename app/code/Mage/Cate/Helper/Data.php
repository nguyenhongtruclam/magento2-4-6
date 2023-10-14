<?php
/**
 * Copyright © Wiki, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mage\Cate\Helper;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Block\Product\ImageBuilder;

class Data extends AbstractHelper
{
    const ROOT_LEVEL = 1;
    const DEFAULT_LEVEL = 2;
    const DEFAULT_CATEGORY_ID = 2;
    const DEFAULT_LIMIT = 12;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager = null;

    /**
     * @var Config
     */
    protected $catalogConfig;

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var Visibility
     */
    protected $catalogProductVisibility;
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var ImageBuilder
     */
    private $imageBuilder;

    protected $logo;

    /**
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param Config $config
     */
    public function __construct(
        Context                   $context,
        ObjectManagerInterface    $objectManager,
        CategoryCollectionFactory $categoryCollectionFactory,
        CollectionFactory         $productCollectionFactory,
        Visibility                $catalogProductVisibility,
        Config                    $config,
        CategoryFactory           $categoryFactory,
        ImageBuilder              $imageBuilder,
        \Magento\Theme\Block\Html\Header\Logo $logo,
    )
    {
        $this->objectManager = $objectManager;
        $this->catalogConfig = $config;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        $this->imageBuilder = $imageBuilder;
        $this->logo = $logo;
        parent::__construct($context);
    }

    /**
     * @param $parentCategoryId
     * @param $level
     * @param bool $children
     * @param int $limit
     * @return array
     * @throws LocalizedException
     */
    public function getChildren($parentCategoryId, bool $children = true, int $limit = self::DEFAULT_LIMIT): array
    {
        $options = [];
        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');
//        $collection->addAttributeToFilter('level', $level);
        $collection->addAttributeToFilter('parent_id', $parentCategoryId);
        $collection->addAttributeToFilter('is_active', 1);
        $collection->setPageSize($limit);
        $collection->setOrder('position', 'asc');

        foreach ($collection as $category) {
            if ($category->getLevel() > self::ROOT_LEVEL) {
                $options[$category->getId()] = $category;
            }
            if ($children) {
                if ($category->hasChildren()) {
                    $options = array_replace($options, $this->getChildren($category->getId(), $category->getLevel() + 1));
                }
            }
        }

        return $options;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getParentCategories(): array
    {
        return $this->getChildren(self::DEFAULT_CATEGORY_ID, self::DEFAULT_LEVEL, false);
    }

    /**
     * @param $categoryIds
     * @param string $pageSize
     * @return Collection
     */
    public function getCategoryProductByIds($categoryIds, string $pageSize = self::DEFAULT_LIMIT): Collection
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToFilter('type_id', ['in' => ['simple']]);
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        if (count($categoryIds) > 0) {
            $categoryId = end($categoryIds);
            $categoryFilter = ['eq' => [$categoryId]];
            $collection->addCategoriesFilter($categoryFilter);
        }

        $collection = $this->addProductAttributesAndPrices($collection)->addStoreFilter();
        $collection->setPageSize($pageSize);
        $collection->getSelect()->orderRand();

        return $collection;
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    protected function addProductAttributesAndPrices(
        Collection $collection
    ): Collection
    {
        return $collection
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addUrlRewrite();
    }

    /**
     * @param string $name
     * @return string
     */
    public function getCategoryIdByName(string $name): ?string
    {
        try {
            $categoryId = null;
            $collection = $this->categoryFactory->create()->getCollection()
                ->addAttributeToFilter('name', $name)->setPageSize(1);

            if ($collection->getSize()) {
                $categoryId = $collection->getFirstItem()->getId();
            }
            return $categoryId;
        } catch (\Exception $exception) {
            return null;
        }
    }


    public function getLogoSrc()
    {
        return $this->logo->getLogoSrc(); // To get Url of Logo image
    }
}
