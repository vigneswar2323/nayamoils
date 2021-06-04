<?php

include '../common/Dateparser.php';
include '../connection/DBConnection.php';

class dao {

    public function saveContact($conn, $parameters) {
        $name = $parameters['name'];
        $mobile = $parameters['mobile'];
        $email = $parameters['email'];
        $subject = $parameters['subject'];
        $message = $parameters['message'];
        $createddate = date('Y-m-d');
        date_default_timezone_set('Asia/Kolkata');
        $createdtime = date('H:i:s');
        $createdby = 'User';

        $insert = "insert into tbl_contactdetails (contact_name,contact_no,contact_email,contact_subject,contact_message,createdby,createddate,createdtime) VALUES ('$name','$mobile','$email','$subject','$message','$createdby','$createddate','$createdtime')";
        $result = mysqli_query($conn, $insert);

        if ($result) {
            $message = "Get in touch with you shorlty!";
        }
        echo $message;
        $_SESSION['message'] = $message;
    }

}

?>