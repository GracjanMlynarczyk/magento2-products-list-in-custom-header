<?php

namespace Ghratzoo\ProductsList\Block;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;

/**
 * Class ProductsList
 * @package Ghratzoo\ProductsList\Block
 */
class ProductsList extends Template
{
    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;


    /**
     * ProductsList constructor.
     * @param Template\Context $context
     * @param CollectionFactory $collectionFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $data = []
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $data);
    }


    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareLayout(): ProductsList
    {
        parent::_prepareLayout();
        if ($this->getProductsCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'custom.products.pager'
            )->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20])
                ->setShowPerPage(true)->setCollection($this->getProductsCollection());
            $this->setChild('pager', $pager);
            $this->getProductsCollection();
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml(): string
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return Collection
     */
    public function getProductsCollection(): Collection
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        //todo
//        $searchCriteria = $this->searchCriteriaBuilder->setPageSize($pageSize)->setCurrentPage($page)->create();
//
//        return $this->productRepository->getList($searchCriteria)->getItems();
        $collection = $this->collectionFactory->create();
        $collection->setCurPage($page);
        $collection->setPageSize($pageSize);
        $collection->addFieldToSelect('*');
        return $collection;
    }
}
