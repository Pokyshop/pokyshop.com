<?php
class Codemasterz_Sarah_Helper_Data extends Mage_Core_Helper_Abstract{
	/**
	Global function to return all the active categories
	Usage	-	$categoryHelper = Mage::helper("sarah")->getCategoriesDropdown(); 
	**/
	public function getCategoriesDropdown() {
		$excludeCategoriesArray =	array(2);
		// Get category collection
		$categoriesArray = Mage::getModel('catalog/category')
								->getCollection()
								->addAttributeToSelect('name')
								->addAttributeToSort('path', 'asc')
								->addFieldToFilter('is_active', array('eq'=>'1'))
								->load()
								->toArray();
	
		// Arrange categories in required array
		foreach ($categoriesArray as $categoryId => $category) {
			if ( isset($category['name']) && !in_array($categoryId,$excludeCategoriesArray) ) {
				$categories[] = array(
					'label' => $category['name'],
					'level'  =>$category['level'],
					'value' => $categoryId
				);
			}
		}
		return $categories;
	}
	
	public function getUserName()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return trim($customer->getName());
    }
	
	public function getUserEmail()
    {
        if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
            return '';
        }
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getEmail();
    }
	
	public function getCssVersion()
    {
        return Mage::getStoreConfig('sarah/cache_settings/css_cache_version');		/*	module name / group name / field name	*/
    }
	
	public function getJsVersion()
    {
        return Mage::getStoreConfig('sarah/cache_settings/js_cache_version');
    }
}
	 