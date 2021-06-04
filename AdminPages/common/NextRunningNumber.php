<?php

class NextRunningNumber {

    //put your code here
    function nextnumber($prefix, $year, $conn) {

        $returnvalue = 0;
        try {
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $sql = "select prefix,years,runningnumber from nextrunningnumber where prefix='$prefix' and years='$year'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $runningnumber = $row['runningnumber']; //17049
                    $runningnumber = $runningnumber + 1;
                    $updateSql = "update nextrunningnumber set runningnumber=$runningnumber where  prefix='$prefix' and years='$year'";
                    $conn->query($updateSql);
                    $returnvalue = $prefix . $year . $this->getsequenceNumber($runningnumber);
                }
            } else {
                $sql = "INSERT INTO nextrunningnumber (`prefix`, years, runningnumber) VALUES ('$prefix', $year, 1)";
                $conn->query($sql);
                $runningnumber = "1";
                $returnvalue = $prefix . $year . $this->getsequenceNumber($runningnumber);
            }
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            echo 'Error message ' . $message;
        }
        return $returnvalue;
    }

    public function getsequenceNumber($number) {
        $runningnumber = "";
        switch (strlen($number)) {
            case 1:
                $runningnumber = '000' . $number;
                break;
            case 2:
                $runningnumber = '00' . $number;
                break;
            case 3:
                $runningnumber = '0' . $number;
                break;
            default :
                $runningnumber = $number;
                break;
        }
        return $runningnumber;
    }

}

?>
