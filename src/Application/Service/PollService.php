<?php

declare(strict_types=1);

namespace App\Application\Service;

final readonly class PollService
{
    public function __construct(
        private TelegramBotApi $telegramBotApi,
    ) {
    }

    public function startPoll($chatId): void
    {
        $pollQuestion = 'From which company should we order food?';
        $pollOptions = ['BarBq', 'MasterSteak', 'PizzaPlace'];

        $this->telegramBotApi->client()->sendPoll([
            'chat_id' => $chatId,
            'question' => $pollQuestion,
            'options' => json_encode($pollOptions),
            'is_anonymous' => false,
            'expires_in' => 600,
        ]);
    }

    public function processPollAnswer(array $pollAnswer): void
    {
        $pollId = $pollAnswer['poll_id'];
        $optionIds = $pollAnswer['option_ids'];
        $userId = $pollAnswer['user']['id'];


    }
}
