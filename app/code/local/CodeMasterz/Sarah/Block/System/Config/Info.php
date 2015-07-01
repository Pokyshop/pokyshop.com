<?php
/**
 * @category   MagePsycho
 * @package    MagePsycho_Easypathhints
 * @author     magepsycho@gmail.com
 * @website    http://www.magepsycho.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Codemasterz_Sarah_Block_System_Config_Info
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
         $html = '<div style="background:url(\'http://www.magepsycho.com/_logo.png\') no-repeat scroll 15px center #EAF0EE;border:1px solid #CCCCCC;margin-bottom:10px;padding:10px 5px 5px 200px;">
                    <h4>About CodeMasterz</h4>
                    <p>A Magento Developer / Freelancer with specialization in E-Commerce + Wordpress CMS Solutions.<br />
                    <a href="http://www.vaseemansari.com/contacts" target="_blank">Request a Quote / Contact Us</a><br />
                    Skype me @ vaseem.ansari<br />
					Email me @ <a href="mailto:vaseemansari007@gmail.com">vaseemansari007@gmail.com</a><br />
					Follow me on Twitter <a href="http://twitter.com/VaseemAnsari" target="_blank">@VaseemAnsari</a><br />
                    Visit my website:  <a href="http://www.vaseemansari.com" target="_blank">www.VaseemAnsari.com</a></p>
                </div>';

        return $html;
    }
}
