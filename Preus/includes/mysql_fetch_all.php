<?php
function mysql_fetch_all($array) {
	   	while($row=@mysql_fetch_array($array)) {
		       $return[] = $row;
		}
		return $return;
}
?>