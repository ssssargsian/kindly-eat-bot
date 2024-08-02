<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Service\PollService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
final readonly class TelegramController
{
    public function __construct(
        private PollService $pollService,
    ) { }

    #[Route(path: '/telegram_webhook', name: 'telegram', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        $update = $request->toArray();

        // https://api.telegram.org/bot**/setwebhook?url=https://2d16-5-35-81-75.ngrok-free.app/telegram_webhook
        if (isset($update['message'])) {
            $message = $update['message'];
            $chatId = $message['chat']['id'];
            $text = $message['text'];

            if ($text === '/start') {
                $this->pollService->startPoll($chatId);
            } elseif ($text === '/order') {

            }
        } elseif (isset($update['poll_answer'])) {
            $this->pollService->processPollAnswer($update['poll_answer']);
        }

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
