<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Форма обратной связи");

$APPLICATION->IncludeComponent(
    "bitrix:main.feedback",
    "",
    [
        "EMAIL_TO" => "admin@example.com", // замените на реальный email
        "USE_CAPTCHA" => "Y",
        "OK_TEXT" => "Спасибо, ваше сообщение принято.",
        "REQUIRED_FIELDS" => ["NAME", "EMAIL", "MESSAGE"],
        "EVENT_MESSAGE_ID" => [],
    ]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
?>