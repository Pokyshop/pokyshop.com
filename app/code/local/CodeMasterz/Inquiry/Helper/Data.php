<?php
class CodeMasterz_Inquiry_Helper_Data extends Mage_Core_Helper_Abstract{
	/*
		$recepientData			=
		$emailTemplateVariables	=	Variables that can be used in email template
		$templateId				=	Transactional Email Template's ID
	*/
	public function sendInquryEmail($templateId, $recepientData, $emailTemplateVariables)
	{
		//echo '<pre>';print_r($emailTemplateVariables);die;
		// Set the sender information          
		$senderName 	= 	'Pokyshop';					//	Mage::getStoreConfig('general/store_information/name');
		$senderEmail 	= 	'info@pokyshop.com';		//	Mage::getStoreConfig('trans_email/ident_general/email');
		$sender 		= 	array(	'name' => $senderName,
									'email' => $senderEmail
								);
		
		$recepientEmail	=	$recepientData['name'];
		$recepientName	=	$recepientData['email'];
									  
		// Get Store ID    
		$store 		= Mage::app()->getStore()->getId();
		$translate  = Mage::getSingleton('core/translate');
	 
		// Send Transactional Email
		try {
			Mage::getModel('core/email_template')
				->addBcc('vaseemansari007@gmail.com')		//	Adding BCC by Vaseem
				->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $emailTemplateVariables, $storeId);
			$translate->setTranslateInline(true);
			return 1;
		}
		catch(Exception $ex) {
			return 0;
		}
	}
	
}
	 