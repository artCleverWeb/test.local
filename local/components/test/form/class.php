<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\Response\AjaxJson;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class Form extends \CBitrixComponent implements Controllerable
{

    private ErrorCollection $errorCollection;

    public function configureActions()
    {
        return [
            'sendForm' => [
                'prefilters' => [
                    new ActionFilter\HttpMethod(
                        [ActionFilter\HttpMethod::METHOD_POST]
                    ),
                    new ActionFilter\Csrf(),
                ],
            ],
        ];
    }

    public function onPrepareComponentParams($arParams): array
    {
        $this->errorCollection = new ErrorCollection();

        return $arParams;
    }

    public function sendFormAction(string $name, string $phone, string $email, int $blockId): AjaxJson
    {
        $responseData = [];

        try {

            $blockId = (int)$blockId;
            $phone = NormalizePhone($phone);

            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $this->errorCollection->setError(
                    new Error(GetMessage('ERROR_VALIDATION_EMAIL'))
                );
            }

            if (!preg_match_all('/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/m', $phone, $matches, PREG_SET_ORDER, 0)) {
                $this->errorCollection->setError(
                    new Error(GetMessage('ERROR_VALIDATION_PHONE'))
                );
            }

            if (strlen($name) < 1) {
                $this->errorCollection->setError(
                    new Error(GetMessage('ERROR_VALIDATION_NAME'))
                );
            }

            if ($blockId < 1) {
                $this->errorCollection->setError(
                    new Error(GetMessage('ERROR_VALIDATION_SYSTEM'))
                );
            }

        } catch (\Exception $e) {
            $this->errorCollection->setError(new Error($e->getMessage()));
        }

        if ($this->errorCollection->count() > 0) {
            return AjaxJson::createError($this->errorCollection);
        }

        try {

            $phone = preg_replace('/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/','7$2$3$4$5', $phone);

            if (!\Bitrix\Main\Loader::includeModule('highloadblock')) {
                $this->errorCollection->setError(
                    new Error(GetMessage('ERROR_NOT_FOUND_HL'))
                );

                return AjaxJson::createError($this->errorCollection);
            }

            $hlblock = HL\HighloadBlockTable::getById($blockId)->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();

            if (!$entity_data_class) {
                $this->errorCollection->setError(
                    new Error(GetMessage('ERROR_NOT_FOUND_HL'))
                );

                return AjaxJson::createError($this->errorCollection);
            }

            $issetItem = $entity_data_class::getCount([
                'LOGIC' => 'OR',
                ['=UF_EMAIL' => $email],
                ['=UF_PHONE' => $phone],
            ]) > 0;

            if($issetItem === true){
                $this->errorCollection->setError(
                    new Error(GetMessage('ERROR_ISSET_ITEM'))
                );

                return AjaxJson::createError($this->errorCollection);
            }

            $resultAdd = $entity_data_class::add([
                'UF_EMAIL' => $email,
                'UF_PHONE' => $phone,
                'UF_NAME' => $name,
            ]);

            if($resultAdd->isSuccess() === false) {
                $this->errorCollection->setError(
                    new Error(GetMessage('ERROR_SYSTEM'))
                );

                return AjaxJson::createError($this->errorCollection);
            }

            $responseData['status'] = 'OK';

        } catch (\Exception $e) {
            $this->errorCollection->setError(new Error($e->getMessage()));
        }
        return AjaxJson::createSuccess($responseData);
    }

    public function getErrors(): array
    {
        return $this->errorCollection->toArray();
    }

    public function executeComponent()
    {

        if (!$this->errorCollection->isEmpty()) {
            return;
        }

        if (!isset($this->arParams['BLOCK_ID']) || empty($this->arParams['BLOCK_ID']) || intval($this->arParams['BLOCK_ID']) < 1) {
            return;
        }

        $this->IncludeComponentTemplate();
    }
}
