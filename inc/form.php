<?php

$value = get_post_meta( bbp_get_topic_id(), 'bbp_extra_field1', true );
echo '<label for="bbp_extra_field1">Extra Field 1</label><br>';
echo '<input type="text" name="bbp_extra_field1" value="' . $value . '"><br>';

$department = get_post_meta( bbp_get_topic_id(), 'bbp_extra_field1', true );
$department_values = array( 
	'Basement', 
	'Electrical', 
	'Elevator', 
	'Heat', 
	'House Keeping', 
	'Inside Shareholder Unit',
	'Maintenance',
	'Other'
	);
echo '<label for="department">Department</label><br>';
echo '<select name="department">';
foreach( $department_values as $key => $value ){
	echo '<option>' . $value . '</option>';
}
echo '</select>';
