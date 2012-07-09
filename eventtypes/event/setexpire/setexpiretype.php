<?php 
class setexpireType extends eZWorkflowEventType
{
	const WORKFLOW_TYPE_STRING = 'setexpire';
	
	function setexpireType()
	{
		$this->eZWorkflowEventType(setexpireType::WORKFLOW_TYPE_STRING, 'set expire');
	}
	
	
	function execute($process, $event)
	{
		$parameters = $process->attribute( 'parameter_list' );
		$obj_id = $parameters['object_id'];
		$obj = eZContentObject::fetch($obj_id);
		
		$settings = timedObjectsFunctionCollection::getExpireSettings();
		
		if ( $settings[$obj->attribute('class_identifier')] ){
			$assignedNodes = $obj->assignedNodes();
			foreach ($assignedNodes as $node){
				$nodeID = $node->attribute('node_id');
				$dataMap = $node->dataMap();
				$db = eZDB::instance();
				$sql = "DELETE FROM timed_objects WHERE node_id = $nodeID AND type = 'E'";
				$db->query($sql);
				$sql = "INSERT INTO timed_objects (node_id, time, type) VALUES ($nodeID, " . $dataMap[ $settings[$obj->attribute('class_identifier')] ]->attribute('data_int') . ", 'E')";
				$result = $db->query($sql);
			}
		}else{
			// Nothing to do here
		}
	} // END Function execute
	
}							
												
eZWorkflowEventType::registerEventType(setexpireType::WORKFLOW_TYPE_STRING, 'setexpireType');
?>