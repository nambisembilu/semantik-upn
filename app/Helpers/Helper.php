<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Helper
{

    public function dateIndotoEnglishFormat($source)
    {
        $result = $source;
        if($source != null && $source != "")
        {
            $tempArr = explode('-', $source);
            $result = $tempArr[2]."-".$tempArr[1]."-".$tempArr[0];
        }
        return $result;
    }

    public function dateEnglishtoIndoFormat($source)
    {
        $result = $source;
        if($source != null && $source != "")
        {
            $tempArr = explode('-', $source);
            $result = $tempArr[2]."-".$tempArr[1]."-".$tempArr[0];
        }
        return $result;
    }

    public function dateEnglishtoIndoMMMFormat($source)
    {
        $month = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $result = $source;
        if($source != null && $source != "")
        {
            $tempArr = explode('-', $source);
            $result = $tempArr[2]." ".$month[ (int)$tempArr[1] ]." ".$tempArr[0];
        }
        return $result;
    }

    public function GetOrganizationPerformanceText($source)
    {
        $result = str_replace("_"," ", $source);
        $result = ucfirst($result);
        return $result;
    }

    public function GetOrganizationPerformanceTextUpper($source)
    {
        $result = str_replace("_"," ", $source);
        $result = strtoupper($result);
        return $result;
    }

    public function getGradeValue($employeeType, $gradeName, $name)
    {
        if($employeeType == 'CALON TETAP NON PNS' || $employeeType == 'TETAP NON PNS' ||
            $employeeType == 'CTNPNS' || $employeeType == 'TNPNS' || 
            strpos($gradeName, 'Setara') === true)
        {
            $result = "Setara ".$gradeName;
        }
        else if($employeeType == 'PNS' || $employeeType == 'CPNS' || $employeeType == 'DK')
        {
            $result = $name.", ".$gradeName;
        }
        else
        {
            $result = $gradeName;
        }
        
        return $result;
    }
}

?>
