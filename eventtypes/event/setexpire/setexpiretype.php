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
		
		if ( isset($settings[$obj->attribute('class_identifier')]) ){
			$assignedNodes = $obj->assignedNodes();
			foreach ($assignedNodes as $node){
				timedObjectsFunctionCollection::setExpire( $settings, $node );
			}
		}else{
			// Nothing to do here
		}
	} // END Function execute
	
}							
												
eZWorkflowEventType::registerEventType(setexpireType::WORKFLOW_TYPE_STRING, 'setexpireType');
?>