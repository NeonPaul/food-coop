<?
class State{
	function __construct(){
	$this->CI =& get_instance();
	$this->CI->config->load("file_locations");
	$this->state_dir=$this->CI->config->item('data_path')."state/";
	}
	public function get($item){
	return file_get_contents($this->state_dir.$item);
	}
	public function set($item,$value){
	return file_put_contents($this->state_dir.$item,$value);
	}
	}
	?>