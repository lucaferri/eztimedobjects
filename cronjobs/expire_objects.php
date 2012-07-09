<?php
/*
 * Manage objects expire date
 */

$db = eZDB::instance();
$sql = "SELECT node_id FROM timed_objects WHERE time<=" . time() . " AND type='E'";
$result = $db->arrayQuery($sql);

eZLog::write('**************************', 'expire_objects.log');

// Hide expired nodes
foreach ($result as $res){
	eZLog::write('$node_id:' . $res['node_id'], 'expire_objects.log');
	$obj_node = eZContentObjectTreeNode::fetch($res['node_id']);
	
	// Check if the node no longer exists
	if (!is_object($obj_node)){
		eZLog::write('NODE ' . $res['node_id'] . " IS NOT AN OBJECT!", 'expire_objects.log');
		continue;
	}
	
	$obj = eZContentObject::fetch($obj_node->attribute('contentobject_id'));
	$assignedNodes = $obj->assignedNodes();
	// Hide all assigned nodes
	foreach ($assignedNodes as $node){
		eZLog::write('hiding node ' . $node->attribute('node_id'), 'expire_objects.log');
		if ($node->attribute('is_hidden') == 0)
			eZContentObjectTreeNode::hideSubTree($node);
		$sql = "DELETE FROM timed_objects WHERE node_id=" . $node->attribute('node_id') . " AND type='E'";
		$db->query($sql);
	}
}
?>