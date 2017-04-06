<?php

include_once('libphp/total_function.php');
//include_once 'api_function.php';
header('Content-Type: text/html; charset=utf-8');
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$type = 9;
delete_records($type);
$name = '';
$city = '';
$region = '';
$address = '';
$phone = '';
$errors_dump= '';
$work_time = '';
$index = NULL;
$lang = NULL;
$flag = NULL;
$total_mas = array();

$content = file_get_contents('https://api2.nrg-tk.ru/v2/cities?lang=ru');
$content = json_decode($content);

if (isset($content)) {
    foreach ($content->cityList as $cityes) {
        $city = $cityes->name;

        foreach ($cityes->warehouses AS $info) {
            $addres = $info->address;
            $dump = preg_match('/[a-zA-Z]{3,}/i', $addres, $address);
            $phone = $info->phone;

            $title= $info->title;
            $name= trim($title);

            if (!$dump) {
                if ($addres  ) {
                    $phone = trim($phone);
                    $city = trim($city);
                    $address = trim($addres);
                    append_department($type, $name, $city, $region, $address, $phone, $work_time, $index, $lang, $flag);
                }
                if ((!$address) ) {
                    $errors_dump .= $address . "адрес  и город с пустыми полями  <br>";//добавление городов или адресов с ошибками
                }
            }
        }
    }
} else {
    mail('serui121094@gmail.com', 'Парсер доставки', 'Необходима починка парсера Energy');
    die("Error");
}

if (!empty($errors_dump)) {
    //mail('olga@accbox.info', 'Парсер доставки', 'Необходима починка парсера kit.PHP'. $errors_dump);
    die('Есть замечания, письмо отправлено оператору');
}
die("Парсер отработал успешно");
