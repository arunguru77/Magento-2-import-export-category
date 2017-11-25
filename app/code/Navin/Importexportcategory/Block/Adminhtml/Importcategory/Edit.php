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
namespace Navin\Importexportcategory\Block\Adminhtml\Importcategory;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * constructor
     *
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
    
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize Test edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'importexport_id';
        $this->_blockGroup = 'Navin_Importexportcategory';
        $this->_controller = 'adminhtml_importcategory';
        parent::_construct();
        $this->buttonList->remove('save');
        $this->buttonList->remove('back');
        $this->buttonList->remove('reset');
        $this->buttonList->add(
            'save-and-continue',
            [
                'label' => __('Import'),
                'class' => 'save primary',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            -100
        );
        $this->buttonList->remove('delete');
    }
    /**
     * Retrieve text for header element depending on loaded Test
     *
     * @return string
     */
    public function getHeaderText()
    {
        return __('Import Categories');
    }
}
