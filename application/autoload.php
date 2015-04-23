<?php
require_once(dirname(__FILE__).'/models/Autoload.class.php');

spl_autoload_register(array(Autoload::getInstance(), 'load'));
