<?php
/*
 * Manage timed publication
 */

$db = eZDB::instance();
$sql = "SELECT node_id FROM timed_objects WHERE time<=" . time();
$result = $db->arrayQuery($sql);

eZLog::write('**************************', 'publish_objects.log');
eZLog::write('SQL Result:' . print_r($result, true), 'publish_objects.log');

// Unhide node returned from the query ($result)
foreach ($result as $res){
	eZLog::write('$node_id:' . $res['node_id'], 'publish_objects.log');
	$obj_node = eZContentObjectTreeNode::fetch($res['node_id']);

	// Check if the node no longer exists
	if (!is_object($obj_node)){
		eZLog::write('NODE ' . $res['node_id'] . " IS NOT AN OBJECT!", 'publish_objects.log');
		continue;
	}
	
	$obj = eZContentObject::fetch($obj_node->attribute('contentobject_id'));
	$assignedNodes = $obj->assignedNodes();
	foreach ($assignedNodes as $node){
		eZLog::write('unhide node ' . $node->attribute('node_id'), 'publish_objects.log');
		if ($node->attribute('is_hidden') == 1)
			eZContentObjectTreeNode::unhideSubTree($node);
		$sql = "DELETE FROM timed_objects WHERE node_id=" . $node->attribute('node_id');
		$db->query($sql);
	}
}

?>