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

			$inquiry_data   =   Mage::registry("bulkinquiry_data")->getdata();
			
            $form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("bulkinquiry_form", array("legend"=>Mage::helper("bulkinquiry")->__("Item information")));

						
						$inquiry_date   =   $inquiry_data['inquiry_date'];
						$dateTime = new DateTime($inquiry_date);
						$date_test  =   $dateTime->format("m-d-Y");
						
						$fieldset->addField("note", "note", array(
							"label" => Mage::helper("bulkinquiry")->__("Request Date"),
							'text'     => $date_test,
						));
		
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
						$fieldset->addField("inquiry_date", "hidden", array(
						//"label" => Mage::helper("bulkinquiry")->__("Request Date"),
						"name" => "inquiry_date",
                        //'readonly' => true
						));
                        $fieldset->addField("time_frame", "select", array(
                            "label" => Mage::helper("bulkinquiry")->__("Time Frame to Purchase"),
                            'values'   => ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::timeFrame(),
                            "name" => "time_frame",
                        ));	


						$fieldset->addField('follow_up_date', 'date', array(
							'name'               => 'follow_up_date',
							'label'              => Mage::helper('bulkinquiry')->__('Follow Up Date'),
							'tabindex'           => 1,
							'image'              => $this->getSkinUrl('images/grid-cal.gif'),
							'format'             => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT) ,
							//'format'             => "y-M-d" ,
							'value'              => date( Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
														  strtotime('next weekday') )
						));

						
						$fieldset->addField("product", "textarea", array(
						"label" => Mage::helper("bulkinquiry")->__("Product/Catalog/Interest"),
						"name" => "product"
						));
					
						/*$fieldset->addField("sku", "text", array(
						"label" => Mage::helper("bulkinquiry")->__("SKU"),
						"name" => "sku"
						));*/
					
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
						//'after_element_html' => '<a href="mailto:' . urlencode(Mage::registry('bulkinquiry_data')->getData('email_address')).'" style="margin-left:40px;">Send Mail</a>',
						'after_element_html' => '<a target="_top" href="mailto:' . Mage::registry('bulkinquiry_data')->getData('email_address').'" style="margin-left:40px;">Send Mail</a>',
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
							
						$country = $fieldset->addField('country', 'select', array(
							'name'  => 'country',
							'label'     => 'Country',
							'values'    => Mage::getModel('adminhtml/system_config_source_country') ->toOptionArray(false),
							'onchange' => 'getstate(this)',
						));
						
						$storeId = $this->getRequest()->getParam('id');
						$country_id    =   $inquiry_data['country'];
						$stateCollection = Mage::getModel('directory/region')->getResourceCollection()->addCountryFilter($country_id)->load();
						$state = "";
						foreach ($stateCollection as $_state) {
						/*echo "<pre>";
						print_r($_state);
						exit;*/
							$state[]= array('value'=>$_state->getRegion_id(),'label'=>$_state->getDefaultName());
						}
						
						
						$fieldset->addField('state', 'select', array(
							'label' => Mage::helper('bulkinquiry')->__('State'),
							'required' => false,
							'name' => 'state',
							'selected' => 'selected',
							'values' => $state,
						));

						/*
						   * Add Ajax to the Country select box html output
						   */
						$country->setAfterElementHtml("<script type=\"text/javascript\">
						function getstate(selectElement){
							var reloadurl = '". $this->getUrl('bulkinquiry/adminhtml_bulkinquiry/state') . "country/' + selectElement.value;
							new Ajax.Request(reloadurl, {
								method: 'get',
								onLoading: function (stateform) {
									$('state').update('Searching...');
								},
								onComplete: function(stateform) {
									$('state').update(stateform.responseText);
								}
							});
						}
					</script>
					<style>
						ul.checkboxes li{float:left; width:190px !important;}
						.columns .form-list td.value{width:100%}
					</style>
					");
					
						
						/*$fieldset->addField('region_id', 'select', array(
						'label'     => Mage::helper('bulkinquiry')->__('State/Province'),
						//'values'   => ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getValueArray14(),
						'values'   => $regions,
						'name' => 'region_id',
						));*/
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

						$model = Mage::registry('bulkinquiry_data');	
				
						/*$fieldset->addField("interestedin[]", "checkboxes", array(
                            "label" => Mage::helper("bulkinquiry")->__("Interested In"),
                            'values'   => ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::interestedin(),
                            "name" => "interestedin[]",
							'checked' => unserialize($model->getInterestedin())
                        ));	*/
						
						/*$model = Mage::registry('bulkinquiry_data');
						$fieldset->addField('title[]', 'checkboxes', array(
									'label'     => Mage::helper('bulkinquiry')->__('Title'),
									'name'      => 'title[]',
									'style'     =>'display:inline',
									'values' => array(
										array('value'=>1,'label'=>'Checkbox1'),
										array('value'=>2,'label'=>'Checkbox2'),
										array('value'=>3,'label'=>'Checkbox3'),
									),
									'checked' => unserialize($model->getTitle())
								));
						*/
						/*for($i=01; $i*5<60; $i++){
							$time[$i]['value'] = $i*5;
							$time[$i]['label'] = $i*5;
						}
						$fieldset->addField('Time', 'checkboxes', array(
							'label' => $this->__('Time'),
							'name' => 'time[]',
							'values' => $time,
							'value'  => '1',
							'tabindex' => 1
						));*/
		
		
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
