<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table cmz_inquiry(inquiry_id int not null auto_increment, inquiry_type varchar(100), name varchar(100), email varchar(100), phone_no varchar(20), comment text, primary key(inquiry_id));
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 