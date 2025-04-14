<?php

function sendMessage($chat_id, $text, $keyboard = null)
{
    $token = TG_TOKEN;
    $url = "https://api.telegram.org/bot{$token}/sendMessage";

    $postFields = array(
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML'
    );

    if ($keyboard) {
        $postFields['reply_markup'] = json_encode($keyboard);
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

function removeInlineKeyboard($messageId, $chatId)
{
    $url = "https://api.telegram.org/bot" . TG_TOKEN . "/editMessageReplyMarkup";

    $postFields = [
        'chat_id' => $chatId,
        'message_id' => $messageId,
        'reply_markup' => json_encode([
            'inline_keyboard' => []
        ])
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

function generateQuestion()
{
    $a = rand(1, 15);
    $b = rand(1, 15);
    return [
        'question' => "$a + $b",
        'answer' => $a + $b
    ];
}