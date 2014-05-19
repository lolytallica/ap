<?php

class Constants
{
	private function __construct ()
	{
		$this->values = array ();
                $this->values['mysqlDatabase'] = 'validation_future';
                $this->values['mysqlHost'] = 'localhost';
                $this->values['mysqlPassword'] = 'UWBAvOT01pqq';
                $this->values['mysqlUser'] = 'validation_dev';
                $this->values['FacebookAppId'] = '398187670246096';
                $this->values['FacebookSecId'] = 'b3c3aab61e3ac8422afdce8152f75f71';
                $this->values["domain"] = "https://demo.validated.eu/";
                $this->values["crypt_key"] = "asd37sWKseiwzkahsdkfajw2342342";
                $this->values["root_url"] = "https://dev.validationportal.eu"; 
        
	}

	public function __get ($name)
	{
		if (!array_key_exists ($name, $this->values))
			throw new Exception ('Constant "' . $name . '" does not exist.');

		return $this->values[$name];
	}

	public static function GetInstance ()
	{
		if (!isset (self::$instance))
			self::$instance = new self ();

		return self::$instance;
	}

	private $values;

	private static $instance;
}

?>
