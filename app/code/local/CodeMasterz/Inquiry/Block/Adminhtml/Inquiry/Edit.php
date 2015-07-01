<?php
	
class CodeMasterz_Inquiry_Block_Adminhtml_Inquiry_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "inquiry_id";
				$this->_blockGroup = "inquiry";
				$this->_controller = "adminhtml_inquiry";
				$this->_updateButton("save", "label", Mage::helper("inquiry")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("inquiry")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("inquiry")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("inquiry_data") && Mage::registry("inquiry_data")->getId() ){

				    return Mage::helper("inquiry")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("inquiry_data")->getId()));

				} 
				else{

				     return Mage::helper("inquiry")->__("Add Item");

				}
		}
}