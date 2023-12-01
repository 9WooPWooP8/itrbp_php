<?php
abstract class AbsctractProduct
{
	public $base_price;
	abstract public function get_price();
}

class WeightedProduct extends AbsctractProduct
{
	public $weight;
	public function get_price()
	{
		return $this->weight * $this->base_price;
	}
}

class PieceProduct extends AbsctractProduct
{
	public $count;
	public function get_price()
	{
		return $this->count * $this->base_price;
	}
}

class DigitalProduct extends AbsctractProduct
{
	public $physical_product;

	public function get_price()
	{
		return $this->physical_product->get_price() / 2;
	}
}
