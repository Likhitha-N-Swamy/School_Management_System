<?php

include('../includes/config.php');
include('../includes/functions.php');

session_start();

$status = isset($_POST["status"]) ? $_POST["status"] : '';
$firstname = isset($_POST["firstname"]) ? $_POST["firstname"] : '';
$amount = isset($_POST["amount"]) ? $_POST["amount"] : '';
$txnid = isset($_POST["txnid"]) ? $_POST["txnid"] : '';
$posted_hash = isset($_POST["hash"]) ? $_POST["hash"] : '';
$key = isset($_POST["key"]) ? $_POST["key"] : '';
$productinfo = isset($_POST["productinfo"]) ? $_POST["productinfo"] : '';
$email = isset($_POST["email"]) ? $_POST["email"] : '';
$month = isset($_POST["udf1"]) ? $_POST["udf1"] : '';
$salt = "YourSaltValue"; // Replace with your actual salt value

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
$title = 'Payment Post'; // Add a meaningful title
$date = date('Y-m-d');

// Insert into `posts` table
$query = mysqli_query($db_conn, "INSERT INTO `posts` (`title`, `type`, `publish_date`, `status`, `author`) VALUES ('$title', 'payment', '$date', '$status', '$user_id')");

if ($query) {
    $item_id = mysqli_insert_id($db_conn);

    // Insert payment metadata
    $payment_data = array(
        'txn_id' => $txnid,
        'amount' => $amount,
        'firstname' => $firstname,
        'productinfo' => $productinfo,
        'status' => $status
    );

    foreach ($payment_data as $key => $value) {
        mysqli_query($db_conn, "INSERT INTO `metadata` (`item_id`, `meta_key`, `meta_value`) VALUES ('$item_id', '$key', '$value')");
    }

    // Handle months update
    $old_months = get_usermeta($user_id, 'months'); // Ensure `get_usermeta` function is defined properly
    if ($old_months) {
        $old_months_array = unserialize($old_months);
        $old_months_array[] = $month;
        $months = serialize($old_months_array);
        mysqli_query($db_conn, "UPDATE `usermeta` SET `meta_value` = '$months' WHERE `user_id` = '$user_id' AND `meta_key` = 'months'");
    } else {
        $months = serialize(array($month));
        mysqli_query($db_conn, "INSERT INTO `usermeta` (`user_id`, `meta_key`, `meta_value`) VALUES ('$user_id', 'months', '$months')");
    }
}

// Hash validation
if (isset($_POST["additionalCharges"])) {
    $additionalCharges = $_POST["additionalCharges"];
    $retHashSeq = $additionalCharges . '|' . $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
} else {
    $retHashSeq = $salt . '|' . $status . '|||||||||||' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
}

$hash = hash("sha512", $retHashSeq);

if ($hash !== $posted_hash) {
    $msg = "Invalid hash. Please try again.";
} else {
    if ($status == "success" && $txnid != "") {
        $msg = "Transaction completed successfully";
    } else {
        $msg = "Invalid Transaction. Please Try Again";
    }
}

echo $msg;
?>
