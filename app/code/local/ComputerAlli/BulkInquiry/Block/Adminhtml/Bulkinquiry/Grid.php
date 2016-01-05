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
				
				$active_user    =   Mage::getSingleton('admin/session')->getUser()->getId();
				$role_data = Mage::getModel('admin/user')->load($active_user)->getRole()->getData();
				$this->user_role	=	$role_data['role_id'];

		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("bulkinquiry/bulkinquiry")->getCollection();		

				$collection->getSelect()
			//->join(array('regions' => 'directory_country_region'), 'main_table.region_id=regions.region_id', array('region' => 'regions.default_name'))
			//->join(array('productName' => 'catalog_product_entity_varchar'), 'main_table.product_id=productName.entity_id and productName.attribute_id = 71', array('product' => 'productName.value'))
			//->join(array('productSKU' => 'catalog_product_entity'), 'main_table.product_id=productSKU.entity_id', array('sku' => 'productSKU.sku'))
			
		;


				 
            $active_user    =   Mage::getSingleton('admin/session')->getUser()->getId();
            $role_data = Mage::getModel('admin/user')->load($active_user)->getRole()->getData();
            if($role_data['role_id']!=1){
			    $collection->getSelect()
				->where("main_table.sales_rep=".$active_user)
                //->joinLeft(array("permmi" => "bulk_permission"), "main_table.inquiry_id = permmi.inquiry_id")
                //->where("permmi.user_id=".$active_user)
				;
                 //echo $collection->getSelect();
                 //exit;
            }

			
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
				$this->addColumn('lead_source', array(
                    'header' => Mage::helper('bulkinquiry')->__('Lead Source '),
                    'index' => 'lead_source',
                    'type' => 'options',
                    'options'=>ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getCustomerSource(),
                    //'options'=>$customerAttVal,
                ));
                /*$this->addColumn("sku", array(
                    "header" => Mage::helper("bulkinquiry")->__("SKU"),
                    "index" => "sku",
                ));
                $this->addColumn("product", array(
                    "header" => Mage::helper("bulkinquiry")->__("Product Name"),
					'width' => '200',
					'class'=>'product_colum',
                    "index" => "product",
					'html_decorators' => array('nobr test'),
                ));
                $this->addColumn("qty", array(
                    "header" => Mage::helper("bulkinquiry")->__("Qty"),
                    "index" => "qty",
                ));*/
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
				));
				
				$link= 'mailto:$email_address';
                $this->addColumn("email_address", array(
                    "header" => Mage::helper("bulkinquiry")->__("Email"),
                    "index" => "email_address",
					'type'     => 'action',
                    'actions'  => array(
                        array(
                            //'url'     => "mailto:".$this->helper('bulkinquiry')->__($email_address),
                            'url'     => $link,
                            'caption' => $this->helper('bulkinquiry')->__($email_address),
                            'target' => '_top',
                        ),
                    )
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
			
            $salesRepSub    =   ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::getRoleUsersForAssign();
            array_unshift($salesRepSub, array('label'=> '', 'value'=> ''));

            $this->getMassactionBlock()->addItem('assign_bulkinquiry', array(
                'label'=> Mage::helper('bulkinquiry')->__('Assign'),
                'url'  => $this->getUrl('*/adminhtml_bulkinquiry/massAssign'),
                'additional'   => array(
                    'visibility'    => array(
                        'name'     => 'assign_group',
                        'type'     => 'select',
                        'class'    => 'required-entry',
                        'label'    => Mage::helper('bulkinquiry')->__('Sales'),
                        'values'   =>$salesRepSub
                    )
                )
            ));

            $leadStatusSub    =   ComputerAlli_BulkInquiry_Block_Adminhtml_Bulkinquiry_Grid::leadStatusforStatusChange();
            array_unshift($leadStatusSub, array('label'=> '', 'value'=> ''));

            $this->getMassactionBlock()->addItem('status_change_bulkinquiry', array(
                'label'=> Mage::helper('bulkinquiry')->__('Status Change'),
                'url'  => $this->getUrl('*/adminhtml_bulkinquiry/massChangestatus'),
                'additional'   => array(
                    'visibility'    => array(
                        'name'     => 'status_group',
                        'type'     => 'select',
                        'class'    => 'required-entry',
                        'label'    => Mage::helper('bulkinquiry')->__('Status'),
                        'values'   =>$leadStatusSub
                    )
                )
            ));
						
			if($this->user_role==1){
				$this->getMassactionBlock()->addItem('remove_bulkinquiry', array(
						 'label'=> Mage::helper('bulkinquiry')->__(' Delete '),
						 'url'  => $this->getUrl('*/adminhtml_bulkinquiry/massRemove'),
						 'confirm' => Mage::helper('bulkinquiry')->__('Are you sure?')
					));
			}
			return $this;
		}
			
		static public function getOptionArray0()
		{
            $data_array=array(); 
			$data_array[1]='New Lead';
			$data_array[2]='Hot Lead';
			$data_array[3]='Warm Lead';
			$data_array[4]='Cold Lead';
			$data_array[5]='Multiple Requests';
			$data_array[6]='Existing Customer';
			$data_array[7]='Dead Lead';
			$data_array[8]='Converted ';
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

        static public function getCustomerSource(){
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

        static public function getCustomerType(){
            $attributeCode = 'select_customer_type';
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
		

    static public function getRoleUsersForAssign(){

        $assignSubmenu = array();
        $salesUsers = Mage::getModel('admin/roles')->load(4)->getRoleUsers();

        foreach($salesUsers as $salesUser){
            $salesUserId    =   $salesUser;
            $user_data = Mage::getModel('admin/user')->load($salesUserId)->getData();
            $assignSubmenu[]=array("label"=>$user_data['firstname']." ".$user_data['lastname'], "value"=>$salesUserId);
        }

        return $assignSubmenu;
    }


    static public function leadStatusforStatusChange()
    {

        $leadstatus = array();
        $leadstatus[]=array("value"=>1, "label"=>"New Lead");
        $leadstatus[]=array("value"=>2, "label"=>"Hot Lead");
        $leadstatus[]=array("value"=>3, "label"=>"Warm Lead");
        $leadstatus[]=array("value"=>4, "label"=>"Cold Lead");
        $leadstatus[]=array("value"=>5, "label"=>"Multiple Requests");
        $leadstatus[]=array("value"=>6, "label"=>"Existing Customer");
        $leadstatus[]=array("value"=>7, "label"=>"Dead Lead");
        $leadstatus[]=array("value"=>8, "label"=>"Converted");

        return $leadstatus;
    }
	
	static public function timeFrame()
    {
        $leadstatus = array();
		$leadstatus[]=array("value"=>1, "label"=>"");
        $leadstatus[]=array("value"=>2, "label"=>"Within 30 days");
        $leadstatus[]=array("value"=>3, "label"=>"1 - 3 Months");
        $leadstatus[]=array("value"=>4, "label"=>"3 - 6 Months");
        $leadstatus[]=array("value"=>5, "label"=>"More than 6 Months");

        return $leadstatus;
    }
	
	static public function interestedin()
    {
        $leadstatus = array();
		$leadstatus[]=array("value"=>1, "label"=>"Barricades");
        $leadstatus[]=array("value"=>2, "label"=>"Benches");
        $leadstatus[]=array("value"=>3, "label"=>"Bike Racks");
        $leadstatus[]=array("value"=>4, "label"=>"Bleachers");
        $leadstatus[]=array("value"=>5, "label"=>"Bollards");
        $leadstatus[]=array("value"=>6, "label"=>"Cigarette Receptacles");
        $leadstatus[]=array("value"=>7, "label"=>"Dog Park");
        $leadstatus[]=array("value"=>8, "label"=>"Fencing");
        $leadstatus[]=array("value"=>9, "label"=>"Fire Rings");
        $leadstatus[]=array("value"=>10, "label"=>"Flooring & Mats");
        $leadstatus[]=array("value"=>11, "label"=>"Grills");
        $leadstatus[]=array("value"=>12, "label"=>"Landscaping & Maintenance");
        $leadstatus[]=array("value"=>13, "label"=>"Lecterns");
        $leadstatus[]=array("value"=>14, "label"=>"Lockers & Storage");
        $leadstatus[]=array("value"=>15, "label"=>"Message Centers");
        $leadstatus[]=array("value"=>16, "label"=>"Outdoor Fitness Equipment");
        $leadstatus[]=array("value"=>17, "label"=>"Patio");
        $leadstatus[]=array("value"=>18, "label"=>"Picnic Tables");
        $leadstatus[]=array("value"=>19, "label"=>"Planters");
        $leadstatus[]=array("value"=>20, "label"=>"Restroom Equipment");
        $leadstatus[]=array("value"=>21, "label"=>"Shelters");
        $leadstatus[]=array("value"=>22, "label"=>"Signs & Stands");
        $leadstatus[]=array("value"=>23, "label"=>"Sports Equipment");
        $leadstatus[]=array("value"=>24, "label"=>"Stanchions");
        $leadstatus[]=array("value"=>25, "label"=>"Tables & Chairs");
        $leadstatus[]=array("value"=>26, "label"=>"Traffic Control");
        $leadstatus[]=array("value"=>27, "label"=>"Trash Receptacles");
        $leadstatus[]=array("value"=>28, "label"=>"Umbrellas");
        $leadstatus[]=array("value"=>29, "label"=>"Windscreens");

        return $leadstatus;
    }
	
}