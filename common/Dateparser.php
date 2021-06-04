<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Dateparser {

    public static function StringtoDate($dateString) {
        $dateString = str_replace("/", "-", $dateString);
        $returndate = date('Y-m-d', strtotime($dateString));
        return $returndate;
    }

    public static function DatetoString($StringtoDate) {
        $dateString = $StringtoDate;
        $returndate = date('d-m-Y', strtotime($dateString));
        $dateString = str_replace("-", "/", $returndate);
        return $dateString;
    }

    public static function Stringdateto_formate($StringtoDate) {
        $dateString = $StringtoDate;
        $returndate = date('d-m-Y', strtotime($dateString));
        $dateString = str_replace("/", "-", $returndate);
        return $dateString;
    }

    public static function monthdateyear_formate($StringtoDate) {
        $dateString = $StringtoDate;
        $returndate = date('Y-m-d', strtotime($dateString));
        $dateString = str_replace("-", "/", $returndate);
        return $dateString;
    }

    public static function Datehrtostringdatehr($StringtoDate) {
        $dateString = $StringtoDate;
        $returndate = date('d-m-Y|h:i:s', strtotime($dateString));
        $dateString = str_replace("-", "/", $returndate);
        return $dateString;
    }

    public static function datevalidation($date) {

        if ($date != '0000-00-00' && $date != 'NULL' && $date != '' && $date != '1970-01-01') {
            $datevalidation = Dateparser::DatetoString($date);
        } else {
            $datevalidation = '-';
        }
        return $datevalidation;
    }
    
    public static function flagtoimageconvertation_binary($flag) {
        // getting flag 0 or 1
        if ($flag == '1') {
            $image = "<img src='default/images/tick1.png' width='20' height='20'/>";
        } else {
            $image = "<img src='default/images/untick.png' width='20' height='20' />";
        }
        return $image;
    }

    public static function flagtoimageconvertation($flag) {
        // getting flag Y or N
        if ($flag == 'Y') {
            $image = "<img src='../default/images/tick1.png' width='25' height='25'/>";
        } else {
            $image = "<img src='../default/images/untick.png' width='25' height='25' />";
        }
        return $image;
    }
    public static function flagtoimageconvertation_ext($flag) {
        // getting flag Y or N
        if ($flag == 'Y') {
            $image = "<img src='default/images/tick1.png' width='25' height='25'/>";
        } else {
            $image = "<img src='default/images/untick.png' width='25' height='25' />";
        }
        return $image;
    }
    
    public static function monthinttostringconversion($monthint) {
        switch ($monthint) {
            case '04':
                $monthname = 'April';
                break;
            case '05':
                $monthname = 'May';
                break;
            case '06':
                $monthname = 'June';
                break;
            case '07':
                $monthname = 'July';
                break;
            case '08':
                $monthname = 'August';
                break;
            case '09':
                $monthname = 'September';
                break;
            case '10':
                $monthname = 'October';
                break;
            case '11':
                $monthname = 'November';
                break;
            case '12':
                $monthname = 'December';
                break;
            case '01':
                $monthname = 'January';
                break;
            case '02':
                $monthname = 'Febuary';
                break;
            case '03':
                $monthname = 'March';
                break;
            case '04':
                $monthname = 'April';
                break;
            default:
                $monthname = 'nil';
                break;
        }
        return $monthname;
    }

}

?>
