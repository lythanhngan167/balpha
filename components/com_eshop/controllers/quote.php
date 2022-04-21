<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

/**
 * EShop controller
 *
 * @package        Joomla
 * @subpackage     EShop
 * @since          1.5
 */
class EShopControllerQuote extends JControllerLegacy
{
	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 *
	 * Function to add a product to the quote
	 */
	public function add()
	{
		$quote = new EshopQuote();
		$json  = array();

		$productId = $this->input->getInt('id');
		$quantity  = $this->input->getInt('quantity', 0);

		if ($quantity <= 0)
		{
			$quantity = 1;
		}

		$options = $this->input->get('options', array(), 'array');
		$options = array_filter($options);

		//Validate options first
		$productOptions = EshopHelper::getProductOptions($productId, JFactory::getLanguage()->getTag());

		for ($i = 0; $n = count($productOptions), $i < $n; $i++)
		{
			$productOption = $productOptions[$i];

			if ($productOption->required && empty($options[$productOption->product_option_id]))
			{
				$json['error']['option'][$productOption->product_option_id] = $productOption->option_name . ' ' . JText::_('ESHOP_REQUIRED');
			}
		}

		if (!$json)
		{
			$product = EshopHelper::getProduct($productId, JFactory::getLanguage()->getTag());
			$quote->add($productId, $quantity, $options);
			$viewProductLink = JRoute::_(EshopRoute::getProductRoute($productId, EshopHelper::getProductCategory($productId)));
			$viewQuoteLink   = JRoute::_(EshopRoute::getViewRoute('quote'));

			$message                    = '<div>' . sprintf(JText::_('ESHOP_ADD_TO_QUOTE_SUCCESS_MESSAGE'), $viewProductLink, $product->product_name, $viewQuoteLink) . '</div>';
			$json['success']['message'] = $message;
			$json['time']               = time();
		}
		else
		{
			$json['redirect'] = JRoute::_(EshopRoute::getProductRoute($productId, EshopHelper::getProductCategory($productId)));
		}

		echo json_encode($json);

		JFactory::getApplication()->close();
	}

	/**
	 *
	 * Function to update quantity of a product in the quote
	 */
	public function update()
	{
		$session = JFactory::getSession();
		$session->set('success', JText::_('ESHOP_QUOTE_UPDATE_MESSAGE'));

		$key      = $this->input->getString('key');
		$quantity = $this->input->getInt('quantity');

		$quote = new EshopQuote();
		$quote->update($key, $quantity);
	}

	/**
	 *
	 * Function to update quantity of all products in the quote
	 */
	public function updates()
	{
		$session = JFactory::getSession();
		$session->set('success', JText::_('ESHOP_QUOTE_UPDATE_MESSAGE'));

		$key      = $this->input->get('key', array(), 'array');
		$quantity = $this->input->get('quantity', array(), 'array');

		$quote = new EshopQuote();
		$quote->updates($key, $quantity);
	}

	/**
	 *
	 * Function to remove a product from the quote
	 */
	public function remove()
	{
		$key   = $this->input->getString('key');
		$quote = new EshopQuote();

		$quote->remove($key);
		{
			JFactory::getSession()->set('success', JText::_('ESHOP_QUOTE_REMOVED_MESSAGE'));
		}
	}

	/**
	 * Function to process quote
	 */
	public function processQuote()
	{
		$post = $this->input->post->getArray();

		/* @var EShopModelQuote $model */
		$model = $this->getModel('Quote');
		$json  = $model->processQuote($post);
		echo json_encode($json);

		JFactory::getApplication()->close();
	}
}