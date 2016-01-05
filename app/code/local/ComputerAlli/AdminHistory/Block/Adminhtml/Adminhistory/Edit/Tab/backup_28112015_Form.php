<?php
class ComputerAlli_AdminHistory_Block_Adminhtml_Adminhistory_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("adminhistory_form", array("legend"=>Mage::helper("adminhistory")->__("Item information")));

				
						$fieldset->addField("product_id", "text", array(
						"label" => Mage::helper("adminhistory")->__("Product Name"),
						"name" => "product_id",
						));
					
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);

						$fieldset->addField('action_time', 'date', array(
						'label'        => Mage::helper('adminhistory')->__('Action Time'),
						'name'         => 'action_time',
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
						));
						$fieldset->addField("action_type", "text", array(
						"label" => Mage::helper("adminhistory")->__("Action Type"),
						"name" => "action_type",
						));
					
						$fieldset->addField("action_user", "text", array(
						"label" => Mage::helper("adminhistory")->__("User"),
						"name" => "action_user",
						));
					
						$fieldset->addField("ip_address", "text", array(
						"label" => Mage::helper("adminhistory")->__("IP Address"),
						"name" => "ip_address",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getAdminhistoryData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getAdminhistoryData());
					Mage::getSingleton("adminhtml/session")->setAdminhistoryData(null);
				} 
				elseif(Mage::registry("adminhistory_data")) {
				    $form->setValues(Mage::registry("adminhistory_data")->getData());
				}
				return parent::_prepareForm();
		}
}
