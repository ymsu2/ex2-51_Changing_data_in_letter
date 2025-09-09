<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Diag\Debug;

// Подключаем обработчик события OnBeforeEventSend
AddEventHandler("main", "OnBeforeEventSend", "replaceFeedbackAuthorMacro");

function replaceFeedbackAuthorMacro(&$event, &$lid, &$arFields)
{
    // Обрабатываем только событие FEEDBACK_FORM
    if ($event != "FEEDBACK_FORM") {
        return;
    }

    global $USER;

    // Получаем имя из формы (поле AUTHOR_NAME — стандартное поле компонента main.feedback)
    $userNameFromForm = isset($arFields["AUTHOR_NAME"]) ? $arFields["AUTHOR_NAME"] : "Не указано";

    // Определяем строку для макроса #AUTHOR#
    if ($USER->IsAuthorized()) {
        $userId = $USER->GetID();
        $userLogin = $USER->GetLogin();
        $userName = $USER->GetFullName();
        if (empty($userName)) {
            $userName = $userLogin;
        }

        $authorText = "Пользователь авторизован: {$userId} ({$userLogin}) {$userName}, данные из формы: {$userNameFromForm}";
    } else {
        $authorText = "Пользователь не авторизован, данные из формы: {$userNameFromForm}";
    }

    // Заменяем макрос #AUTHOR# во всех текстовых полях письма
    foreach ($arFields as $key => &$value) {
        if (is_string($value)) {
            $value = str_replace("#AUTHOR#", $authorText, $value);
        }
    }
    unset($value); // сбрасываем ссылку, чтобы не повредить последний элемент

    // Записываем в журнал событий
    AddMessage2Log("Замена данных в отсылаемом письме – " . $authorText, "feedback_author_replace");

    // Опционально: добавляем/перезаписываем поле AUTHOR для совместимости
    $arFields["AUTHOR"] = $authorText;
}