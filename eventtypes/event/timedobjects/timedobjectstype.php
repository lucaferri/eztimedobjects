<?php 
class timedobjectsType extends eZWorkflowEventType
{
	const WORKFLOW_TYPE_STRING = 'timedobjects';
	
	function timedobjectsType()
	{
		$this->eZWorkflowEventType(timedobjectsType::WORKFLOW_TYPE_STRING, 'timed objects');
	}
	
	
	function execute($process, $event)
	{
		$parameters = $process->attribute( 'parameter_list' );
		$obj_id = $parameters['object_id'];
		$obj = eZContentObject::fetch($obj_id);
		
		// Manage expiration
		$settings = timedObjectsFunctionCollection::getExpireSettings();
		
		if ( isset($settings[$obj->attribute('class_identifier')]) ){
			$assignedNodes = $obj->assignedNodes();
			foreach ($assignedNodes as $node){
				timedObjectsFunctionCollection::setExpire( $settings, $node );
			}
		}else{
			// Nothing to do here
		}
		// END Manage expiration
		
		// Manage publication
		$settings = timedObjectsFunctionCollection::getPublishSettings();
		
		if ( isset($settings[$obj->attribute('class_identifier')]) ){
			$assignedNodes = $obj->assignedNodes();
			foreach ($assignedNodes as $node){
				timedObjectsFunctionCollection::setPublish($settings, $node);
			}
		}else{
			// Nothing to do here
		}
		// END Manage publication
		
	} // END Function execute
	
}							
												
eZWorkflowEventType::registerEventType(timedobjectsType::WORKFLOW_TYPE_STRING, 'timedobjectsType');
?>