<?php
class ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("bulkinquiry_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("bulkinquiry")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
		    $this->addTab("form_section", array(
				"label" => Mage::helper("bulkinquiry")->__("Item Information"),
				"title" => Mage::helper("bulkinquiry")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("bulkinquiry/adminhtml_bulkinquiry_edit_tab_form")->toHtml(),
			));
           /* $this->addTab('admin_history', array(
                'label'     => Mage::helper('rating')->__('Administrator History'),
                'content'   => $this->getLayout()->createBlock('bulkinquiry/adminhtml_bulkinquiry_edit_tab_history')->toHtml(),
            ));*/
            $this->addTab('notes_customer', array(
                'label'     => Mage::helper('rating')->__('Notes On Customer'),
                'content'   => $this->getLayout()->createBlock('bulkinquiry/adminhtml_bulkinquiry_edit_tab_Customernotes')->toHtml(),
            ));
           /* $this->addTab('shopping_cart', array(
                'label'     => Mage::helper('rating')->__('Shopping Cart'),
                'content'   => $this->getLayout()->createBlock('adminhtml/rating_edit_tab_form')->toHtml(),
            ));*/
				return parent::_beforeToHtml();
		}

}
