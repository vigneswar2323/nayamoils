<?php

class CommonDao {

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
    
    function defaultimage($imagepath){
        if($imagepath == ''){
            $imagepath="/images/afflogo.jpg";
        }
        return $imagepath;
    }

}

?>