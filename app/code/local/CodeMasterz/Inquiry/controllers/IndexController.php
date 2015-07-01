<?php
class CodeMasterz_Inquiry_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Titlename"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("titlename", array(
                "label" => $this->__("Titlename"),
                "title" => $this->__("Titlename")
		   ));

      $this->renderLayout(); 
	  
    }
	
	public function GetInTouchAction() {
		$post = $this->getRequest()->getPost();
		//echo '<pre>';print_r($post);die;
		$name		=	$post['name'];
		$email		=	$post['email'];
		$phoneNo	=	$post['phone_no'];
		
		$insertData	= 	array(	'inquiry_type'	=> 'Get in Touch',
								'name' 			=> $name,
								'email' 		=> $email,
								'phone_no' 		=> $phoneNo
                		);
		$model 		= 	Mage::getModel('inquiry/inquiry')->setData($insertData);
		try {
			$insertId = $model->save()->getId();
			//echo "Data successfully inserted. Insert ID: ".$insertId;die;

			// Transactional Email Template's ID
			$templateId 	= 	'inquiry_get_in_touch_email_template';
			
			// Set variables that can be used in email template
			$emailTemplateVariables 	= 	array(	'name' 		=> $name,
													'email' 	=> $email,
													'phone_no' 	=> $phoneNo
											);
			// Set recepient information
			$recepientEmail = 	'vaseemansari007@gmail.com';
			$recepientName 	= 	'Vaseem Ansari';
			$recepientData = 	array(	'name' 		=> $recepientName,
										'email' 	=> $recepientEmail
								);
			
			$isEmailSent 	= 	Mage::helper('inquiry')->sendInquryEmail($templateId, $recepientData, $emailTemplateVariables);
			//echo $isEmailSent;
			if($isEmailSent==1){
				$message	=	'Thanks for getting in touch with us. We will reply within 24 hours.';
				Mage::getSingleton('core/session')->addSuccess($message);
				$this->_redirect('make-in-india');				
			}else{
				$message	=	'Oops! There is some error. Please wait while we are working on it.';
				Mage::getSingleton('core/session')->addError($message);
				$this->_redirect('make-in-india');				
			}
		} catch (Exception $e){
		 	echo $e->getMessage();   
		}
	}
	
	public function JoinUsAction() {
		$post = $this->getRequest()->getPost();
		//echo '<pre>';print_r($post);die;
		$name			=	$post['name'];
		$email			=	$post['email'];
		$phoneNo		=	$post['phone_no'];
		
		$company_name	=	$post['company_name'];
		$address		=	$post['address'];
		$city			=	$post['city'];
		$state			=	$post['state'];
		$postal_code	=	$post['postal_code'];
		
		$comment		=	'Company Name : '.$company_name.'<br>';
		$comment.=	'Address: '.$address.'<br>';
		$comment.=	'City: '.$city.'<br>';
		$comment.=	'State: '.$state.'<br>';
		$comment.=	'Postal Code: '.$postal_code;
		
		$insertData	= 	array(	'inquiry_type'	=> 'Join Us',
								'name' 			=> $name,
								'email' 		=> $email,
								'phone_no' 		=> $phoneNo,
								'comment' 		=> $comment
                		);
		$model 		= 	Mage::getModel('inquiry/inquiry')->setData($insertData);
		try {
			$insertId = $model->save()->getId();
			//echo "Data successfully inserted. Insert ID: ".$insertId;die;

			// Transactional Email Template's ID
			$templateId 	= 	'inquiry_join_us_email_template';
			
			// Set variables that can be used in email template
			$emailTemplateVariables 	= 	array(	'name' 			=> $name,
													'email' 		=> $email,
													'phone_no' 		=> $phoneNo,
													'company_name' 	=> $company_name,
													'address' 		=> $address,
													'city' 			=> $city,
													'state' 		=> $state,
													'postal_code' 	=> $postal_code
											);
			// Set recepient information
			$recepientEmail = 	'vaseemansari007@gmail.com';
			$recepientName 	= 	'Vaseem Ansari';
			$recepientData = 	array(	'name' 		=> $recepientName,
										'email' 	=> $recepientEmail
								);
			
			$isEmailSent 	= 	Mage::helper('inquiry')->sendInquryEmail($templateId, $recepientData, $emailTemplateVariables);
			//echo $isEmailSent;
			if($isEmailSent==1){
				$message	=	'Thanks for getting in touch with us. We will reply within 24 hours.';
				Mage::getSingleton('core/session')->addSuccess($message);
				$this->_redirect('make-in-india');				
			}else{
				$message	=	'Oops! There is some error. Please wait while we are working on it.';
				Mage::getSingleton('core/session')->addError($message);
				$this->_redirect('make-in-india');				
			}
		} catch (Exception $e){
		 	echo $e->getMessage();   
		}
	}
	
	public function SellWithUsAction() {
		$post = $this->getRequest()->getPost();
		//echo '<pre>';print_r($post);die;
		$shop_name		=	$post['shop_name'];
		$phone_no		=	$post['phone_no'];
		$email			=	$post['email'];
		$alternate_email=	$post['alternate_email'];
		//$name			=	$post['name'];
		$company_name	=	$post['company_name'];
		$contact_person_name	=	$post['contact_person_name'];
		$address_line1	=	$post['address_line1'];
		$address_line2	=	$post['address_line2'];
		$city			=	$post['city'];
		$state			=	$post['state'];
		$postal_code	=	$post['postal_code'];
		$i_agree		=	$post['i_agree'];
		
		$comment =	'Shop Name :'.$shop_name;
		$comment.=	'Alternate Email : '.$alternate_email;
		$comment.=	'Company Name : '.$company_name;
		$comment.=	'Contact Person Name: '.$contact_person_name;
		$comment.=	'Address: '.$address_line1. ' '.$address_line2;
		$comment.=	'City: '.$city;
		$comment.=	'State: '.$state;
		$comment.=	'Postal Code: '.$postal_code;
		
		$insertData	= 	array(	'inquiry_type'	=> 'Join Us',
								'name' 			=> $contact_person_name,
								'email' 		=> $email,
								'phone_no' 		=> $phone_no,
								'comment' 		=> $comment
                		);
		$model 		= 	Mage::getModel('inquiry/inquiry')->setData($insertData);
		try {
			$insertId = $model->save()->getId();
			//echo "Data successfully inserted. Insert ID: ".$insertId;
			
			// Transactional Email Template's ID
			$templateId 	= 	'inquiry_sell_with_us_email_template';
		 	
			// Set variables that can be used in email template
			$emailTemplateVariables 	= 	array(	'name' 			=> $name,
													'email' 		=> $email,
													'alternate_email'=> $alternate_email,
													'phone_no' 		=> $phoneNo,
													'shop_name' 	=> $shop_name,
													'company_name' 	=> $company_name,
													'contact_person_name'=> $contact_person_name,
													'address' 		=> $address_line1 .' '.$address_line2,
													'city' 			=> $city,
													'state' 		=> $state,
													'postal_code' 	=> $postal_code
											);
			// Set recepient information
			$recepientEmail = 	'vaseemansari007@gmail.com';
			$recepientName 	= 	'Vaseem Ansari';
			$recepientData = 	array(	'name' 		=> $recepientName,
										'email' 	=> $recepientEmail
								);
			
			$isEmailSent 	= 	Mage::helper('inquiry')->sendInquryEmail($templateId, $recepientData, $emailTemplateVariables);
			//echo $isEmailSent;
			if($isEmailSent==1){
				//	Sending email to merchants
				$templateId 	= 	'inquiry_sell_with_us_merchants_email_template';
				$recepientData 	= 	array(	'name' 		=> $name,
											'email' 	=> $email
									);
								
				$isEmailSent 	= 	Mage::helper('inquiry')->sendInquryEmail($templateId, $recepientData);
				
				$message	=	'Thanks for filling the seller form. We will surely get in touch with you within 24 hours.';
				Mage::getSingleton('core/session')->addSuccess($message);
				$this->_redirect('make-in-india');				
			}else{
				$message	=	'Oops! There is some error. Please wait while we are working on it.';
				Mage::getSingleton('core/session')->addError($message);
				$this->_redirect('make-in-india');				
			}
		} catch (Exception $e){
			//Mage::log($e->getMessage());
		 	echo $e->getMessage();
		}
	}
}