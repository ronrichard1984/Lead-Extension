<?php
class ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Edit_Tab_Customernotes extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

            $countryCode = Mage::getStoreConfig('general/country/default');
            $regionCollection = Mage::getModel('directory/region_api')->items($countryCode);
            $regions = array();
            foreach($regionCollection as $region) {
                $regions[] = array('value' => $region['region_id'], 'label' => $region['name']);
            }



            $form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("bulkinquiry_form", array("legend"=>Mage::helper("bulkinquiry")->__("Notes On Customer")));
						$fieldset->addField("notes", "textarea", array(
						"label" => Mage::helper("bulkinquiry")->__("Note"),
						"name" => "notes",
						));
					
				$fieldset->addField('delete_note','checkbox', array(
					'label' => Mage::helper('bulkinquiry')->__('Delete?'),
					'name' => 'delete_note',
					//'checked' => (int)$history_data['delete_note'] > 0 ? 'checked' : '',
					'onclick' => 'this.value = this.checked ? 1 : 0;',
				));
			
				if (Mage::getSingleton("adminhtml/session")->getBulkinquiryData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getBulkinquiryData());
					Mage::getSingleton("adminhtml/session")->setBulkinquiryData(null);
				} 
				elseif(Mage::registry("bulkinquiry_data")) {
				    $form->setValues(Mage::registry("bulkinquiry_data")->getData());
				}
				return parent::_prepareForm();
		}
}
?>
