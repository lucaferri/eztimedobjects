<?php

class timedObjectsFunctionCollection{
	
	// Get ExpireSettings from timed_objects.ini.append.php
	public static function getExpireSettings(){
		return timedObjectsFunctionCollection::getSettings('Expire');
	}
	
	// Get PublishSettings from timed_objects.ini.append.php
	public static function getPublishSettings(){
		return timedObjectsFunctionCollection::getSettings('Publish');
	}
	
	// $type could be 'Expire' or 'Publish'
	public static function getSettings( $type ){
		
		// Check if $type parameter is valid
		if ( !in_array($type, array( 'Publish', 'Expire' )) )
			return false;
		
		$ini = eZINI::instance('timed_objects.ini.append.php');
		$ini = $ini->getNamedArray();
		$settings = $ini[$type."Settings"]['ClassAttribute'];
	
		$return = array();
		foreach ($settings as $setting){
			$class_attribute = explode( '/', $setting );
			$return = array_merge( $return, array(  $class_attribute[0] => $class_attribute[1] ) );
		}
	
		return $return;
	}
	
} 

?>