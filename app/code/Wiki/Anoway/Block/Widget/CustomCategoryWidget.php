<?php
namespace Wiki\Anoway\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class CustomCategoryWidget extends Template implements BlockInterface
{
    protected $categoryCollectionFactory;

    protected $_template = "";

    public function __construct(
        Template\Context $context,
        CategoryCollectionFactory $categoryCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    public function handleData(){
        $data = $this->getData();
    }

    public function getCategoryCollection()
    {
        $data = $this->getData();
        return $this->categoryCollectionFactory->create()->addAttributeToSelect('name');
    }
}