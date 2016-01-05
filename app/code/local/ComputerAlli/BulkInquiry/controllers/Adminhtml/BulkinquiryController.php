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
					//$model['product'] = $product->getName();
					$model['sku'] = $product->getSku();
			
			        if($model['country']){
                        $model['country']   =   $model['country'];
                    }
                    else{
                        $model['country']   =   "US";
                    }
					
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

		$model['country']   =   "US";
		
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
                $lead_source   =   $post_data['lead_source'];
                $country   =   $post_data['country'];
                $state   =   $post_data['state'];

                $customer = Mage::getModel('customer/customer');
                $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
                $customer->loadByEmail($email);


                if($customer->getId()<1){
                    $customer2 = Mage::getModel('customer/customer');
                    $customer2->setWebsiteId(Mage::app()->getWebsite()->getId());
                    $customer2->loadByEmail($email);
                    if($customer2->getId()<1){
                        $customer->setEmail($email);
                    }
                    //$customer->setSelect_customer_type(8);
                    $customer->setSelect_customer_source($lead_source);
                    $customer->setSelect_customer_type($lead_type);
                    $customer->setFirstname($firstname);
                    $customer->setLastname ($lastname);
                    $customer->save();



                    $dataShipping = array(
                        'firstname'  => $customer->getFirstname(),
                        'lastname'   => $customer->getLastname(),
                        'street'     => $post_data['address_line1'],
                        'city'       => $post_data['city'],
                        'region'     => $post_data['state'],
                        'region_id'  => '',
                        'postcode'   => $post_data['postal_code'],
                        'country_id' => $post_data['country'],
                        'telephone'  => $post_data['phonenumber'],
                    );
					
					
                    $customerAddress = Mage::getModel('customer/address');

                    if ($defaultShippingId = $customer->getDefaultShipping()){
                        $customerAddress->load($defaultShippingId);
                    } else {
                        $customerAddress
                            ->setCustomerId($customer->getId())
                            ->setIsDefaultShipping('1')
                            ->setIsDefaultBilling('1')
                        ;

                        $customer->addAddress($customerAddress);
                    }

                    try {
                        $customerAddress
                            ->addData($dataShipping)
                            ->save()
                        ;
                    } catch(Exception $e){
                        Mage::log('Address Save Error::' . $e->getMessage());
                    }


                }
                else{
                    $customer = Mage::getModel("customer/customer");
                    $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
                    $customer->setStore(Mage::app()->getStore());

                    $customer->setSelect_customer_source($lead_source);
                    $customer->setSelect_customer_type($lead_type);
                    $customer->setFirstname($firstname);
                    $customer->setLastname($lastname);
                    $customer->setEmail($email);
                    $customer->setPasswordHash(md5("myReallySecurePassword"));
                    $customer->save();

                    //$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                    //return;
                }

                $action_time    =   now();
                $log_data    =   array();
                $log_data['inquiry_id']=$this->getRequest()->getParam("id");
                $log_data['product_id']="123";
                $log_data['action_time']=$action_time;
                $log_data['action_type']='Modified';
                $log_data['action_user']=Mage::getSingleton('admin/session')->getUser()->getId();
                $log_data['ip_address']=gethostbyname(trim(`hostname`));

                //$post_data['lead_source']=$post_data['lead_source'];
                $post_data['lead_status']=8;

                try {

                    $model = Mage::getModel("bulkinquiry/bulkinquiry")
                        ->addData($post_data)
                        ->setId($this->getRequest()->getParam("id"))
                        ->save();

                    $model_log = Mage::getModel("adminhistory/adminhistory")
                        ->addData($log_data)
                        ->save();

                    Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Converted was successfully saved"));
                    Mage::getSingleton("adminhtml/session")->setBulkinquiryData(false);
                        //$this->_redirect("*/*/edit", array("id" => $model->getId()));
						//Mage::app()->getResponse()->setRedirect(Mage::helper("adminhtml")->getUrl("adminhtml/customer/"));
						Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/sales_order/"));
                        return;
                }
                catch (Exception $e) {
                    Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                    Mage::getSingleton("adminhtml/session")->setBulkinquiryData($this->getRequest()->getPost());
                    $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                    return;
                }
            }

            if($this->getRequest()->getParam("id")){
                $log_type="Modified";
            }
            else{
                $log_type="New entry";
				$post_data['product_id']=1;
            }
                $action_time    =   now();
                $log_data    =   array();
                $log_data['inquiry_id']=$this->getRequest()->getParam("id");
                $log_data['product_id']="123";
                $log_data['action_time']=$action_time;
                $log_data['action_type']=$log_type;
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

                    if($post_data['is_enabled']==false){
                        //$model->setIsEnabled(2);
                        $post_data['is_enabled']=0;
                    }
                    else{
                        $post_data['is_enabled']=1;
                    }

                   // $post_data['lead_source']=$post_data['lead_source'];

				    if($post_data['delete_note']!=false){
                        $post_data['notes']="";
                    }
					
					 $post_data['interestedin'] = serialize($post_data['interestedin']);
					 $post_data['title'] = serialize($post_data['title']);
			
					
					try {

						$model = Mage::getModel("bulkinquiry/bulkinquiry")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

                        //$model->setIsEnabled(!empty($postData['is_enabled']));


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

		
        public function massAssignAction()
        {

            $write = Mage::getSingleton("core/resource")->getConnection("core_write");

            $assign_val =   $this->getRequest()->getPost('assign_group');

           /* echo "<pre>";
            echo $this->getRequest()->getPost("assign_group");
            print_r($this->getRequest()->getPost());
            exit;*/

            try {
                $ids = $this->getRequest()->getPost('inquiry_ids', array());
                foreach ($ids as $id) {
                    //$query = "INSERT IGNORE INTO `bulk_permission` (`inquiry_id`, `user_id`) VALUES ($id, $assign_val)";
                    $query  =   "UPDATE `bulk_inquiry` SET `sales_rep` = '$assign_val' WHERE `inquiry_id` =$id";

					$write->query($query);
                    //$model = Mage::getModel("bulkinquiry/bulkinquiry");
                    //$model->setId($id)->delete();
                }
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully assigned"));
            }
            catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
            }
            $this->_redirect('*/*/');
        }
		
		public function massChangestatusAction()
        {

            $write = Mage::getSingleton("core/resource")->getConnection("core_write");

            $status_val =   $this->getRequest()->getPost('status_group');

             /*echo "<pre>";
            // echo $this->getRequest()->getPost("assign_group");
             print_r($this->getRequest()->getPost());
             exit;*/

            try {
                $ids = $this->getRequest()->getPost('inquiry_ids', array());
                foreach ($ids as $id) {
				
					$model = Mage::getModel("bulkinquiry/bulkinquiry");
					$model->load($id);
						
					if($model->getEmail_address()==8){
					
					}
					
					else{
											
                    $query  =   "UPDATE `bulk_inquiry` SET `lead_status` = '$status_val' WHERE `inquiry_id` =$id";

                    //$query = "INSERT IGNORE INTO `bulk_permission` (`inquiry_id`, `user_id`) VALUES ($id, $assign_val)";
                    $write->query($query);

                    //$model = Mage::getModel("bulkinquiry/bulkinquiry");
                    //$model->setId($id)->delete();
					
					}

                }
                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully changed"));
            }
            catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
            }
            $this->_redirect('*/*/');
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

		public function stateAction() {
            $countrycode = $this->getRequest()->getParam('country');

            $state = "<option value=''>Please Select</option>";
            if ($countrycode != '') {
                $statearray = Mage::getModel('directory/region')->getResourceCollection() ->addCountryFilter($countrycode)->load();
                foreach ($statearray as $_state) {
                    $state .= "<option value='" . $_state->getCode() . "'>" .  $_state->getDefaultName() . "</option>";
                }
            }
            else{
                $statearray = Mage::getModel('directory/region')->getResourceCollection() ->addCountryFilter("US")->load();
                foreach ($statearray as $_state) {
                    $state .= "<option value='" . $_state->getCode() . "'>" .  $_state->getDefaultName() . "</option>";
                }
            }
            echo $state;
        }
		
		
        protected function _isAllowed(){
            return true;
        }

}
