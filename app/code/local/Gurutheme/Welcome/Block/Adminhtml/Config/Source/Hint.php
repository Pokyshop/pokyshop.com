<?php
class Gurutheme_Welcome_Block_Adminhtml_Config_Source_Hint
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        //$version = Mage::getConfig()->getModuleConfig('_')->version;
        $string ="<p style='font-size:14px;'>We warmly welcome all of you - inspired soul!
                    We are passionate designers, programmers and challenge takers providing you innovative, 
                    distinctive magento themes and extensions. We combine innovative solutions, artist designs 
                    with the latest technologies to implement ideas into our products. If you are looking for an 
                    effective look and feel for your store, you have come to the right place. Let's work together to make things happen.<p>
                    <p style='margin-top:10px;font-size:15px; font-weight:bold'>Visit gurutheme website : <a href='http://gurutheme.com'><strong>Gurutheme</strong></a></p>";
        return $string;
    }
}
