<?php
/**

 * This package designed for Magento COMMUNITY edition
 * Gurutheme does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Gurutheme does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   Gurutheme
 * @package    Gurutheme_Welcome
 * @version    1.0.1
 * @copyright  Copyright (c) 2010-2012 Gurutheme Co. (https://www.gurutheme.com)
 */


class Gurutheme_Welcome_Block_Jsinit extends Mage_Adminhtml_Block_Template
{


    protected $_platform = -1;
    protected $_extensions_cache = array();
    protected $_extensions;

    protected $_section = '';
    protected $_store_data = null;

    /**
     * Include JS in head if section is moneybookers
     */
    protected function _prepareLayout()
    {
        $this->_section = $this->getAction()->getRequest()->getParam('section', false);
       if ($this->_section == 'gurustore') {
           
            $this->getLayout()
                    ->getBlock('head')
                    ->addJs('gurutheme/gurutheme.js');
            $this->setData('store_data', $this->_getStoreData());
        }
        parent::_prepareLayout();
    }

    /**
     * Print init JS script into body
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->_section == 'gurustore') {
            return parent::_toHtml();
        } else {
            return '';
        }
    }

   
    /**
     * Convert version to comparable integer
     * @param $v
     * @return int
     */
    protected function _convertVersion($v)
    {
        $digits = @explode(".", $v);
        $version = 0;
        if (is_array($digits)) {
            foreach ($digits as $k => $v) {
                $version += ($v * pow(10, max(0, (3 - $k))));
            }
        }
        return $version;
    }


    /**
     * Get extension info from cached feed
     * @param $moduleName
     * @return bool|Varien_Object
     */
    public function getExtensionInfo($moduleName)
    {
        if (!sizeof($this->_extensions_cache)) {
            if ($displayNames = Mage::app()->loadCache('aw_all_extensions_feed')) {
                $this->_extensions_cache = @unserialize($displayNames);
            }
        }
        if (array_key_exists($moduleName, $this->_extensions_cache)) {
            $data = array(
                'url' => @$this->_extensions_cache[$moduleName]['url'],
                'display_name' => @$this->_extensions_cache[$moduleName]['display_name'],
                'latest_version' => @$this->_extensions_cache[$moduleName]['version']
            );
            return new Varien_Object($data);
        }
        return new Varien_Object();
    }

    /**
     * Return icon for installed extension
     * @param $Extension
     * @return Varien_Object
     */
    public function getIcon($Extension)
    {
        if ($Extension->getUpgradeAvailable()) {
            $icon = 'aw_all/images/update.gif';
            $title = "Update available";
        } elseif (!$Extension->getIsPlatformValid()) {
            $icon = 'aw_all/images/bad.gif';
            $title = "Wrong Extension Platform";
        } else {
            $icon = 'aw_all/images/ok.gif';
            $title = "Installed and up to date";
        }
        return new Varien_Object(array('title' => $title, 'source' => $this->getSkinUrl($icon)));
    }

    /**
     * Fetch store data and return as Varien Object
     * @return Varien_Object
     */
    protected function _getStoreData()
    {
        if (!is_null($this->_store_data))
            return $this->_store_data;
        $storeData = array();
        $connection = $this->_getStoreConnection();
        $storeResponse = $connection->read();

        if ($storeResponse !== false) {
            $storeResponse = preg_split('/^\r?$/m', $storeResponse, 2);
            $storeResponse = trim($storeResponse[1]);
            Mage::app()->saveCache($storeResponse, Gurutheme_Welcome_Helper_Config::STORE_RESPONSE_CACHE_KEY);
        }
        else {
            $storeResponse =  Mage::app()->loadCache(Gurutheme_Welcome_Helper_Config::STORE_RESPONSE_CACHE_KEY);
            if (!$storeResponse) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Sorry, but Extensions Store is not available now. Please try again in a few minutes.'));
            }
        }

        $connection->close();
        $this->_store_data = new Varien_Object(array('text_response' => $storeResponse));
        return $this->_store_data;
    }

    /**
     * Returns URL to store
     * @return Varien_Http_Adapter_Curl
     */
    protected function _getStoreConnection()
    {
        $params = array(

        );
        $url = array();
        foreach ($params as $k => $v) {
            $url[] = urlencode($k) . "=" . urlencode($v);
        }
        $url = rtrim(Gurutheme_Welcome_Helper_Config::STORE_URL) . (sizeof($url) ? ("?" . implode("&", $url)) : "");
        $curl = new Varien_Http_Adapter_Curl();
        $curl->setConfig(array(
                              'timeout' => 5
                         ));
        $curl->write(Zend_Http_Client::GET, $url, '1.0');

        return $curl;
    }


}
 
