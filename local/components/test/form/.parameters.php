<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Highloadblock\HighloadBlockTable;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if(!\Bitrix\Main\Loader::includeModule('highloadblock')){
    die();
}

$hlBlockLists = Bitrix\Highloadblock\HighloadBlockTable::getList(
    [
        'select' => [
            'ID' => 'ID',
            'NAME' => 'NAME',
        ]
    ]
)->fetchAll();

$hlBlockListsEx = array_column($hlBlockLists, 'NAME', 'ID');

$arComponentParameters = [
    'GROUPS' => [
    ],
    'PARAMETERS' => [
        'BLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => GetMessage('HLTEST_BLOCK_ID'),
            "TYPE" => "LIST",
            "VALUES" => $hlBlockListsEx,
        ],
    ],
];
