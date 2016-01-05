<?php

class ComputerAlli_AdminHistory_Block_Adminhtml_Adminhistory_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("adminhistoryGrid");
				$this->setDefaultSort("history_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("adminhistory/adminhistory")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("history_id", array(
				"header" => Mage::helper("adminhistory")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "history_id",
				));
                
				$this->addColumn("product_id", array(
				"header" => Mage::helper("adminhistory")->__("Product Name"),
				"index" => "product_id",
				));
					$this->addColumn('action_time', array(
						'header'    => Mage::helper('adminhistory')->__('Action Time'),
						'index'     => 'action_time',
						'type'      => 'datetime',
					));
				$this->addColumn("action_type", array(
				"header" => Mage::helper("adminhistory")->__("Action Type"),
				"index" => "action_type",
				));
				$this->addColumn("action_user", array(
				"header" => Mage::helper("adminhistory")->__("User"),
				"index" => "action_user",
				));
				$this->addColumn("ip_address", array(
				"header" => Mage::helper("adminhistory")->__("IP Address"),
				"index" => "ip_address",
				));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('history_id');
			$this->getMassactionBlock()->setFormFieldName('history_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_adminhistory', array(
					 'label'=> Mage::helper('adminhistory')->__('Remove Adminhistory'),
					 'url'  => $this->getUrl('*/adminhtml_adminhistory/massRemove'),
					 'confirm' => Mage::helper('adminhistory')->__('Are you sure?')
				));
			return $this;
		}
			

}