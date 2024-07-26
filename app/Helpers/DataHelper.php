<?php

use Carbon\Carbon;

if (!function_exists('getListMonthId')) {
    function getListMonthId()
    {
        $periods = Carbon::create(date('Y'), 1, 1, 0, 0, 0)->monthsUntil(Carbon::create(date('Y'), 12, 1, 0, 0, 0));
        $months = [];
        foreach ($periods as $period) {
            $months[] = [
                'value' => $period->month,
                'name' => $period->monthName
            ];
        }
        return $months;
    }
}
if (!function_exists('getOrganizationType')) {
    function getOrganizationType()
    {
        return [
            [
                'value' => 'PERGURUAN_TINGGI',
                'code' => 'PT',
                'name' => 'Perguruan Tinggi'
            ],
            [
                'value' => 'SEKOLAH',
                'code' => 'SKLH',
                'name' => 'Sekolah'
            ],
            [
                'value' => 'INSTANSI',
                'code' => 'INS',
                'name' => 'Instansi Pemerintah'
            ],
            [
                'value' => 'RUMAH_SAKIT',
                'code' => 'RS',
                'name' => 'Rumah Sakit'
            ],
            [
                'value' => 'ORGANISASI_SOSIAL',
                'code' => 'SOS',
                'name' => 'Organisasi Sosial'
            ],
            [
                'value' => 'PERUSAHAAN',
                'code' => 'USH',
                'name' => 'Perusahaan'
            ],
        ];
    }
}
if (!function_exists('getAttendanceStatus')) {
    function getAttendanceStatus()
    {
        return [
            [
                'value' => '0',
                'name' => 'Tidak Masuk',
                'class' => 'danger',
                'subtract_rate' => 10,
            ],
            [
                'value' => '1',
                'name' => 'Masuk',
                'class' => 'success',
                'subtract_rate' => 0,
            ],
            [
                'value' => '2',
                'name' => 'Ijin',
                'class' => 'primary',
                'subtract_rate' => 2.5,
            ],
            [
                'value' => '3',
                'name' => 'Terlambat',
                'class' => 'warning',
                'subtract_rate' => 1,
            ],
            [
                'value' => '4',
                'name' => 'Pulang Mendahului',
                'class' => 'secondary',
                'subtract_rate' => 1,
            ],
        ];
    }
}
if (!function_exists('searchMonth')) {
    function searchMonth($value)
    {
        $result = '';
        $months = getListMonthId();
        foreach ($months as $month) {
            if ($month['value'] == $value) {
                $result = $month;
            }
        }
        return $result;
    }
}
if (!function_exists('searchOrganizationType')) {
    function searchOrganizationType($value)
    {
        $result = '';
        $types = getOrganizationType();
        foreach ($types as $type) {
            if ($type['value'] == $value) {
                $result = $type;
            }
        }
        return $result;
    }
}
if (!function_exists('searchAttendanceStatus')) {
    function searchAttendanceStatus($value)
    {
        $result = '';
        $lists = getAttendanceStatus();
        foreach ($lists as $list) {
            if ($list['value'] == $value) {
                $result = $list;
            }
        }
        return $result;
    }
}

if (!function_exists('searchOrgPerformance')) {
    function searchOrgPerformance($value)
    {
        $result = '';
        $types = getOrgPerformanceList();
        foreach ($types as $type) {
            if ($type['value'] == $value) {
                $result = $type;
            }
        }
        return $result;
    }
}
if (!function_exists('generatePhoneNumberCode')) {
    function generatePhoneNumberCode($number)
    {
        $result = '';
        $first_char = substr($number, 0, 1);
        if ($first_char == '0') {
            $result = '+62' . substr($number, 1, strlen($number));
        } else {
            $result = $number;
        }
        return $result;
    }
}
if (!function_exists('getValueRangeAssessment')) {
    function getValueRangeAssessment($value)
    {
        $value = $value >= 100 ? 99.00 : $value;
        $result = [];
        $ranges = [
            [
                'up' => 100.00,
                'down' => 80.00,
                'text' => 'Sangat Baik',
                'badge_class' => 'badge-success opacity-75'
            ],
            [
                'up' => 80.00,
                'down' => 60.00,
                'text' => 'Baik',
                'badge_class' => 'badge-primary'
            ],
            [
                'up' => 60.00,
                'down' => 40.00,
                'text' => 'Cukup',
                'badge_class' => 'badge-warning'
            ],
            [
                'up' => 40.00,
                'down' => 20.00,
                'text' => 'Kurang',
                'badge_class' => 'badge-danger opacity-75'
            ],
            [
                'up' => 20.00,
                'down' => -1000,
                'text' => 'Sangat Kurang',
                'badge_class' => 'badge-danger'
            ],
        ];
        foreach ($ranges as $range) {
            if ($value > $range['down'] && $value <= $range['up']) {
                $result = $range;
            }
        }
        return $result;
    }

    if (!function_exists('getWorkResultList')) {
        function getWorkResultList()
        {
            return [
                ['value' => 1, 'text' => 'DIBAWAH EKSPEKTASI'],
                ['value' => 2, 'text' => 'SESUAI EKSPEKTASI'],
                ['value' => 3, 'text' => 'DIATAS EKSPEKTASI'],
            ];
        }
    }
    if (!function_exists('getWorkResultFinalList')) {
        function getWorkResultFinalList()
        {
            return [
                ['value' => 1, 'text' => 'SANGAT KURANG'],
                ['value' => 2, 'text' => 'BUTUH PERBAIKAN'],
                ['value' => 3, 'text' => 'KURANG/MIS CONDUCT'],
                ['value' => 4, 'text' => 'BAIK'],
                ['value' => 5, 'text' => 'SANGAT BAIK'],
            ];
        }
    }
    if (!function_exists('getOrgPerformanceList')) {
        function getOrgPerformanceList()
        {
            return [
                ['value' => 'istimewa', 'text' => 'Istimewa'],
                ['value' => 'baik', 'text' => 'Baik'],
                ['value' => 'butuh_perbaikan', 'text' => 'Butuh Perbaikan'],
                ['value' => 'kurang', 'text' => 'Kurang'],
                ['value' => 'sangat_kurang', 'text' => 'Sangat Kurang'],
            ];
        }
    }
    if (!function_exists('getWorkResultText')) {
        function getWorkResultText($value)
        {
            foreach (getWorkResultList() as $l) {
                if ($l['value'] == $value) {
                    return $l['text'];
                }
            }
        }
    }
    if (!function_exists('getWorkResultFinal')) {
        function getWorkResultFinal($work_result, $behavior_result)
        {
            if ($work_result == 1 && $behavior_result == 1) {
                return 1;
            } else if ($work_result == 1 && $behavior_result == 2) {
                return 2;
            } else if ($work_result == 1 && $behavior_result == 3) {
                return 2;
            } else if ($work_result == 2 && $behavior_result == 1) {
                return 3;
            } else if ($work_result == 2 && $behavior_result == 2) {
                return 4;
            } else if ($work_result == 2 && $behavior_result == 3) {
                return 4;
            } else if ($work_result == 3 && $behavior_result == 1) {
                return 3;
            } else if ($work_result == 3 && $behavior_result == 2) {
                return 4;
            } else if ($work_result == 3 && $behavior_result == 3) {
                return 5;
            }
        }
    }
    if (!function_exists('getWorkResultFinalText')) {
        function getWorkResultFinalText($value)
        {
            foreach (getWorkResultFinalList() as $l) {
                if ($l['value'] == $value) {
                    return $l['text'];
                }
            }
        }
    }
}

