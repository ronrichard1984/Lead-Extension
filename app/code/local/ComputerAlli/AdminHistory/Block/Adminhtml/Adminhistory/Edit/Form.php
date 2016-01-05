<?php
class ComputerAlli_AdminHistory_Block_Adminhtml_Adminhistory_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{
				$form = new Varien_Data_Form(array(
				"id" => "edit_form",
				"action" => $this->getUrl("*/*/save", array("id" => $this->getRequest()->getParam("id"))),
				"method" => "post",
				"enctype" =>"multipart/form-data",
				)
				);

           // echo "<pre>";
           // print_r($this->htmlEscape(Mage::registry("adminhistory_data")->getdata()));



           //echo "<pre>";
            $adminUserModel = Mage::getModel('admin/user');
            $user_data = Mage::getModel('admin/user')->load(1)->getData();

            //print_r($user_data);
            //exit;
            $history_data   =   Mage::registry("adminhistory_data")->getdata();

            $user_data = Mage::getModel('admin/user')->load($history_data['action_user'])->getData();
            //echo "<pre>";
           //print_r($user_data);

            //print_r(Mage::registry("adminhistory_data")->getdata());

            $date=date_create($history_data['action_time']);
            $action_time    =   date_format($date,'F j, Y \a\t g:ia');
            echo "
            <p><b>Time:</b> $action_time
            </p><p><b>Action Type: </b>".$history_data['action_type'].
            "</p><p><b>User:</b> ".$user_data['firstname']." ".$user_data['lastname'].
            "</p><p><b>Remote Address</b> ".$history_data['ip_address'].
            "</p><p><b>Entity</b> ".$history_data['entity']."</p>"
            ;


            //echo "Ron Test";
				//$form->setUseContainer(true);
				//$this->setForm($form);
				return parent::_prepareForm();
		}
}
