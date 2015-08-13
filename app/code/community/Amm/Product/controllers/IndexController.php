<?php

class Amm_Product_IndexController extends Mage_Core_Controller_Front_Action
{
	
	public function preDispatch()
	{
		parent::preDispatch();
		if (!Mage::getSingleton('customer/session')->authenticate($this)) {
			Mage::getSingleton('customer/session')->addError($this->__('Please login to continue.'));
			$currentUrl = Mage::helper('core/url')->getCurrentUrl();
			Mage::getSingleton('customer/session')->setBeforeAuthUrl($currentUrl);
			$this->setFlag('', self::FLAG_NO_DISPATCH, true);
		}
	}
	
	
    /**
     * Index action
     */
    
    protected function _getSession()
    {		
        return Mage::getSingleton('customer/session');
    }

    public function indexAction()
    {
     	$session = $this->_getSession();

        Mage::helper('product')->checkTable();

    	/*if(!$session->isLoggedIn()){
    		$this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
    	}else{*/
    		$this->loadLayout();

                $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
                if ($navigationBlock) {
                    $navigationBlock->setActive('product');
                }

            $this->renderLayout();
        //}
    }

    public function editAction()
    {
        $param = $this->getRequest()->getParams();
        if($param['ep']==0){
            $this->_redirectUrl(Mage::getBaseUrl('link').'product/');
        }else{ 
            $session = $this->_getSession();
            if(!$session->isLoggedIn()){
                $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
            }else{
                $this->loadLayout();

                    $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
                    if ($navigationBlock) {
                        $navigationBlock->setActive('product');
                    }

                $this->renderLayout();
            }
        }
    }

    public function deleteAction()
    {
        $param = $this->getRequest()->getParams();
        if($param['p']!=0){
            $session = $this->_getSession();
            if(!$session->isLoggedIn()){
                $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
            }else{
                
                $session_array = $session->toArray();
                $result = Mage::helper('product')->deleteProduct($session_array['id'],$param['p']);
                //var_dump($result);
            }
        }

        $this->_redirectUrl(Mage::getBaseUrl('link').'product/');
    }
	
	public function exportAction()
    {
     	$session = $this->_getSession();
		//echo '<pre>';print_r($session);die;
        //Mage::helper('product')->checkTable();

    	if(!$session->isLoggedIn()){
    		$this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
    	}else{
			
			//echo $customerID;die;
			$session_array = $session->toArray();
			//echo '<pre>';print_r($session_array);die;
			$customerID	=	$session_array['id'];	//Mage::helper('sarah')->getCustomerId();
			
			$result = Mage::helper('product')->exportProducts($customerID);
			die;
			//var_dump($result);
			
    		//$this->loadLayout();

			/*$navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
			if ($navigationBlock) {
				$navigationBlock->setActive('product');
			}*/

            $this->renderLayout();
        }
    }
	
	
	public function updateProductDetailsAction(){
		//echo '<pre>'; print_r($_FILES);die;
		if(isset($_FILES['input_file']['name']) && ($_FILES['input_file']['tmp_name'] != NULL)){
			$uploader = new Varien_File_Uploader('input_file');
			$uploader->setAllowedExtensions(array('csv'));
			$uploader->setAllowRenameFiles(true);
			$uploader->setFilesDispersion(false);
			$fileUploadPath = Mage::getBaseDir('media') . DS . 'importcsv' . DS ;
			$uploader->save($fileUploadPath, $_FILES['input_file']['name'] );		//	file uploaded
			
			$file 		=   $_FILES['input_file']['name'];
			$filepath 	= 	$fileUploadPath.$file;
			
			$data				=	array();
			$updates_handle		=	fopen($filepath, 'r');
			$i = 0;
			if($updates_handle) {
				while($data = fgetcsv($updates_handle, 1000, ",")) {
					//echo '<pre>'; print_r($data);die;
					if($i > 0 && count($data)>1){
						$old_sku		=	$data[0];
						$name			=	$data[2];
						try {
							$_product 		= Mage::getModel('catalog/product');
							$checkProductId = $_product->getIdBySku($old_sku);
							if(!$checkProductId){
								$_product = Mage::getModel('catalog/product')->loadByAttribute('name', $name);
							}else{
								$_product = Mage::getModel('catalog/product')->load($checkProductId);
							}
							//echo '<pre>';print_r($_product->getData());die;
							if ($_product) {
								$description	=	$data[3];
								$price			=	$data[4];
								if($data[1]){
									$new_sku	=	str_replace(' ','-',$data[1]);	
								}
								if($new_sku){
									$_product->setSku($new_sku)->save();
									$updatedSku	=	$new_sku;
								}else{
									$updatedSku	=	$old_sku;
								}
								$_product->setName($name);
								$_product->setDescription($description);
								$_product->setPrice($price);
								$_product->save();
								Mage::getSingleton('core/session')->addSuccess( $this->__("Successfully updated the product details of sku '".$updatedSku."'") );
							} else {
								Mage::getSingleton('core/session')->addError( $this->__("The product with sku '".$old_sku."' or name '".$name."' does not exist.") );
							}
						} catch (Exception $e) {
							Mage::getSingleton('core/session')->addError( $this->__("Cannot retrieve products : ".$e->getMessage()));
						}
					}
					$i++;
				}
			}
			fclose($updates_handle);
			unlink($filepath);		//	Delete file so that the same file does not get imported again
		}else{
			Mage::getSingleton('core/session')->addError( $this->__('Please upload a valid csv file.') );
		}
		Mage::app()->getResponse()->setRedirect(Mage::getUrl('product'));
	}
	
	public function importAction(){
		echo '<pre>'; print_r($_FILES);die;
		
		if(isset($_FILES['import_product']['name']) && ($_FILES['import_product']['tmp_name'] != NULL)){
			$file 		= 	$_FILES['import_product']['tmp_name']; 
			$handle 	= 	fopen($file,"r"); 
			// get the first line into a fields map
			$csv_fields = 	fgetcsv($handle,1000,",",'"');
			while($data = 	fgetcsv($handle,1000,",",'"')){
				$data	=	array_map('addslashes',$data);
				//echo '<pre>';print_r($data);
				$data	=	array_combine($csv_fields,$data); // csv fields assoc (key=>value)
				//echo '<pre>';print_r($data);
				$sku	=	$data['sku'];
				// Load by SKU
				$_product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
				if ($_product->getId()){
					$_product->setName($data['name']);
					$_product->setShortDescription($data['short_description']);
					$_product->setDescription($data['description']);
					$_product->setPrice($data['price']);
					/*$stock_data=array(
						'use_config_manage_stock' => 1,
						'qty' => 998,
						'min_qty' => 0,
						'use_config_min_qty'=>1,
						'min_sale_qty' => 0,
						'use_config_min_sale_qty'=>1,
						'max_sale_qty' => 9999,
						'use_config_max_sale_qty'=>1,
						'is_qty_decimal' => 0,
						'backorders' => 0,
						'notify_stock_qty' => 0,
						'is_in_stock' => 1
					);
					$product->setData('stock_data',$stock_data);*/
					//$product->setWebsiteIds(array(1,2));
					//$product->setCategoryIds(array(7,10));
					//$product->setStatus(1);//1=Enabled; 2=Disabled;
					//$product->setVisibility(4);//4 = catalog & search.
					$_product->save();
				}else{
					Mage::getSingleton('core/session')->addError( $this->__("Oops! There is some issue with the sku ".$sku) );
				}
			}
			Mage::getSingleton('core/session')->addSuccess( $this->__('Product(s) updated successfully.') );
		}else{
			Mage::getSingleton('core/session')->addError( $this->__('Please upload a valid csv file.') );
		}
		Mage::app()->getResponse()->setRedirect(Mage::getUrl('product'));
	}

    public function uploadAction()
    {
        try {
            $uploader = new Mage_Core_Model_File_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->addValidateCallback('catalog_product_image',
                Mage::helper('catalog/image'), 'validateUploadFile');
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                Mage::getSingleton('catalog/product_media_config')->getBaseTmpMediaPath()
            );

            Mage::dispatchEvent('catalog_product_gallery_upload_image_after', array(
                'result' => $result,
                'action' => $this
            ));

            /**
             * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
             */
            $result['tmp_name'] = str_replace(DS, "/", $result['tmp_name']);
            $result['path'] = str_replace(DS, "/", $result['path']);

            $result['url'] = Mage::getSingleton('catalog/product_media_config')->getTmpMediaUrl($result['file']);
            $result['file'] = $result['file'] . '.tmp';
            $result['cookie'] = array(
                'name'     => session_name(),
                'value'    => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path'     => $this->_getSession()->getCookiePath(),
                'domain'   => $this->_getSession()->getCookieDomain()
            );

        } catch (Exception $e) {
            $result = array(
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode());
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function additionalfieldsAction(){
        $param = $this->getRequest()->getParams();
        $result = Mage::helper('product')->getAdditionalFieldsProduct($param['setp'],$param['entid']);
        $this->getResponse()->setBody($result);
    }

    protected function _processDownload($resource, $resourceType)
    {
        $helper = Mage::helper('downloadable/download');
        /* @var $helper Mage_Downloadable_Helper_Download */

        $helper->setResource($resource, $resourceType);

        $fileName       = $helper->getFilename();
        $contentType    = $helper->getContentType();

        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true);

        if ($fileSize = $helper->getFilesize()) {
            $this->getResponse()
                ->setHeader('Content-Length', $fileSize);
        }

        if ($contentDisposition = $helper->getContentDisposition()) {
            $this->getResponse()
                ->setHeader('Content-Disposition', $contentDisposition . '; filename='.$fileName);
        }

        $this->getResponse()
            ->clearBody();
        $this->getResponse()
            ->sendHeaders();

        $helper->output();
    }

    /**
     * Download link action
     *
     */
    public function linkAction()
    {
        $linkId = $this->getRequest()->getParam('id', 0);
        $link = Mage::getModel('downloadable/link')->load($linkId);
        if ($link->getId()) {
            $resource = '';
            $resourceType = '';
            if ($link->getLinkType() == Mage_Downloadable_Helper_Download::LINK_TYPE_URL) {
                $resource = $link->getLinkUrl();
                $resourceType = Mage_Downloadable_Helper_Download::LINK_TYPE_URL;
            } elseif ($link->getLinkType() == Mage_Downloadable_Helper_Download::LINK_TYPE_FILE) {
                $resource = Mage::helper('downloadable/file')->getFilePath(
                    Mage_Downloadable_Model_Link::getBasePath(), $link->getLinkFile()
                );
                $resourceType = Mage_Downloadable_Helper_Download::LINK_TYPE_FILE;
            }
            try {
                $this->_processDownload($resource, $resourceType);
            } catch (Mage_Core_Exception $e) {
                $this->_getCustomerSession()->addError(Mage::helper('downloadable')->__('An error occurred while getting the requested content.'));
            }
        }
        exit(0);
    }

    public function downloadableuploadAction()
    {
        $type = $this->getRequest()->getParam('type');
        $tmpPath = '';
        if ($type == 'samples') {
            $tmpPath = Mage_Downloadable_Model_Sample::getBaseTmpPath();
        } elseif ($type == 'links') {
            $tmpPath = Mage_Downloadable_Model_Link::getBaseTmpPath();
        } elseif ($type == 'link_samples') {
            $tmpPath = Mage_Downloadable_Model_Link::getBaseSampleTmpPath();
        }
        $result = array();
        try {
            $uploader = new Mage_Core_Model_File_Uploader($type);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save($tmpPath);

            /**
             * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
             */
            $result['tmp_name'] = str_replace(DS, "/", $result['tmp_name']);
            $result['path'] = str_replace(DS, "/", $result['path']);

            if (isset($result['file'])) {
                $fullPath = rtrim($tmpPath, DS) . DS . ltrim($result['file'], DS);
                Mage::helper('core/file_storage_database')->saveFile($fullPath);
            }

            $result['cookie'] = array(
                'name'     => session_name(),
                'value'    => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path'     => $this->_getSession()->getCookiePath(),
                'domain'   => $this->_getSession()->getCookieDomain()
            );
        } catch (Exception $e) {
            $result = array('error'=>$e->getMessage(), 'errorcode'=>$e->getCode());
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}