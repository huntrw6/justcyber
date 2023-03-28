<?php
// Check if flag is valid
$flag = ($_POST['flag']);

if ($flag === 'NOAH DID EVERYTHING JUST AS GOD COMMANDED HIM. GENESIS') {
  $result = array('valid' => true);
} else {
  $result = array('valid' => false);
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($result);
?>
