<?php
if ($_GET['randomId'] != "D4wLx3Ur7APhwth8MzvYotIcSr_UzNSvir95N60w86qEMam81s2zPoUDL4MMWVW1") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
