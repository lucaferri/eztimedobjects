<?php 
class hideobjectType extends eZWorkflowEventType
{
	const WORKFLOW_TYPE_STRING = 'hideobject';
	
	function hideobjectType()
	{
		$this->eZWorkflowEventType(hideobjectType::WORKFLOW_TYPE_STRING, 'hide object');
	}
	
	
	function execute($process, $event)
	{
		$parameters = $process->attribute( 'parameter_list' );
		$obj_id = $parameters['object_id'];
		$obj = eZContentObject::fetch($obj_id);
		
		$settings = timedObjectsFunctionCollection::getPublishSettings();
		
		if ( $settings[$obj->attribute('class_identifier')] ){
			$assignedNodes = $obj->assignedNodes();
			foreach ($assignedNodes as $node){
				$nodeID = $node->attribute('node_id');
				$dataMap = $node->dataMap();
				if ($dataMap[ $settings[$obj->attribute('class_identifier')] ]->attribute('data_int') > time()){
					eZContentObjectTreeNode::hideSubTree($node);
					$db = eZDB::instance();
					$sql = "DELETE FROM timed_objects WHERE node_id = $nodeID AND type = 'P'";
					$result = $db->query($sql);
					$sql = "INSERT INTO timed_objects (node_id, time, type) VALUES ($nodeID, " . $dataMap[ $settings[$obj->attribute('class_identifier')] ]->attribute('data_int') . ", 'P')";
					$result = $db->query($sql);
				}else{
					if ($node->attribute('is_hidden') == 1)
						eZContentObjectTreeNode::unhideSubTree($node);
				}
			}
		}
	} // END Function execute
	
}							
												
eZWorkflowEventType::registerEventType(hideobjectType::WORKFLOW_TYPE_STRING, 'hideobjectType');
?>