<?php
define('STOP_STATISTICS', true);
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
use Bitrix\Main\Loader;
Loader::includeModule('crm');

use Bitrix\Crm\Service\Container;

$entityTypeId= 1036;

$url = 'https://your_domen.ru/api/test.php';
$response = file_get_contents($url);
if($response === false){
    die('Ошибка при получении данных');
}


$carArr = json_decode($response, true);
if(count($carArr) == 0){
    die('Ошибка при декодировании');
}

$factory = Container::getInstance()->getFactory($entityTypeId);

foreach ($carArr as $car) {
    $carArray = [
        'TITLE'=> 'Автомобиль ' . $car['model_name']. " " . $car['vehicle_vin'] ,
        'UF_CRM_3_BRAND_ID' => $car['brand_id'],
        'UF_CRM_3_MODEL_NAME' => $car['model_name'],
        'UF_CRM_3_COLOR' =>$car['color']['name'],
        'UF_CRM_3_COMPLECTATION_CODE' =>$car['complectation_code'],
        'UF_CRM_3_VIN_CODE' =>  $car['vehicle_vin'],
    ];
    $item = $factory->createItem($carArray);
    $saveOperation = $factory->getAddOperation($item);
    $operationResult = $saveOperation->launch();
}

