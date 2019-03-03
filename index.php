<?php

require_once('dbscan.php');

// using the following distance matrix taken from:
// http://stats.stackexchange.com/questions/2717/clustering-with-a-distance-matrix
/* 
    A   B   C   D   E   F   G   H   I   J   K   L
A   0  20  20  20  40  60  60  60 100 120 120 120
B  20   0  20  20  60  80  80  80 120 140 140 140
C  20  20   0  20  60  80  80  80 120 140 140 140
D  20  20  20   0  60  80  80  80 120 140 140 140
E  40  60  60  60   0  20  20  20  60  80  80  80
F  60  80  80  80  20   0  20  20  40  60  60  60
G  60  80  80  80  20  20   0  20  60  80  80  80
H  60  80  80  80  20  20  20   0  60  80  80  80
I 100 120 120 120  60  40  60  60   0  20  20  20
J 120 140 140 140  80  60  80  80  20   0  20  20
K 120 140 140 140  80  60  80  80  20  20   0  20
L 120 140 140 140  80  60  80  80  20  20  20   0


Should find clusters of (A, B, C, D), (E, F, G, H) and (I, J, K, L)
*/

//create array of unique point ids
$point_ids = array('A','B','C','D');

// Create an upper diagonal version of the example distance matrix
$distance_matrix = array();

$distance_matrix['A'] = array('B' => 2,
							  'C' => 1,
							  'D' => 0.5);
$distance_matrix['B'] = array('C' => 1,
							  'D' => 1.5);
$distance_matrix['C'] = array('D' => 0.5);
$distance_matrix['D'] = array();


echo 'Point IDs:<br />';
print_r($point_ids);

// Setup DBSCAN with distance matrix and unique point IDs
$DBSCAN = new DBSCAN($distance_matrix, $point_ids);
$epsilon = 1;
$minpoints = 0;

// Perform DBSCAN clustering
$clusters = $DBSCAN->dbscan($epsilon, $minpoints);

//Output results
echo '<br /><br />Clusters (using epsilon = 30 and minpoints = 3): <br /><br />';
foreach ($clusters as $index => $cluster)
{
	if (sizeof($cluster) > 0)
	{
		echo 'Cluster number '.($index+1).':<br />';
		echo '<ul>';
		foreach ($cluster as $member_point_id)
		{
			echo '<li>'.$member_point_id.'</li>';
		}
		echo '</ul>';
	}
}

// Not useful for this example but below is how you would find sub-clusters within each of the clusters found
//
// The epsilon specified for sub-clusters should be less than the epsilon used to find the parent cluster
// otherwise the entire cluster will just be found as a sub-cluster
/*
foreach ($clusters as $index => $cluster)
{
	if (sizeof($cluster) > 0)
	{
		$DBSCAN->set_points($cluster);
		$sub_clusters = $DBSCAN->dbscan(21, 2);
		echo 'Sub clusters of cluster number '.($index+1).'<br />';
		foreach ($sub_clusters as $sub_cluster)
		{
			echo '<ul>';
			foreach ($sub_cluster as $sub_cluster_point_id)
			{
				echo '<li>'.$sub_cluster_point_id.'</li>';
			}
			echo '</ul>';
		}
	}
}
*/
?>