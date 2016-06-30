<?php
require_once '../api/autoload.php';
auth();
$target_dir = "../assets/images/";
if (isset($_GET['delete'])) {
    @unlink($target_dir . $_GET['delete']);
    return api("true");
    die();
}
$db = new DB();
$p1 = $p2 = [];
if (empty($_FILES['image']['name'])) {
    echo '{}';
    return;
}
for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
    $j = $i + 1;
    $target_file = $target_dir . basename($_FILES['image']['name'][$i]);

    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    $fName = md5(date("Y-m-d H:i:s") . rand(0, 50)) . "." . $imageFileType;
    $target_file = $target_dir . $fName;
    $target_file_sm = $target_dir . "sm_" . $fName;
    if (preg_match("/png|jpg|jpeg|svg|gif|webp/", $imageFileType)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"][$i], $target_file)) {
            $url = 'upload.php?delete=' . $fName;
            $p1[$i] = '<img src="' . SITEURL . 'assets/images/' . $fName . '" width="200px">';
            $db = new DB();
            // $db->table('images')->insert(['uri' => $p1[$i]]);
            $db->table('images')->insert(['uri' => $fName]);
            smart_resize_image($target_file, "", 200, 200, false, $target_file_sm, false);
        } else {

        }
    }
    $key = '';

    $p2[$i] = ['caption' => $fName, 'size' => $_FILES["image"]["size"][$i], 'width' => '120px', 'url' => $url, 'key' => $i + 1];
}
echo json_encode([
    'initialPreview' => $p1,
    'initialPreviewConfig' => $p2,
    'append' => true,
]);
