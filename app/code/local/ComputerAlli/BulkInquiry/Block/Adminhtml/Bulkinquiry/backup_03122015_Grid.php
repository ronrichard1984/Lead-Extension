<?php

class ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("bulkinquiryGrid");
				$this->setDefaultSort("inquiry_date");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("bulkinquiry/bulkinquiry")->getCollection();		

				$collection->getSelect()
			//->join(array('regions' => 'directory_country_region'), 'main_table.region_id=regions.region_id', array('region' => 'regions.default_name'))
			->join(array('productName' => 'catalog_product_entity_varchar'), 'main_table.product_id=productName.entity_id and productName.attribute_id = 71', array('product' => 'productName.value'))
			->join(array('productSKU' => 'catalog_product_entity'), 'main_table.product_id=productSKU.entity_id', array('sku' => 'productSKU.sku'))
		;
			
			$this->setCollection($collection);
		
            //echo $collection->getSelect();

				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{

            $countryCode = Mage::getStoreConfig('general/country/default');
            $regionCollection = Mage::getModel('directory/region_api')->items($countryCode);
            $reqions = array();
            foreach ($regionCollection as $reqion) {
                $reqions[] = $reqion['name'];
            }

            $attributeCode = 'select_customer_source';
            $attribute = Mage::getModel('customer/attribute')->loadByCode('customer', $attributeCode);
            $withEmpty = true;
            $defaultValues = true;
            $options = $attribute->getSource()->getAllOptions($withEmpty, $defaultValues);

            $customerAttVal = array();
            foreach( $options as $option ) {
                if($option['value']!=""){
                    $customerAttVal[$option['value']] = $option['label'];
                }
            }

                /*$this->addColumn("inquiry_id", array(
				"header" => Mage::helper("bulkinquiry")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "inquiry_id",
				));*/


                $this->addColumn("inquiry_date", array(
                    "header" => Mage::helper("bulkinquiry")->__("Request Date"),
                    'type'   => 'datetime',
                    "index" => "inquiry_date",
                ));
				
                $this->addColumn('sales_rep', array(
                    'header' => Mage::helper('bulkinquiry')->__('Sales Rep'),
                    'index' => 'sales_rep',
                    'type' => 'options',
                    'options'=>ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getRoleUsers(),
                    //'options'=>$customerAttVal,
                ));
				
                $this->addColumn('lead_status', array(
                'header' => Mage::helper('bulkinquiry')->__('Lead Status '),
                'index' => 'lead_status',
                'type' => 'options',
                'options'=>ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getOptionArray0(),
                //'options'=>$customerAttVal,
                ));
                $this->addColumn("sku", array(
                    "header" => Mage::helper("bulkinquiry")->__("SKU"),
                    "index" => "sku",
					'width' => '60',
                ));
                $this->addColumn("product", array(
                    "header" => Mage::helper("bulkinquiry")->__("Product Name"),
					'width' => '400',
					'class'=>'product_colum',
                    "index" => "product",
					'html_decorators' => array('nobr test'),
                ));
                $this->addColumn("qty", array(
                    "header" => Mage::helper("bulkinquiry")->__("Qty"),
					'renderer'  => new ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Renderer_Qty(),
                    "index" => "qty",
                ));
                /*$this->addColumn('lead_type', array(
                'header' => Mage::helper('bulkinquiry')->__('Lead'),
                'index' => 'lead_type',
                'type' => 'options',
                'options'=>ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getOptionArray1(),
                ));
						
				$this->addColumn("inquiry_date", array(
				"header" => Mage::helper("bulkinquiry")->__("Request Date"),
				"index" => "inquiry_date",
				));*/



				$this->addColumn("firstname", array(
				"header" => Mage::helper("bulkinquiry")->__("First Name"),
				"index" => "firstname",
				));
				$this->addColumn("lastname", array(
				"header" => Mage::helper("bulkinquiry")->__("Last Name"),
				"index" => "lastname",
				));
				$this->addColumn("company", array(
				"header" => Mage::helper("bulkinquiry")->__("Company"),
				"index" => "company",
				'width' => '130',
				));
                $this->addColumn("email_address", array(
                    "header" => Mage::helper("bulkinquiry")->__("Email"),
					'renderer'  => new ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Renderer_Email(),
                    "index" => "email_address",
                ));
				$this->addColumn("phonenumber", array(
				"header" => Mage::helper("bulkinquiry")->__("Phone"),
				"index" => "phonenumber",
				));
				/*$this->addColumn("faxnumber", array(
				"header" => Mage::helper("bulkinquiry")->__("Fax Number"),
				"index" => "faxnumber",
				));

				$this->addColumn("address_line1", array(
				"header" => Mage::helper("bulkinquiry")->__("Address Line 1"),
				"index" => "address_line1",
				));
				$this->addColumn("address_line2", array(
				"header" => Mage::helper("bulkinquiry")->__("Address Line 2"),
				"index" => "address_line2",
				));*/
				/*$this->addColumn("city", array(
				"header" => Mage::helper("bulkinquiry")->__("City"),
				"index" => "city",
				));*/

                $this->addColumn('region_id', array(
                'header' => Mage::helper('bulkinquiry')->__('State/Province'),
                'index' => 'region_id',
                'type' => 'options',
                //'options'=>ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getOptionArray14(),
                'options'=>$reqions,
				'width' => '60',
                ));

                $this->addColumn("notes", array(
                    "header" => Mage::helper("bulkinquiry")->__("Note"),
                    'type' => 'options',
                    'options' => array(
                        '' => 'Any',
                        'yes' => 'True',
                        'no' => 'False'
                    ),
                    "index" => "notes",
                    //'renderer'  => 'ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Renderer_Red',
                    'renderer'  => new ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Renderer_Red(),// THIS IS WHAT THIS POST IS ALL ABOUT
                    'filter_condition_callback' => array($this, '_filterHasUrlConditionCallback'),
                ));

				/*$this->addColumn("postal_code", array(
				"header" => Mage::helper("bulkinquiry")->__("Zip/Postal Code"),
				"index" => "postal_code",
				));*/
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

        protected function _filterHasUrlConditionCallback($collection, $column)
        {
            if (!$value = $column->getFilter()->getValue()) {
                return $this;
            }
            echo $value;
            //exit;
            if ($value=="yes") {
                $this->getCollection()->getSelect()->where(
                    "main_table.notes IS NOT NULL");
            }
            else if ($value=="no") {
                $this->getCollection()->getSelect()->where(
                    "main_table.notes IS NULL");
            }
            else {
                $this->getCollection()->getSelect()->where(
                    "main_table.notes IS NOT NULL");
            }

            return $this;
        }

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('inquiry_id');
			$this->getMassactionBlock()->setFormFieldName('inquiry_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_bulkinquiry', array(
					 'label'=> Mage::helper('bulkinquiry')->__('Remove Bulkinquiry'),
					 'url'  => $this->getUrl('*/adminhtml_bulkinquiry/massRemove'),
					 'confirm' => Mage::helper('bulkinquiry')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray0()
		{
            $data_array=array(); 
			$data_array[0]='New Lead';
			$data_array[1]='Hot Lead';
			$data_array[2]='Warm Lead';
			$data_array[3]='Cold Lead';
			$data_array[4]='Multiple Requests';
			$data_array[5]='Existing Customer';
			$data_array[6]='Dead Lead';
			$data_array[7]='Converted ';
            return($data_array);
		}
		static public function getValueArray0()
		{
            $data_array=array();
			foreach(ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getOptionArray0() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray1()
		{
            $data_array=array(); 
			$data_array[0]='Inbound Call';
			$data_array[1]='Lead - AB Quote';
			$data_array[2]='Lead - Bulk Inquiry';
			$data_array[3]='Lead - Catalog Request';
			$data_array[4]='Meritt Direct - 2015 B';
			$data_array[5]='Outbound - Abandoned Cart';
			$data_array[6]='Tradeshow NRPA - 2015';
			$data_array[7]='Tradeshow NRPA - 2016';
			$data_array[8]='Test';
            return($data_array);
		}
		static public function getValueArray1()
		{
            $data_array=array();
			foreach(ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getOptionArray1() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray14()
		{
            $data_array=array(); 
			$data_array[0]='Dhaka';
			$data_array[1]='Khulna';
            return($data_array);
		}
		static public function getValueArray14()
		{
            $data_array=array();
			foreach(ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getOptionArray14() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}

        static public function getCustomAttVal(){
            $attributeCode = 'select_customer_source';
            $attribute = Mage::getModel('customer/attribute')->loadByCode('customer', $attributeCode);
            $withEmpty = true;
            $defaultValues = true;
            $options = $attribute->getSource()->getAllOptions($withEmpty, $defaultValues);

            $customerAttVal = array();
            foreach( $options as $option ) {
                if($option['value']!=""){
                    $customerAttVal[$option['value']] = $option['label'];
                }
            }

            return $customerAttVal;
        }
		
		static public function getRoleUsers(){
			$salesUsers = Mage::getModel('admin/roles')->load(4)->getRoleUsers();
			$salesoption    =   array();
			foreach($salesUsers as $salesUser){
				$salesUserId    =   $salesUser;
				$user_data = Mage::getModel('admin/user')->load($salesUserId)->getData();
				$salesoption[$salesUserId] = $user_data['firstname']." ".$user_data['lastname'];
			}
			return $salesoption;
		}
		

}