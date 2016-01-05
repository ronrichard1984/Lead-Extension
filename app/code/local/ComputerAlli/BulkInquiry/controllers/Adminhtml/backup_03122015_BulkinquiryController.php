<?php

class ComputerAlli_BulkInquiry_Adminhtml_BulkinquiryController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("bulkinquiry/bulkinquiry")->_addBreadcrumb(Mage::helper("adminhtml")->__("Bulkinquiry  Manager"),Mage::helper("adminhtml")->__("Bulkinquiry Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("BulkInquiry"));
			    $this->_title($this->__("Manager Bulkinquiry"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("BulkInquiry"));
				$this->_title($this->__("Bulkinquiry"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");

                //SAVE DATA FOR ADMINISTRATOR LOG
                $action_time    =   now();
                $log_data    =   array();
                $log_data['inquiry_id']=$id;
                $log_data['product_id']="123";
                $log_data['action_time']=$action_time;
                $log_data['action_type']='Load';
                $log_data['action_user']=Mage::getSingleton('admin/session')->getUser()->getId();
                $log_data['ip_address']=gethostbyname(trim(`hostname`));

                $model_log = Mage::getModel("adminhistory/adminhistory")
                ->addData($log_data)
                //->setId($this->getRequest()->getParam("id"))
                ->save();


				$model = Mage::getModel("bulkinquiry/bulkinquiry")->load($id);
				if ($model->getId()) {
				
					$product = Mage::getModel('catalog/product')->load($model['product_id']);
					$model['product'] = $product->getName();
					$model['sku'] = $product->getSku();
			
					Mage::register("bulkinquiry_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("bulkinquiry/bulkinquiry");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Bulkinquiry Manager"), Mage::helper("adminhtml")->__("Bulkinquiry Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Bulkinquiry Description"), Mage::helper("adminhtml")->__("Bulkinquiry Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("bulkinquiry/adminhtml_bulkinquiry_edit"))->_addLeft($this->getLayout()->createBlock("bulkinquiry/adminhtml_bulkinquiry_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("bulkinquiry")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("BulkInquiry"));
		$this->_title($this->__("Bulkinquiry"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("bulkinquiry/bulkinquiry")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("bulkinquiry_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("bulkinquiry/bulkinquiry");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Bulkinquiry Manager"), Mage::helper("adminhtml")->__("Bulkinquiry Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Bulkinquiry Description"), Mage::helper("adminhtml")->__("Bulkinquiry Description"));


		$this->_addContent($this->getLayout()->createBlock("bulkinquiry/adminhtml_bulkinquiry_edit"))->_addLeft($this->getLayout()->createBlock("bulkinquiry/adminhtml_bulkinquiry_edit_tabs"));

		$this->renderLayout();

		}

       public function get_client_ip_server() {
            $ipaddress = '';
            if ($_SERVER['HTTP_CLIENT_IP'])
                $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if($_SERVER['HTTP_X_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if($_SERVER['HTTP_X_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if($_SERVER['HTTP_FORWARDED_FOR'])
                $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if($_SERVER['HTTP_FORWARDED'])
                $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if($_SERVER['REMOTE_ADDR'])
                $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
                $ipaddress = 'UNKNOWN';

            return $ipaddress;
        }

		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();

            if($this->getRequest()->getParam("convertocustomer")){

                $email   =   $post_data['email_address'];
                $firstname  =   $post_data['firstname'];
                $lastname   =   $post_data['lastname'];
                $lead_type   =   $post_data['lead_type'];
	
                $customer = Mage::getModel('customer/customer');
                $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
                $customer->loadByEmail($email);

				
                if($customer->getId()>=1){
                    $customer2 = Mage::getModel('customer/customer');
                    $customer2->setWebsiteId(Mage::app()->getWebsite()->getId());
                    $customer2->loadByEmail($email);
                    if($customer2->getId()<1){
                        $customer->setEmail($email);
                    }
                    //$customer->setSelect_customer_type(8);
                    $customer->setSelect_customer_source($lead_type);
                    $customer->setFirstname($firstname);
                    $customer->setLastname ($lastname);
                    $customer->save();
                }
                else{
                    $customer = Mage::getModel("customer/customer");
                    $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
                    $customer->setStore(Mage::app()->getStore());

					$customer->setSelect_customer_source($lead_type);
                    $customer->setFirstname($firstname);
                    $customer->setLastname($lastname);					
                    $customer->setEmail($email);
                    $customer->setPasswordHash(md5("myReallySecurePassword"));
                    $customer->save();

                    //$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                    //return;
                }



            }

            if($this->getRequest()->getParam("id")){
                $model="Modified";
            }
            else{
                $model="New entry";
            }
                $action_time    =   now();
                $log_data    =   array();
                $log_data['inquiry_id']=$this->getRequest()->getParam("id");
                $log_data['product_id']="123";
                $log_data['action_time']=$action_time;
                $log_data['action_type']=$model;
                $log_data['action_user']=Mage::getSingleton('admin/session')->getUser()->getId();
                $log_data['ip_address']=gethostbyname(trim(`hostname`));

            /*echo "<pre>";
            print_r($log_data);
            exit;
            echo $this->getRequest()->getParam("id");
               echo "<pre>";
                print_r($post_data);
                exit;
            echo Mage::getSingleton('admin/session')->getUser()->getId();
            exit;*/

				if ($post_data) {

					try {

						$model = Mage::getModel("bulkinquiry/bulkinquiry")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

                       $model_log = Mage::getModel("adminhistory/adminhistory")
                            ->addData($log_data)
                            //->setId($this->getRequest()->getParam("id"))
                            ->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Bulkinquiry was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setBulkinquiryData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setBulkinquiryData($this->getRequest()->getPost());
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
						$model = Mage::getModel("bulkinquiry/bulkinquiry");
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
				$ids = $this->getRequest()->getPost('inquiry_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("bulkinquiry/bulkinquiry");
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
			$fileName   = 'bulkinquiry.csv';
			$grid       = $this->getLayout()->createBlock('bulkinquiry/adminhtml_bulkinquiry_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'bulkinquiry.xml';
			$grid       = $this->getLayout()->createBlock('bulkinquiry/adminhtml_bulkinquiry_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}

        protected function _isAllowed(){
            return true;
        }


}
