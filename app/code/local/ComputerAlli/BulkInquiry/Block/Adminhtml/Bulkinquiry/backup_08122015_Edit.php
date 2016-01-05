<?php
	
class ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "inquiry_id";
				$this->_blockGroup = "bulkinquiry";
				$this->_controller = "adminhtml_bulkinquiry";
				$this->_updateButton("save", "label", Mage::helper("bulkinquiry")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("bulkinquiry")->__("Delete Item"));
				$this->_removeButton('delete');
				       //convert to customer button start

                $this->_addButton("convert", array(
                    "label"     => Mage::helper("bulkinquiry")->__("Convert to Customer"),
                    "onclick"   => "convertToCustomer()",
                    "class"     => "save",
                ), 1, 1);

                $this->_formScripts[] = "

							function convertToCustomer(){
							    //alert($('edit_form').action+'convertocustomer/edit/');
								editForm.submit($('edit_form').action+'convertocustomer/edit/');
							}

                            //$('#sales_rep option[value=$active_user]').attr('selected', 'selected');
						";

            //convert to customer button end
			
				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("bulkinquiry")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);


				$active_user    =   Mage::getSingleton('admin/session')->getUser()->getId();

				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
							
							document.getElementsByName('sales_rep')[0].value='$active_user';
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("bulkinquiry_data") && Mage::registry("bulkinquiry_data")->getId() ){

				    return Mage::helper("bulkinquiry")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("bulkinquiry_data")->getId()));

				} 
				else{

				     return Mage::helper("bulkinquiry")->__("Add Item");

				}
		}
}