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
				timedObjectsFunctionCollection::setPublish($settings, $node);
			}
		}else{
			// Nothing to do here
		}
	} // END Function execute
	
}							
												
eZWorkflowEventType::registerEventType(hideobjectType::WORKFLOW_TYPE_STRING, 'hideobjectType');
?>