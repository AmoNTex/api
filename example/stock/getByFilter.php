<?php

// выборка объектов базы данных (продуктов) с фильтром по определенному полю
// например, все объекты с ценой больше 30 000

require_once '../usage.php'; //настройте данный конфигурационный файл

$typesData = $api->getStockTypes();

//Массив id всех типов
$types = array_map(function($item) {
    return $item['id'];
}, $typesData['data']);

//Получить список типов
$type = $types[0]; //Выьираем первый тип
//Выборка всех существующих полей
$fields = $api->getStockFields();

//Поля объектов
$myFields = $fields['data'][$type]['fields'];

$list = array();

$data = $api->getStockByFilter(array(
    'type' => $type,
    'page' => 1,
    'limit' => 500, //Максимальный, как выбрать больше 500 показано в примерах exportAll.php и exportByDate.php
    'fields' => array(
        //Объекты с ценой больше 30000
        array(
            'id'    => 396,
            'value' => ">=30000"
        )
    )
        ));
$list = $data['data']['list'];

foreach ($list as $key => $item) {
    foreach ($item['fields'] as $fieldKey => $field) {
        if ($field['type'] == 'file') {
            $list[$key]['fields'][$fieldKey]['value'] = $api->getStockUrlPhoto($field['value']);
        }
        $list[$key]['fields'][$fieldKey]['name'] = $fields['data'][$type]['fields'][$field['id']]['name'];
    }
}

print_r($list);

//($list);  Подходящие объекты, формат показан в примерах exportAll.php и exportByDate.php

