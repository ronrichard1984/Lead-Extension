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
        $collection = Mage::getModel("adminhistory/adminhistory")->getCollection()
		            ->addExpressionFieldToSelect('fullname', 'CONCAT({{firstname}}, \' \', {{lastname}})', array('firstname' => 'firstname', 'lastname' => 'lastname'));

		$collection->getSelect()
		->join(array('inquery' => 'bulk_inquiry'), 'main_table.inquiry_id=inquery.inquiry_id', array('firstname' => 'inquery.firstname', 'lastname' => 'inquery.lastname'));
			
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        $this->addColumn("history_id", array(
            "header" => Mage::helper("adminhistory")->__("ID"),
            "align" =>"right",
            "width" => "50px",
            "index" => "history_id",
        ));

        /*$this->addColumn("product_id", array(
        "header" => Mage::helper("adminhistory")->__("Product Name"),
        "index" => "product_id",
        ));*/
            $this->addColumn('action_time', array(
                'header'    => Mage::helper('adminhistory')->__('Action Time'),
                'index'     => 'action_time',
                'type'      => 'datetime',
            ));
        $this->addColumn("action_type", array(
            "header" => Mage::helper("adminhistory")->__("Action Type"),
            "index" => "action_type",
        ));
		$this->addColumn('fullname', array(
            'header'       => Mage::helper("adminhistory")->__('Entity'),
            'index'        => 'fullname',
            'sortable' => false,
            'filter'   => false
            //'filter_index' => 'CONCAT(firstname, \' \', lastname)'
        ));
        /*$this->addColumn("action_user", array(
        "header" => Mage::helper("adminhistory")->__("User"),
        "index" => "action_user",
        ));*/

        $this->addColumn('action_user', array(
            'header' => Mage::helper('adminhistory')->__('User'),
            'index' => 'action_user',
            'type' => 'options',
            'options'=>ComputerAlli_AdminHistory_Block_Adminhtml_Adminhistory_Grid::getRoleUsers(),
            //'options'=>$customerAttVal,
        ));
        $this->addColumn("ip_address", array(
            "header" => Mage::helper("adminhistory")->__("IP Address"),
            'sortable' => false,
            'filter'   => false,
            "index" => "ip_address",
        ));

        $link= Mage::helper('adminhtml')->getUrl('admin_adminhistory/adminhtml_adminhistory/edit/') .'id/$history_id';
        $this->addColumn('action_edit', array(
            'header'   => $this->helper('adminhistory')->__('Action'),
            'width'    => 15,
            'sortable' => false,
            'filter'   => false,
            'type'     => 'action',
            'actions'  => array(
                array(
                    'url'     => $link,
                    'caption' => $this->helper('catalog')->__('Details'),
                ),
            )
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

    static public function getRoleUsers(){

        //$salesUsers = Mage::getModel('admin/roles')->load(3)->getRoleUsers();
        $adminUserModel = Mage::getModel('admin/user');
        $salesUsers = $adminUserModel->getCollection()->load()->getData();

        $salesoption    =   array();
        foreach($salesUsers as $salesUser){
            $salesUserId    =   $salesUser['user_id'];
            $salesoption[$salesUserId] = $salesUser['firstname']." ".$salesUser['lastname'];
        }
        return $salesoption;
    }


}