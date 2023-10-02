<?php

class Cart
{
	private $products;

	public function add_to_cart($product)
	{
		array_push($this->products, $product);
	}

	public function get_total_cost()
	{
		$sum = 0;

		foreach ($this->products as $product) {
			$sum += $product->get_price();
		}

		return $sum;
	}

	public function buy_products()
	{
		$this->products = array();
	}
}
