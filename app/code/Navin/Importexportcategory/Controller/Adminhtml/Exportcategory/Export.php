<?php
/**
* Navin Bhudiya
* Copyright (C) 2016 Navin Bhudiya <navindbhudiya@gmail.com>
*
* NOTICE OF LICENSE
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html.
*
* @category Navin
* @package Navin_Importexportcategory
* @copyright Copyright (c) 2016 Mage Delight (http://www.navinbhudiya.com/)
* @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
* @author Navin Bhudiya <navindbhudiya@gmail.com>
*/
namespace Navin\Importexportcategory\Controller\Adminhtml\Exportcategory;

class Export extends \Magento\Backend\App\Action
{
    /**
     * Redirect result factory
     *
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $_resultForwardFactory;

    /**
     * constructor
     *
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $prodcollection,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
    
        $this->_resultForwardFactory = $resultForwardFactory;
        $this->_storeManager = $storeManagerInterface;
        $this->_categoryFactory = $categoryFactory;
        $this->_productcollection = $prodcollection;
        $this->resultRawFactory = $resultRawFactory;
        $this->fileFactory  = $fileFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $store_id = $this->getRequest()->getPost('store_id');
        $singlestoremode = $this->_storeManager->isSingleStoreMode();
        $_stores = [];
        if (!$singlestoremode) {
            $stores = $this->_storeManager->getStores();
            foreach ($stores as $key => $store) {
                $_stores[$store->getId()] = $store->getCode();
            }
        }
         $fileName = 'categories.csv';
        $content = '"category_id","parent_id"';
        $content .= ',"store"';
        $content .= ',"name","path","image","is_active","is_anchor","include_in_menu","meta_title","meta_keywords","meta_description","display_mode","custom_use_parent_settings","custom_apply_to_products","custom_design","custom_design_from","custom_design_to","default_sort_by","page_layout","description","products"'."\n";
        $collection = $this->_categoryFactory->create()->getCollection()->addAttributeToSort('entity_id', 'asc');
        
        foreach ($collection as $key => $cat) {
            $categoryitem = $this->_categoryFactory->create();
            if ($cat->getId()>=2) {
                $categoryitem->setStoreId($store_id);
                $categoryitem->load($cat->getId());
                if ($categoryitem->getId()) {
                    $prodids = '';
                    $productids = $this->_productcollection->addCategoryFilter($categoryitem)->getAllIds();
                    if (isset($productids) && !empty($productids)) {
                        $prodids = $productids = implode('|', $productids);
                    }
                    $content .= '"'.$categoryitem->getId().'","'.$categoryitem->getParentId().'","';
                    $content .= $_stores[$categoryitem->getStoreId()].'","';
                    $content .= $categoryitem->getName().'","'.$categoryitem->getPath().'","'.$categoryitem->getImage().'","'.$categoryitem->getIsActive().'","'.$categoryitem->getIsAnchor().'","'.$categoryitem->getIncludeInMenu().'","'.$categoryitem->getMetaTitle().'","'.$categoryitem->getMetaKeywords().'","'.$categoryitem->getMetaDescription().'","'.$categoryitem->getDisplayMode().'","'.$categoryitem->getCustomUseParentSettings().'","'.$categoryitem->getCustomApplyToProducts().'","'.$categoryitem->getCustomDesign().'","'.$categoryitem->getCustomDesignFrom().'","'.$categoryitem->getCustomDesignTo().'","'.$categoryitem->getDefaultSortBy().'","'.$categoryitem->getPageLayout().'","'.$categoryitem->getDescription().'","'.$prodids.'"'."\n";
                }
            }
        }
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function _prepareDownloadResponse($name, $content)
    {
        $fileName = $name;
        $this->fileFactory->create(
            $fileName,
            $content,
            'var',
            'text/csv', 
            strlen($content)
        );
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw;
    }
}
