<?php
require_once 'func.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define("TG_TOKEN", "8078273151:AAHNXw9Ap0gA3s5DDspO0R5SoKNHCAvsvvw");
define("TG_USER_ID", 7945284360);


$input = file_get_contents('php://input');
$data = json_decode($input, true);

$keyboard = [
    'inline_keyboard' => [
        [
            ['text' => 'Да', 'callback_data' => 'yes'],
            ['text' => 'Нет', 'callback_data' => 'no'],
        ]
    ], 'one_time_keyboard' => TRUE,
];

if (isset($data['message']['text'])) {
    $chatId = $data['message']['chat']['id'];
    $text = $data['message']['text'];
    $stateFile = "state_{$chatId}.txt";
    $state = file_exists($stateFile) ? file_get_contents($stateFile) : null;
    if($state == 'waiting_for_answer'){
        if(is_numeric($text)){
            $userAnswer = (int)($text);
            $correctAnswer = (int)(file_get_contents("answer_{$chatId}.txt"));
            if($userAnswer == $correctAnswer){
                sendMessage($chatId, "Отлично, вот секретная информация: https://core.telegram.org/bots/api ");
            } else {
                sendMessage($chatId, "Ответ неверный.");
            }
            unlink($stateFile);
            unlink("answer_{$chatId}.txt");
        } else {
            sendMessage($chatId, "Пожалуйста, введите число");
        }
    }
    if($state != 'waiting_for_answer'){
        if ($text === "/start") {
            sendMessage($chatId, "Привет, для получения секретной информации нужно решить пример. Готов?", $keyboard);
        } else {
            sendMessage($chatId, "Привет, для начала работы напиши /start");
        }
    }
}

if (isset($data['callback_query'])){
    $callbackData = $data['callback_query']['data'];
    $callbackId = $data['callback_query']['id'];
    $chatId = $data['callback_query']['message']['chat']['id'];
    if($callbackData == 'yes') {
        removeInlineKeyboard($data['callback_query']['message']['message_id'], $chatId);
        $example = generateQuestion();
        file_put_contents("answer_{$chatId}.txt", $example['answer']);
        file_put_contents("state_{$chatId}.txt", 'waiting_for_answer');

        sendMessage($chatId, "Реши пример: " . $example['question']);

    } else
        sendMessage($chatId, "Как знаешь.");
}










