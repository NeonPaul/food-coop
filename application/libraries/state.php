<?
class State {
  function __construct() {
    $this->CI =& get_instance();
    $this->CI->config->load("file_locations");
    $this->stateFile = $this->CI->config->item('data_path') . "state.json";
  }

  function readJson() {
    $decoded = (array) @json_decode(file_get_contents($this->stateFile));
    return $decoded ? $decoded : array();
  }

  public function get($key, $fallback) {
    $state = $this->readJson();
    return isset($state[$key]) ? $state[$key] : $fallback;
  }

  public function set($item, $value) {
    $state = $this->readJson();
    $state[$item] = $value;
    $result = file_put_contents($this->stateFile, json_encode($state));
    if ($result === false) {
      throw new Exception('Could not write to file '.$this->stateFile);
    }
  }
}
?>
