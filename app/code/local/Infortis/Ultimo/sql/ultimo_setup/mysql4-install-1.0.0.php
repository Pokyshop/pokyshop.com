<?php
/**
 * @package		Infortis_Ultimo
 * @author		Infortis
 * @copyright	Copyright 2012 - 2013 Infortis
 */
$installer = $this;
$installer->startSetup();

//WYSIWYG hidden by default
Mage::getConfig()->saveConfig('cms/wysiwyg/enabled', 'hidden');

$installer->endSetup();
