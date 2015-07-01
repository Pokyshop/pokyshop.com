<?php
class Codemasterz_Sarah_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("This is title of frontend page"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("this is title of frontend page", array(
                "label" => $this->__("This is title of frontend page"),
                "title" => $this->__("This is title of frontend page")
		   ));

      $this->renderLayout(); 
	  
    }
}