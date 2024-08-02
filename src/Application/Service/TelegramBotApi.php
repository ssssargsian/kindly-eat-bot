<?php

declare(strict_types=1);

namespace App\Application\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Telegram\Bot\Api;

final readonly class TelegramBotApi
{
    private Api $telegramBotApi;

    public function __construct(
        #[Autowire(param: 'app.telegram_bot_token')]
        private string $token,
    ) {
        $this->telegramBotApi = new Api($this->token);
    }

    public function client(): Api
    {
        return $this->telegramBotApi;
    }
}
