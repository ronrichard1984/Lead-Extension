<?php


class ComputerAlli_AdminHistory_Block_Adminhtml_Adminhistory extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_adminhistory";
	$this->_blockGroup = "adminhistory";
	$this->_headerText = Mage::helper("adminhistory")->__("Adminhistory Manager");
	$this->_addButtonLabel = Mage::helper("adminhistory")->__("Add New Item");
	parent::__construct();
     $this->_removeButton('add');
	
	}

}