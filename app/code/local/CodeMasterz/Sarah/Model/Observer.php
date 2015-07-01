<?php
class Codemasterz_Sarah_Model_Observer
{
	public function generateCustomerSpecificCouponCode($observer){
		//return true;
		
	$name =	'vaseem';
	$coupon_code = 'testert';
  if ($name != null && $coupon_code != null)
  {
    $rule = Mage::getModel('salesrule/rule');
    $customer_groups = array(0, 1, 2, 3);
    $rule->setName($name)
      ->setDescription($name)
      ->setFromDate('')
      ->setCouponType(2)
      ->setCouponCode($coupon_code)
      ->setUsesPerCustomer(1)
      ->setCustomerGroupIds($customer_groups) //an array of customer grou pids
      ->setIsActive(1)
      ->setConditionsSerialized('')
      ->setActionsSerialized('')
      ->setStopRulesProcessing(0)
      ->setIsAdvanced(1)
      ->setProductIds('')
      ->setSortOrder(0)
      ->setSimpleAction('cart_fixed')
      ->setDiscountAmount($discount)
      ->setDiscountQty(null)
      ->setDiscountStep(0)
      ->setSimpleFreeShipping('0')
      ->setApplyToShipping('0')
      ->setIsRss(0)
      ->setWebsiteIds(array(1));

    $item_found = Mage::getModel('salesrule/rule_condition_product_found')
      ->setType('salesrule/rule_condition_product_found')
      ->setValue(1) // 1 == FOUND
      ->setAggregator('all'); // match ALL conditions
    $rule->getConditions()->addCondition($item_found);
    $conditions = Mage::getModel('salesrule/rule_condition_product')
      ->setType('salesrule/rule_condition_product')
      ->setAttribute('sku')
      ->setOperator('==')
      ->setValue($sku);
    $item_found->addCondition($conditions);

    $actions = Mage::getModel('salesrule/rule_condition_product')
      ->setType('salesrule/rule_condition_product')
      ->setAttribute('sku')
      ->setOperator('==')
      ->setValue($sku);
    $rule->getActions()->addCondition($actions);
    $rule->save();
  }
die;
		
		$customer 		= 	$observer->getEvent()->getCustomer();
		//echo '<pre>'; print_r($customer);
		$customerId		=	$customer->getId();
		$customerGroupId=	$customer->getGroupId(); 
		$discount		=	'30';
		
        /*if (($customer instanceof Mage_Customer_Model_Customer)) {
            //Mage::getModel('newsletter/subscriber')->subscribeCustomer($customer);
			echo 'yes';
        }*/
		
		//$this->generateRuleAction();
		$this->createCoupon($customerId, $discount);
		
		die;
        return $this;
	}
	
	public function createCoupon($customer_id, $discount)
	{
		
		/**
 * Create Shopping Cart Sales Rule with Specific Coupon Code Programmatically
 */
 
// All customer group ids
$customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();

// SalesRule Rule model
$rule = Mage::getModel('salesrule/rule');
 
// Rule data
$rule->setName('Rule name')                                             
    ->setDescription('Rule description')
    ->setFromDate('')
    ->setCouponType(Mage_SalesRule_Model_Rule::COUPON_TYPE_SPECIFIC)
    ->setCouponCode('my-coupon-code')
    ->setUsesPerCustomer(1)
    ->setUsesPerCoupon(1)
    ->setCustomerGroupIds($customerGroupIds)
    ->setIsActive(1)
    ->setConditionsSerialized('')
    ->setActionsSerialized('')
    ->setStopRulesProcessing(0)
    ->setIsAdvanced(1)
    ->setProductIds('')
    ->setSortOrder(0)
    ->setSimpleAction(Mage_SalesRule_Model_Rule::BY_FIXED_ACTION)
    ->setDiscountAmount(10)
    ->setDiscountQty(1)
    ->setDiscountStep(0)
    ->setSimpleFreeShipping('0')
    ->setApplyToShipping('0')
    ->setIsRss(0)
    ->setWebsiteIds(array(1))
    ->setStoreLabels(array('My Rule Frontend Label'));
 
// Product found condition type
$productFoundCondition = Mage::getModel('salesrule/rule_condition_product_found')
    ->setType('salesrule/rule_condition_product_found')
    ->setValue(1)               // 0 == not found, 1 == found
    ->setAggregator('all');     // match all conditions
 
// 'Attribute set id 1' product condition
$attributeSetCondition = Mage::getModel('salesrule/rule_condition_product')
    ->setType('salesrule/rule_condition_product')
    ->setAttribute('attribute_set_id')
    ->setOperator('==')
    ->setValue(1);
    
// Bind attribute set condition to product found condition
$productFoundCondition->addCondition($attributeSetCondition);
 
// If a product with 'attribute set id 1' is found in the cart
$rule->getConditions()->addCondition($productFoundCondition);

// Only apply the rule discount to this specific product
$rule->getActions()->addCondition($attributeSetCondition);
 
// Here we go
//$rule->save()
try {
	echo 'created';
	$rule->save();
} catch (Exception $e) {
	echo $e->getMessage();die;
	//Mage::log($e->getMessage());
}

die;
		$customer = Mage::getModel('customer/customer')->load($customer_id);
		//echo '<pre>';print_r($customer);die;
	
		$customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();
		$websitesId = Mage::getModel('core/website')->getCollection()->getAllIds();
	
		$customer_name = $customer->getName();
		$couponCode = Mage::helper('core')->getRandomString(9);
		echo $couponCode;
	
		$model = Mage::getModel('salesrule/rule');
		$model->setName('Discount for ' . $customer_name);
		$model->setDescription('Discount for ' . $customer_name);
		$model->setFromDate(date('Y-m-d'));
		$model->setToDate(date('Y-m-d', strtotime('+2 days')));
		$model->setCouponType(2);
		$model->setCouponCode($couponCode);
		$model->setUsesPerCoupon(1);
		$model->setUsesPerCustomer(1);
		$model->setCustomerGroupIds($customerGroupIds);
		$model->setIsActive(1);
		//$model->setConditionsSerialized('a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		//$model->setActionsSerialized('a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setStopRulesProcessing(0);
		$model->setIsAdvanced(1);
		$model->setProductIds('');
		$model->setSortOrder(1);
		$model->setSimpleAction('by_fixed');
		$model->setDiscountAmount($discount);
		$model->setDiscountStep(0);
		$model->setSimpleFreeShipping(0);
		$model->setTimesUsed(0);
		$model->setIsRss(0);
		$model->setWebsiteIds($websitesId);
	
		try {
			echo 'created';
			$model->save();
		} catch (Exception $e) {
			echo $e->getMessage();die;
			Mage::log($e->getMessage());
			
		}
	}
	

	
	public function generateRuleAction()
	{
		$rndId = crypt(uniqid(rand(),1));
		$rndId = strip_tags(stripslashes($rndId));
		$rndId = str_replace(array(".", "$"),"",$rndId);
		$rndId = strrev(str_replace("/","",$rndId));
		if (!is_null($rndId))
		{
			strtoupper(substr($rndId, 0, 5));
		}
	
		$groups = array();
		foreach ($customerGroups as $group)
		{
			$groups[] = $group->getId();
		}
	
		$websites = Mage::getModel('core/website')->getCollection();
		$websiteIds = array();
		foreach ($websites as $website)
		{
			$websiteIds[] = $website->getId();
		}
	
		 $uniqueId = strtoupper($rndId);
		 echo $uniqueId;
		 $uniqueId = 'vaseem30';
		 
		 $rule = Mage::getModel('salesrule/rule');
		 $rule->setName($uniqueId);
		 $rule->setDescription('Generated for Test Purposes');
		 $rule->setFromDate(date('Y-m-d'));//starting today
		 //$rule->setToDate('2011-01-01');//if an expiration date's needed
		 $rule->setCouponCode($uniqueId);
		 $rule->setUsesPerCoupon(1);//number of allowed uses for this coupon
		 $rule->setUsesPerCustomer(1);//number of allowed uses for this coupon for each customer
		$customerGroups = Mage::getModel('customer/group')->getCollection();
	
		$rule->setCustomerGroupIds($groups); 
		$rule->setIsActive(1);
		$rule->setStopRulesProcessing(0);//set to 1 if you want all other rules after this to not be processed
		$rule->setIsRss(0);//set to 1 if you want this rule to be public in rss
		$rule->setIsAdvanced(1);
		$rule->setProductIds('');   
		$rule->setSortOrder(0);// order in which the rules will be applied
		$rule->setSimpleAction('by_percent');
	
		$rule->setDiscountAmount('20');//the discount amount/percent. 
		//if SimpleAction is by_percent this value must be <= 100
		$rule->setDiscountQty(0);//Maximum Qty Discount is Applied to
		$rule->setDiscountStep(0);//used for buy_x_get_y; This is X
		$rule->setSimpleFreeShipping(0);//set to 1 for Free shipping
		$rule->setApplyToShipping(1);//set to 0 if you don't want the rule to be applied to shipping
	
		$rule->setWebsiteIds($websiteIds); 
	
		$conditions = array();
		$conditions[1] = array(
		'type' => 'salesrule/rule_condition_combine',
		'aggregator' => 'all',
		'value' => 1,
		'new_child' => ''
		);
	
		$conditions['1--1'] = Array
		(
		'type' => 'salesrule/rule_condition_address',
		'attribute' => 'base_subtotal',
		'operator' => '>=',
		'value' => 200
		);
	
	
		$labels = array();
		$labels[0] = 'Default store label';//default store label
		$labels[1] = 'Label for store with id 1';
		//....
		$labels[n] = 'Label for store with id n';
		//add one line for each store view you have. The key is the store view ID
		$rule->setData('conditions',$conditions);
		$rule->loadPost($rule->getData());
		$rule->setCouponType(2);
		$rule->setStoreLabels($labels);
		$rule->save();
	
	}
	
}