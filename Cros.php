<?php

/**
 * Cros Class
 * 
 * This script allow cross-site scripting in php servers
 * 
 * @example
 * 
 * require_once 'Cros.php';
 * 
 * // Initialization ( __contructor($ip) )
 * $config = [
 *     'origin' => ['localhost','example.com'], # array of allowed origin if empty allow cross-site
 *     'credentials' => false, # allowed credentials headers (defualt true)
 *     'max_age' => 86400, # Time in seconds request needs to be cached for,
 *     'methods' => ['POST','GET','OPTIONS'] // array of allowed request method N.B is you making async request always include OPTIONS method
 * ];
 * $cros = new Cros($config);
 * 
 * // allow ajax request and set response content-type to (json)
 * $cros->ajax()->header('Content-type', 'application/json');
 * 
 */
class Cros
{

    /**
     * Allowed host or ip address
     * 
     * @var array
     */
    protected $origin = [];

    /**
     * Allow request credentials headers
     * 
     * @var boolean
     */
    protected $credentials = true;

    /**
     * Time in seconds request to be cached in server
     * 
     * @var integer
     */
    protected $max_age = 86400;

    /**
     * Allowed request methods types
     * 
     * @var array
     */
    protected $methods = ['POST','GET','OPTIONS'];

    /**
     * Call when class is frist created
     * 
     * @return $this
     */
    public function __construct(array $config = [])
    {
        $this->config($config);

        return $this;
    }

    /**
     * Load custom configurations
     * 
     * @param array
     * @return $this
     */
    public function config(array $config)
    {
        foreach($config as $key => $value)
            $this->{strtolower($key)} = $value;

        return $this;
    }

    /**
     * Allow ajax request with OPTION header
     * 
     * @return $this
     */
    public function ajax()
    {
        // check if cross origin request
        if (isset($_SERVER["HTTP_ORIGIN"]))
            $this->allow_http_orgin();

        // check if request type is OPTIONS request
        if ($_SERVER["REQUEST_METHOD"] == 'OPTIONS') {
            $this->allow_options_request();
            exit(0);
        }

        return $this;
    }

    /**
     * Set row http header
     * 
     * @param string $key
     * @param mixed $value
     * @param string $spit
     * @return $this
     */
    public function header(string $key, $value, string $spit = ", ")
    {
        if(is_array($value))
            $value = implode("{$spit}", $value);

        header("{$key}: {$value}");
        
        return $this;
    }

    /**
     * Allow cross-site requests
     * 
     * @return void
     */
    protected function allow_http_orgin()
    {
        $this->header("Access-Control-Allow-Origin", count($this->origin) == 0 ? $_SERVER["HTTP_ORIGIN"] : $this->origin);
        $this->header("Access-Control-Allow-Credentials", $this->credentials ? "true" : "false");
        $this->header("Access-Control-Max-Age", $this->max_age);
    }

    /**
     * Allow OPTIONS requests in server
     * 
     * @return void
     */
    protected function allow_options_request()
    {
        if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
            $this->header("Access-Control-Allow-Methods", $this->methods);
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            $this->header("Access-Control-Allow-Headers", $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
    }

}
