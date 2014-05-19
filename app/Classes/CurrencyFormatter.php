<?php

namespace Classes;

class CurrencyFormatter extends NumberFormatter {
	
	/**
	 * Formats currency according to FI config
	 * @param  float $amount
	 * @return string
	 */
	public static function format($amount)
	{
		$amount = parent::format($amount);
		
		if (Config::get('invoice.currencySymbolPlacement') == 'before')
		{
			return Config::get('invoice.currencySymbol') . $amount;
		}

		return $amount . Config::get('invoice.currencySymbol');
	}
}