<?php

class ComputerAlli_AdminHistory_Adminhtml_AdminhistoryController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("bulkinquiry/bulkinquiry")->_addBreadcrumb(Mage::helper("adminhtml")->__("Adminhistory  Manager"),Mage::helper("adminhtml")->__("Adminhistory Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("AdminHistory"));
			    $this->_title($this->__("Manager Adminhistory"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("AdminHistory"));
				$this->_title($this->__("Adminhistory"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("adminhistory/adminhistory")->load($id);

                /*echo "<pre>";
                print_r($model->getorigData());
                exit;*/


				if ($model->getId()) {

                    //get bulk enquiry first name and last name, then pass to history edit section
                    $inquery = Mage::getModel("bulkinquiry/bulkinquiry")->load($model['inquiry_id']);
                    $inquery_data   =   $inquery->getData();
                    $model['entity'] = $inquery_data['firstname']." ".$inquery_data['lastname'];

                    //get user first name and last name from user model then pass to edit block
                    //$user_data = Mage::getModel('admin/user')->load($model['action_user'])->getData();
                    //$model['history_user'] = $user_data['firstname']." ".$user_data['lastname'];


                    Mage::register("adminhistory_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("adminhistory/adminhistory");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Adminhistory Manager"), Mage::helper("adminhtml")->__("Adminhistory Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Adminhistory Description"), Mage::helper("adminhtml")->__("Adminhistory Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					//$this->_addContent($this->getLayout()->createBlock("adminhistory/adminhtml_adminhistory_edit"))->_addLeft($this->getLayout()->createBlock("adminhistory/adminhtml_adminhistory_edit_tabs"));
					$this->_addContent($this->getLayout()->createBlock("adminhistory/adminhtml_adminhistory_edit"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("adminhistory")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("AdminHistory"));
		$this->_title($this->__("Adminhistory"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("adminhistory/adminhistory")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("adminhistory_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("adminhistory/adminhistory");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Adminhistory Manager"), Mage::helper("adminhtml")->__("Adminhistory Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Adminhistory Description"), Mage::helper("adminhtml")->__("Adminhistory Description"));


		$this->_addContent($this->getLayout()->createBlock("adminhistory/adminhtml_adminhistory_edit"))->_addLeft($this->getLayout()->createBlock("adminhistory/adminhtml_adminhistory_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("adminhistory/adminhistory")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Adminhistory was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setAdminhistoryData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setAdminhistoryData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("adminhistory/adminhistory");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('history_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("adminhistory/adminhistory");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'adminhistory.csv';
			$grid       = $this->getLayout()->createBlock('adminhistory/adminhtml_adminhistory_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'adminhistory.xml';
			$grid       = $this->getLayout()->createBlock('adminhistory/adminhtml_adminhistory_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
		
		protected function _isAllowed(){
            return true;
        }
}
