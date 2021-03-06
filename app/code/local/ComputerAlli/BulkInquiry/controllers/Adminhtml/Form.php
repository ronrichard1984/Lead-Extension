<?php
class ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
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
				$fieldset = $form->addFieldset("bulkinquiry_form", array("legend"=>Mage::helper("bulkinquiry")->__("Item information")));



                        $fieldset->addField('lead_source', 'select', array(
                            'label'     => Mage::helper('bulkinquiry')->__('Lead Source'),
                            'values'   => ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getCustomerSource(),
                            'name' => 'lead_source',
                        ));

                        $fieldset->addField('lead_type', 'select', array(
                            'label'     => Mage::helper('bulkinquiry')->__('Lead Type'),
                            'values'   => ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getCustomerType(),
                            'name' => 'lead_type',
                        ));

                        $fieldset->addField('lead_status', 'select', array(
						'label'     => Mage::helper('bulkinquiry')->__('Lead Status '),
						'values'   => ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getValueArray0(),
						'name' => 'lead_status',
						));
                        $fieldset->addField("sales_rep", "select", array(
                            "label" => Mage::helper("bulkinquiry")->__("Sales Rep"),
                            'values'   => ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getRoleUsers(),
                            "name" => "sales_rep",
                        ));
						$fieldset->addField("inquiry_date", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("Request Date"),
						"name" => "inquiry_date",
                        'readonly' => true
						));
					
						$fieldset->addField("product", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("Product"),
						"name" => "product"
						));
					
						$fieldset->addField("sku", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("SKU"),
						"name" => "sku"
						));
					
						$fieldset->addField("firstname", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("First Name"),
						"name" => "firstname",
						));
					
						$fieldset->addField("lastname", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("Last Name"),
						"name" => "lastname",
						));
					
						$fieldset->addField("company", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("Company"),
						"name" => "company",
						));
					
						$fieldset->addField("phonenumber", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("Phone Number"),
						"name" => "phonenumber",
						));
					
						$fieldset->addField("faxnumber", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("Fax Number"),
						"name" => "faxnumber",
						));
					
						$fieldset->addField("email_address", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("Email Address"),
						'required' => true,
						"name" => "email_address",
						));
					
						$fieldset->addField("address_line1", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("Address Line 1"),
						"name" => "address_line1",
						));
					
						$fieldset->addField("address_line2", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("Address Line 2"),
						"name" => "address_line2",
						));
					
						$fieldset->addField("city", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("City"),
						"name" => "city",
						));
									
						 $fieldset->addField('region_id', 'select', array(
						'label'     => Mage::helper('bulkinquiry')->__('State/Province'),
						//'values'   => ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getValueArray14(),
						'values'   => $regions,
						'name' => 'region_id',
						));
						$fieldset->addField("postal_code", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("Zip/Postal Code"),
						"name" => "postal_code",
						));
					
						/*$fieldset->addField("notes", "textarea", array(
						"label" => Mage::helper("bulkinquiry")->__("Receive email news and updates"),
						"name" => "notes",
						));*/

                        $history_data   =   Mage::registry("bulkinquiry_data")->getdata();

                        $fieldset->addField('is_enabled','checkbox', array(
                            'label' => Mage::helper('bulkinquiry')->__('Receive email news and updates?'),
                            'name' => 'is_enabled',
                            'checked' => (int)$history_data['is_enabled'] > 0 ? 'checked' : '',
                            'onclick' => 'this.value = this.checked ? 1 : 0;',
                        ));

                        /*$fieldset->addField('is_enabled', 'checkbox', array(
                            'label'     => Mage::helper('bulkinquiry')->__('Enable feature?'),
                            'onclick'   => 'this.value = this.checked ? 1 : 0;',
                            'name'      => 'is_enabled',
                        ));*/
					

				if (Mage::getSingleton("adminhtml/session")->getBulkinquiryData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getBulkinquiryData());
					Mage::getSingleton("adminhtml/session")->setBulkinquiryData(null);
				} 
				elseif(Mage::registry("bulkinquiry_data")) {
				    $form->setValues(Mage::registry("bulkinquiry_data")->getData());
				}

            $buil_inquerydata   =   Mage::registry("bulkinquiry_data")->getData();
            if(!$buil_inquerydata['inquiry_date']){
                $form->addValues(array('inquiry_date'=> date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()))));
            }

            return parent::_prepareForm();
		}
}
