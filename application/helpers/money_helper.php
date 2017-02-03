<?if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('unit_price'))
{
	function unit_price_array($a,$q=1,$member=1,$format=0){
		$unit_price = ceil((($member?1:1.05)*$a['wholesale_cost']/$a['wholesale_qtty'])/5)*5/100;

		$price = floor($q/$a['wholesale_qtty'])*$a['wholesale_cost']/100
				+ ($q % $a['wholesale_qtty']) * $unit_price;


		return $format ? price_format($price) : $price;
	}

    function unit_price($item,$wholesale_quantity=1,$unit_quantity=1,$vat=false,$format=true)
    {
		if(is_object($item)){
			$wholesale_price=$item->wholesale_cost;
			$wholesale_quantity=$item->wholesale_qtty;
			$unit_quantity=func_get_arg(1);
			$member=func_get_arg(2);
			$format=func_get_arg(3);
		}else{
			$wholesale_price=func_get_arg(0);
		}
		$unit_price = ceil(($wholesale_price/$wholesale_quantity)/5)*5/100;

		$price = floor($unit_quantity/$wholesale_quantity)*$wholesale_price/100
				+ ($unit_quantity % $wholesale_quantity) * $unit_price;

		return $format ? price_format($price) : $price;
    }

	function unit_price_members($wholesale_price,$wholesale_quantity=1,$unit_quantity=1,$vat=false,$format=true)
    {
		$unit_price = ceil((1.05*$wholesale_price/$wholesale_quantity)/5)*5/100;

		$price = floor($unit_quantity/$wholesale_quantity)*$wholesale_price/100
				+ ($unit_quantity % $wholesale_quantity) * $unit_price;


		return $format ? price_format($price) : $price;
    }

  function price_format($money){
    return "£".number_format($money,2);
  }
}?>
