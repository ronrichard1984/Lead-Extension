<?php
class ComputerAlli_AdminHistory_Block_Adminhtml_Adminhistory_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("adminhistory_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("adminhistory")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("adminhistory")->__("Item Information"),
				"title" => Mage::helper("adminhistory")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("adminhistory/adminhtml_adminhistory_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
