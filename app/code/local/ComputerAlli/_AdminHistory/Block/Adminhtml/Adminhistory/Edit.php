<?php
	
class ComputerAlli_AdminHistory_Block_Adminhtml_Adminhistory_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "history_id";
				$this->_blockGroup = "adminhistory";
				$this->_controller = "adminhtml_adminhistory";
				$this->_updateButton("save", "label", Mage::helper("adminhistory")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("adminhistory")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("adminhistory")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("adminhistory_data") && Mage::registry("adminhistory_data")->getId() ){

				    return Mage::helper("adminhistory")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("adminhistory_data")->getId()));

				} 
				else{

				     return Mage::helper("adminhistory")->__("Add Item");

				}
		}
}