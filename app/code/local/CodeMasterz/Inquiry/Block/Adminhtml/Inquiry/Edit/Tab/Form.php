<?php
class CodeMasterz_Inquiry_Block_Adminhtml_Inquiry_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("inquiry_form", array("legend"=>Mage::helper("inquiry")->__("Item information")));

								
						 $fieldset->addField('inquiry_type', 'select', array(
						'label'     => Mage::helper('inquiry')->__('Type'),
						'values'   => CodeMasterz_Inquiry_Block_Adminhtml_Inquiry_Grid::getValueArray1(),
						'name' => 'inquiry_type',
						));
						$fieldset->addField("name", "text", array(
						"label" => Mage::helper("inquiry")->__("Name"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "name",
						));
					
						$fieldset->addField("email", "text", array(
						"label" => Mage::helper("inquiry")->__("Email"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "email",
						));
					
						$fieldset->addField("phone_no", "text", array(
						"label" => Mage::helper("inquiry")->__("Phone"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "phone_no",
						));
					
						$fieldset->addField("comment", "textarea", array(
						"label" => Mage::helper("inquiry")->__("Comment"),
						"name" => "comment",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getInquiryData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getInquiryData());
					Mage::getSingleton("adminhtml/session")->setInquiryData(null);
				} 
				elseif(Mage::registry("inquiry_data")) {
				    $form->setValues(Mage::registry("inquiry_data")->getData());
				}
				return parent::_prepareForm();
		}
}
