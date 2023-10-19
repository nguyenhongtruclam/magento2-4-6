<?php
/**
 * Copyright © Wiki, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mage\Cate\Block\Widget;

use Magento\Catalog\Model\ResourceModel\AbstractResource;
use Magento\Framework\Exception\NoSuchEntityException;

class AbstractWidget extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Widget\Block\BlockInterface
{
    const TITLE = 'Popular category';


    /**
     * Prepared anchor text
     *
     * @var string
     */
    protected $_anchorText;

    /**
     * Entity model name which must be used to retrieve entity specific data.
     * @var null|AbstractResource
     */
    protected $_entityResource = null;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param ThemeData $themeData
     * @param AbstractResource|null $entityResource
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        AbstractResource                       $entityResource = null
    )
    {
        $this->_entityResource = $entityResource;
        parent::__construct($context);
    }

    /**
     * @return string
     * @throws NoSuchEntityException
     */
    public function getTitle()
    {
        return $this->getLabel();
    }

    /**
     * Prepare label using passed text as parameter.
     * If anchor text was not specified get entity name from DB.
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getLabel()
    {
        $auchorText = $this->escapeHtml($this->getData('anchor_text'));
        if (!$this->_anchorText) {
            if ($auchorText) {
                $this->_anchorText = $auchorText;
            } elseif ($this->_entityResource) {
                $idPath = explode('/', $this->_getData('id_path'));
                if (isset($idPath[1])) {
                    $id = $idPath[1];
                    if ($id) {
                        $this->_anchorText = $this->_entityResource->getAttributeRawValue(
                            $id,
                            'name',
                            $this->_storeManager->getStore()
                        );
                    }
                }
            }
        }

        return $this->_anchorText;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        if ($this->getData('id_path')) {
            return $this->getData('id_path');
        }
        return null;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        $limit = (int)$this->escapeHtml($this->_getData('limit'));

        if ($limit) {
            return $limit;
        }
        return \Mage\Cate\Helper\Data::DEFAULT_LIMIT;
    }
}
