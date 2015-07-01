<?php


class CodeMasterz_Inquiry_Block_Adminhtml_Inquiry extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_inquiry";
	$this->_blockGroup = "inquiry";
	$this->_headerText = Mage::helper("inquiry")->__("Inquiry Manager");
	$this->_addButtonLabel = Mage::helper("inquiry")->__("Add New Item");
	parent::__construct();
	
	}

}