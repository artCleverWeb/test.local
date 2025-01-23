<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'main',
    'OnPageStart',
    'changeLanguage'
);

function changeLanguage(): void
{
    $context = \Bitrix\Main\Context::getCurrent();

    $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    if ($request->get('lang') && strlen($request->get('lang')) > 0) {
        $context->setLanguage($request->get('lang') == 'en' ? 'en' : 'ru');
    }
}
