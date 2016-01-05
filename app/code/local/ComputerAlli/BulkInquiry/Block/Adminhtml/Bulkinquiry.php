<?php


class ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_bulkinquiry";
	$this->_blockGroup = "bulkinquiry";
	$this->_headerText = Mage::helper("bulkinquiry")->__("Leads Manager");
	$this->_addButtonLabel = Mage::helper("bulkinquiry")->__("Add New Item");
	parent::__construct();
	
	}

}