<?php

class CodeMasterz_Inquiry_Block_Adminhtml_Inquiry_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("inquiryGrid");
				$this->setDefaultSort("inquiry_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("inquiry/inquiry")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("inquiry_id", array(
				"header" => Mage::helper("inquiry")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "inquiry_id",
				));
                
						$this->addColumn('inquiry_type', array(
						'header' => Mage::helper('inquiry')->__('Type'),
						'index' => 'inquiry_type',
						'type' => 'options',
						'options'=>CodeMasterz_Inquiry_Block_Adminhtml_Inquiry_Grid::getOptionArray1(),				
						));
						
				$this->addColumn("name", array(
				"header" => Mage::helper("inquiry")->__("Name"),
				"index" => "name",
				));
				$this->addColumn("email", array(
				"header" => Mage::helper("inquiry")->__("Email"),
				"index" => "email",
				));
				$this->addColumn("phone_no", array(
				"header" => Mage::helper("inquiry")->__("Phone"),
				"index" => "phone_no",
				));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('inquiry_id');
			$this->getMassactionBlock()->setFormFieldName('inquiry_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_inquiry', array(
					 'label'=> Mage::helper('inquiry')->__('Remove Inquiry'),
					 'url'  => $this->getUrl('*/adminhtml_inquiry/massRemove'),
					 'confirm' => Mage::helper('inquiry')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray1()
		{
            $data_array=array(); 
			$data_array[0]='Get in Touch';
			$data_array[1]='Join Us';
            return($data_array);
		}
		static public function getValueArray1()
		{
            $data_array=array();
			foreach(CodeMasterz_Inquiry_Block_Adminhtml_Inquiry_Grid::getOptionArray1() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}