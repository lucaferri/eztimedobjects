<?php

class timedObjectsFunctionCollection{
	
	/**
     * Get ExpireSettings from timed_objects.ini.append.php
     * @return Array hash of ini configurations
     */
	public static function getExpireSettings(){
		return timedObjectsFunctionCollection::getSettings('Expire');
	}
	
	/**
     *  Get PublishSettings from timed_objects.ini.append.php
     *  @return Array hash of ini configurations
     */
	public static function getPublishSettings(){
		return timedObjectsFunctionCollection::getSettings('Publish');
	}
	
	/**
	 * Get Settings from timed_objects.ini.append.php
	 * @param String $type could be 'Expire' or 'Publish'
	 * @return Array hash of ini configurations
	 */
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
	
	/**
	 * This function manage publications (used inside hideobjecttype.php)
	 * @param Array $publishSettings settings read from ini
	 * @param eZContentObjectTreeNode $node node to publish
	 */
	public static function setPublish( $publishSettings, $node ){
		$nodeID = $node->attribute('node_id');
		$dataMap = $node->dataMap();
		$obj = eZContentObject::fetch($node->attribute('contentobject_id'));
		if ($dataMap[ $publishSettings[$obj->attribute('class_identifier')] ]->attribute('data_int') > time()){
			eZContentObjectTreeNode::hideSubTree($node);
			$db = eZDB::instance();
			$sql = "DELETE FROM timed_objects WHERE node_id = $nodeID AND type = 'P'";
			$result = $db->query($sql);
			$sql = "INSERT INTO timed_objects (node_id, time, type) VALUES ($nodeID, " . $dataMap[ $publishSettings[$obj->attribute('class_identifier')] ]->attribute('data_int') . ", 'P')";
			$result = $db->query($sql);
		}else{
			if ($node->attribute('is_hidden') == 1)
				eZContentObjectTreeNode::unhideSubTree($node);
		}
	}
	
	/**
	 * This function manage expiration (used inside hideobjecttype.php)
	 * @param Array $publishSettings settings read from ini
	 * @param eZContentObjectTreeNode $node node to expire
	 */
	public static function setExpire( $expireSettings, $node ){
		$nodeID = $node->attribute('node_id');
		$dataMap = $node->dataMap();
		$obj = eZContentObject::fetch($node->attribute('contentobject_id'));
		if ($dataMap[ $expireSettings[$obj->attribute('class_identifier')] ]->attribute('data_int') > time()){
			$db = eZDB::instance();
			$sql = "DELETE FROM timed_objects WHERE node_id = $nodeID AND type = 'E'";
			$db->query($sql);
			$sql = "INSERT INTO timed_objects (node_id, time, type) VALUES ($nodeID, " . $dataMap[ $expireSettings[$obj->attribute('class_identifier')] ]->attribute('data_int') . ", 'E')";
			$result = $db->query($sql);
		}else{
			if ($node->attribute('is_hidden') == 0)
				eZContentObjectTreeNode::hideSubTree($node);
		}
	}
	
} 

?>