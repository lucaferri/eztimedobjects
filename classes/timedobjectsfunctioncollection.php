<?php

class timedObjectsFunctionCollection{
	
	public static function getExpireSettings(){
		$ini = eZINI::instance('timed_objects.ini.append.php');
		$ini = $ini->getNamedArray();
		$expireSettings = $ini['ExpireSettings']['ClassAttribute'];
		
		$return = array();
		foreach ($expireSettings as $setting){
			$class_attribute = explode( '/', $setting );
			$return = array_merge( $return, array(  $class_attribute[0] => $class_attribute[1] ) );
		} 
		
		return $return;
	}
	
	public static function getPublishSettings(){
		$ini = eZINI::instance('timed_objects.ini.append.php');
		$ini = $ini->getNamedArray();
		$publishSettings = $ini['PublishSettings']['ClassAttribute'];
	
		$return = array();
		foreach ($publishSettings as $setting){
			$class_attribute = explode( '/', $setting );
			$return = array_merge( $return, array(  $class_attribute[0] => $class_attribute[1] ) );
		}
	
		return $return;
	}
	
} 

?>