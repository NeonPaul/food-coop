<?
function clean_close_tags($str){
	$opentags=array();
	$match="<";
	$tagstart=0;
	$opener=true;
	for($i=0; $i<strlen($str); $i++){
		if(strpos($match,substr($str,$i,1))===false)
			continue;
		if($match=="<"){
			$opener=!(substr($str,$i+1,1)=="/");
			$i=$tagstart=$i+1+!$opener;
			$match=" >-";
		}else{
			$tagname=substr($str,$tagstart,$i-$tagstart);
			if(!isset($opentags[$tagname]))
				$opentags[$tagname]=0;
			if($opener){
				$opentags[$tagname]++;
			}else{
				$opentags[$tagname]--;
				if($opentags[$tagname]<0){
					$str=substr($str,0,$tagstart-2).substr($str,$i+1);
					$i-=(strlen($tagname)+3);
					$opentags[$tagname]=0;
				}
			}
			$match="<";
		}
	}
	return $str;
}