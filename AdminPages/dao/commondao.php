<?php

include_once '../../connection/DBConnection.php';

if (isset($_POST['dataType'])) {
    $method = isset($_POST['dataType']) ? $_POST['dataType'] : '';
} else {
    $method = isset($_GET['dataType']) ? $_GET['dataType'] : '';
}
$commondao = new commondao();
$DBConnection = new DBConnection();
switch ($method) {
    case 'checkUserIdAvailability':
        $commondao->checkUserIdAvailability($conn);
        break;
    case 'checknumberavailability':
        $commondao->checknumberavailability($conn);
        break;
    case 'checkEmailavailability':
        $commondao->checkEmailavailability($conn);
        break;
}

class commondao {

    function formatSizeUnits($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    function checkUserIdAvailability($conn) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $userid = $_GET['userid'];
            $sql = "select userid from registration where userid = '$userid' ";
            // echo 'query :' . $sql;
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo '1';
            } else {
                echo '0';
            }
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    function checknumberavailability($conn) {
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $number = $_GET['number'];
            $sql = "select mobile from registration where mobile = '$number' ";
            // echo 'query :' . $sql;
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo '1';
            } else {
                echo '0';
            }
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    function checkEmailavailability($conn) {

        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $emailid = $_GET['emailid'];
            $sql = "select email from registration where email = '" . $emailid . "' ";
            //echo 'query :' . $sql;
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo '1';
            } else {
                echo '0';
            }
            mysqli_close($conn);
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
    }

    function getDateDiff($timestamp) {

        $datetime1 = new DateTime($timestamp);
        $datetime2 = new DateTime(date('Y-m-d H:i:s'));

        $diff = $datetime1->diff($datetime2);

        $y = $diff->y;
        $m = $diff->m;
        $d = $diff->d;
        $h = $diff->h;
        $i = $diff->i;
        $s = $diff->s;

        if ($y != 0) {
            $seen = $y . 'year';
        } else if ($m != 0) {
            $seen = $m . 'month';
        } else if ($d != 0) {
            $seen = $d . 'day';
        } else if ($h != 0) {
            $seen = $h . 'hrs';
        } else if ($i != 0) {
            $seen = $i . 'mins';
        } else if ($s != 0) {
            $seen = $s . 'secs';
        }
        return $seen;
    }

}

?>