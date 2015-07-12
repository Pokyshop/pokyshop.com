<?php
class Codemasterz_PostProduct_IndexController extends Mage_Core_Controller_Front_Action{
    
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
	
	protected function _getSession()
    {		
        return Mage::getSingleton('customer/session');
    }
	
	public function indexAction()
    {
		$session 	= 	$this->_getSession();
    	/*if(!$session->isLoggedIn()){
			$message = $this->__('You need to login first before you add a new product. So please login or create an account.');
			Mage::getSingleton('core/session')->addError($message);
    		$this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
    	}else{*/
			//Get current layout state
			$this->loadLayout();   
	 
			$block = $this->getLayout()->createBlock(
				'Mage_Core_Block_Template',
				'codemasterz.postproduct',
				array(
					'template' => 'codemasterz/postproduct.phtml'
				)
			);
	 
			$this->getLayout()->getBlock('content')->append($block);
			//$this->getLayout()->getBlock('right')->insert($block, 'catalog.compare.sidebar', true);
	 
			$this->_initLayoutMessages('core/session');
	 
			$this->renderLayout();
		//}
    }
	
	public function addProductAction(){
		//Fetch submited params
        $params 			= 	$this->getRequest()->getParams();
		//echo '<pre>';print_r($params);die;
		$title				=	$params['title'];
		$short_description	=	$params['short_description'];
		$description		=	$params['description'];
		if($description==''){
			$description	=	$short_description;
		}
		$price				=	$params['price'];
		$product_condition	=	$params['product_condition'];
		$category			=	$params['category'];
		$price				=	$params['price'];
		
		//	S:VA - Check if customer already posted the same data
		$collection = Mage::getModel('catalog/product')
							->getCollection()  
							->addAttributeToFilter('name',$title)
			                ->addAttributeToFilter('short_description',$short_description)
							->addAttributeToSort('created_at', 'desc');   
		$collection->getSelect()->limit(1);
		//$collection->load(1);die;
		//echo '<pre>';print_r($collection->getData());
		$previousRecordsCount	=	$collection->count();
		//	E:VA
		if($previousRecordsCount>=1){
			$message	=	'Oops! You have already posted a same product. Please check the below listed product(s) to edit the details.';
			Mage::getSingleton('core/session')->addError($message);
			echo Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('product/'));die;	
		}
		//	Customer Details
		$customerName		=	$params['name'];
		$customerEmail		=	$params['email'];
		$telephone			=	$params['telephone'];
		$city				=	$params['city'];
		$state				=	$params['state'];
		$address			=	$params['address'];
		$customerAddress	=	$params['address'];
		$customerDetails	=	'<p>'.$params['name'].'<br />'.$params['email'].'<br />'.$params['telephone'].'<br />'.$params['city'].'<br />'.$params['state'].'</p>';
		
		if($product_condition=='New'){
			$sku				=	'PSCPN-'.time();
			$product_condition	=	'3';		//	value of the dropdown options for this attribute cmz_product_condition
		}else{
			$sku				=	'PSCPO-'.time();
			$product_condition	=	'4';
		}
		//echo '<pre>';print_r($params);die;
		//echo '<pre>';print_r($_FILES['product_image']);die;
		
		//	Start adding a new product
		$product = new Mage_Catalog_Model_Product();
		//echo time();
		// Build the required attributes for new Product
		$product->setAttributeSetId(4);// #4 is for default
		$product->setTypeId('simple');
		$product->setName($title);
		$product->setShortDescription($short_description);
		$product->setDescription($description);
		$product->setSku($sku);
		$product->setUrlKey($title.'-'.$sku);
		$product->setWeight(1.0000);
		
		$product->setCmzProductCondition($product_condition);
		$product->setCmzCustomerAddress($customerAddress);
		$product->setCmzCustomerDetails($customerDetails);
		
		//$product->setStatus(1);
		//$product->setStatus( Mage_Catalog_Model_Product_Status::STATUS_ENABLED );
		$product->setStatus( Mage_Catalog_Model_Product_Status::STATUS_DISABLED );
		$product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);//4
		//print_r(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
		
		$product->setPrice($price);// # Set some price
		$product->setTaxClassId(0);// # default tax class
		
		$product->setStockData(array(
			'is_in_stock' => 1,
			'qty' => 1
		));
		
		$product->setCategoryIds(array($category));
		$product->setWebsiteIDs(array(1));
		
		//	Default Magento attribute
		$product->setCreatedAt(strtotime('now'));
		
		
		//	Upload product image to media\import directory
		foreach ($_FILES['product_image']['size'] as $key => $fileSize) {
			if($fileSize>2097152){
				$message = $this->__('Uploaded file is too large. Please upload a valid file.');
				Mage::getSingleton('core/session')->addError($message);
				echo Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('*/*/index', array( 'param'=>$this->getRequest()->getParam('param'),'key'=>$this->getRequest()->getParam('order'))));die;
			}else{
				if(count($_FILES['product_image']['name'])>0){
					foreach ($_FILES['product_image']['name'] as $key => $image) {
						//	Check if file is valid
						/*echo '<pre>';print_r($_FILES['product_image']);
						echo '<pre>';print_r($key);
						echo '<pre>';print_r($image);
						die;*/
						$image	=	strtolower($image);
						if(!strpos($image,'.jpg') && !strpos($image,'.jpeg') && !strpos($image,'.png') && !strpos($image,'.gif') && !strpos($image,'.doc') && !strpos($image,'.pdf') && $image!=''){
							//echo 'not a valid extension';
							$message = $this->__('Uploaded file is not a valid Product Image as it doesnt match gif, jpg, png.');
							Mage::getSingleton('core/session')->addError($message);
							echo Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('*/*/index', array( 'param'=>$this->getRequest()->getParam('param'),'key'=>$this->getRequest()->getParam('order'))));die;
						}else{
							//echo 'allowed extension';
							if($_FILES['product_image']['name']){
								try {
									$uploader = new Varien_File_Uploader(
										array(
											'name' 		=> str_replace(' ','',$_FILES['product_image']['name'][$key]),
											'type' 		=> $_FILES['product_image']['type'][$key],
											'tmp_name' 	=> $_FILES['product_image']['tmp_name'][$key],
											'error' 	=> $_FILES['product_image']['error'][$key],
											'size' 		=> $_FILES['product_image']['size'][$key]
										)
									);
									// Any extention would work
									//$orderIncrementId = Mage::helper('core')->urlDecode($this->getRequest()->getParam('order'));
									$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
									$uploader->setAllowRenameFiles(false);
									$uploader->setFilesDispersion(false);
									// We set media as the upload dir
									$path = Mage::getBaseDir('media') . DS . 'import' . DS . $sku . DS;
									//preg_replace('/[^a-zA-Z0-9 s]/', '-', $image);
									$image = strtolower(str_replace(array('    ','   ','  ', ' '), '', preg_replace('/[^a-zA-Z0-9. s]/', '-', trim($image))));
									if(strstr($image,'-')){
										$image	=	str_replace(array('---','--','-'), '_', $image);
									}
									//$newName    = time() .$image;
									$uploader->save($path, $image);
									$imageUrl = $path . $image;
									$imageResized = $path . $image;
									if(strpos($images,'.jpg')!='' || strpos($images,'.jpeg')!='' || strpos($images,'.png')!='' || strpos($images,'.gif')!='' ):
										if (file_exists($imageResized) && file_exists($imageUrl)) :
											$imageObj = new Varien_Image($imageUrl);
											$imageObj->constrainOnly(TRUE);
											$imageObj->keepAspectRatio(TRUE);
											$imageObj->keepFrame(FALSE);
											//$imageObj->resize(150, 150);
											$imageObj->save($imageResized);
										endif;
									endif;
									$saveImageNameToDb[] .= $image;
									//$img = $uploader->save($path, $_FILES['product_image']['name'][$key]);
								} catch (Exception $e) {
									Mage::log($e->getMessage());
								}
							}
						}
					}
				}	
			}
		}
		//echo '<pre>';print_r($saveImageNameToDb);		echo count($saveImageNameToDb);die;
	// Add three image sizes to media gallery
		//$putPathHere	=	$saveImageNameToDb;			//	media\import\sku
		$mainImage		=	$saveImageNameToDb[0];
		$smallImage		=	$saveImageNameToDb[1];
		$thumbnailImage	=	$saveImageNameToDb[2];
		
		if(count($saveImageNameToDb) > 2){
			$mediaArray 	= 	array(
										'image'       => $mainImage,
										'small_image' => $smallImage,
										'thumbnail'   => $thumbnailImage,
									);
		}elseif(count($saveImageNameToDb) > 1){
			$mediaArray 	= 	array(
									'image'       => $mainImage,
									'small_image' => $smallImage,
									'thumbnail'   => $smallImage,
								);
		}else{
			$mediaArray 	= 	array(
									'image'       => $mainImage,
									'small_image' => $mainImage,
									'thumbnail'   => $mainImage,
								);	
		}
		
		// Remove unset images, add image to gallery if exists
		$importDir = Mage::getBaseDir('media') . DS . 'import'. DS . $sku. DS ;
		
		foreach($mediaArray as $imageType => $fileName) {
			$filePath = $importDir.$fileName;
			if ( file_exists($filePath) ) {
				try {
					$product->addImageToMediaGallery($filePath, $imageType, false);
				} catch (Exception $e) {
					echo $e->getMessage();
				}
			} else {
				echo "Product does not have an image or the path is incorrect. Path was: {$filePath}<br/>";
			}
		}
		
		//print_r($product);die;
		try {
			$product->save();
			//	Delete product images from media import folder	
			array_map('unlink', glob("$importDir/*.*"));
			rmdir($importDir);
			
			//	Save the entry in Amm Product Table
			$resource 			= 	Mage::getSingleton('core/resource');
			$write 				= 	$resource->getConnection('core_write');		// reading the database resource
			$customerId 		= 	Mage::getSingleton('customer/session')->getCustomer()->getId();
			
			$updatefieldsData 	= 	array('id_customer' => $customerId,'id_product' => $product->getId());
			if(	$write->insert('ps_amm_product', $updatefieldsData) ){
				$message	=	'Congratulation. Your product is added successfully. You can edit the product(s) listed below.';
				Mage::getSingleton('core/session')->addSuccess($message);
				
				// Transactional Email Template's ID
				$templateId 	= 	'postproduct_email_template';
				$emailTemplate  = 	Mage::getModel('core/email_template')
										->loadDefault($templateId);		
										
				
				
				// Set variables that can be used in email template
		$emailTemplateVariables 	= 	array(	'customer_name' 	=> $customerName,
												'customer_email' 	=> $customerEmail
											 );
		$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
												
		
		$senderName 	= 	Mage::getStoreConfig('general/store_information/name');
		$senderEmail 	= 	Mage::getStoreConfig('trans_email/ident_general/email');
		
		$mail = Mage::getModel('core/email')
				->setToName($customerName)
				->setToEmail($customerEmail)
				//->setToEmail('vaseemansari007@gmail.com')
				->setBody($processedTemplate)
				->setSubject('Thank you for posting a product at '.$senderName)
				->setFromEmail($senderEmail)
				->setFromName($senderName)
				->setType('html');					
				// Send Transactional Email
				try {	
					$mail->send();		
					$message	=	'Please check you email for the confirmation.';
					Mage::getSingleton('core/session')->addSuccess($message);
				}catch (Mage_Core_Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				} catch (Exception $e) {
					$this->_getSession()->addError($this->__('Oops! There is some issue in sending an acknowledgement email to you. Please wait while we are working on it.'));
				}
				$this->_redirect('product/');				
				//Mage::getSingleton('core/session')->addError($message);
				//echo Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('product/'));
			}else{
				$message	=	'Your product is added successfully but there is some technical issue.';
				Mage::getSingleton('core/session')->addSuccess($message);
				echo Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('*/*/index', array( 'param'=>$this->getRequest()->getParam('param') )));die;	
			}
		}
		catch (Exception $ex) {
			print_r($ex);die;
			//Handle the error
			$message	=	'Sorry there is some error while adding the product.';
			Mage::getSingleton('core/session')->addError($message);
			echo Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('*/*/index', array( 'param'=>$this->getRequest()->getParam('param') )));die;
							
		}
	}

}
 
?>