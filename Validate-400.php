<?php
// Check if flag is valid
$search = ($_POST['search']);

if ($flag === 'NOAH DID EVERYTHING JUST AS GOD COMMANDED HIM. GENESIS') {
  $result = array('valid' => true);
} else {
  $result = array('valid' => false);
}

// Return a response
echo "<p>Search results for: " . $search . "</p>";
?>
// Return JSON response
//header('Content-Type: application/json');
//echo json_encode($result);
?>
