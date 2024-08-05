<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\TelegramPollAnswer;
use App\Domain\Entity\TelegramPollOption;
use App\Domain\Entity\TelegramUser;
use App\Domain\Repository\TelegramPollAnswerRepositoryInterface;
use App\Domain\Repository\TelegramUserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class PollService
{
    public function __construct(
        private TelegramBotApi $telegramBotApi,
        private TelegramUserRepositoryInterface $userRepository,
        private TelegramPollAnswerRepositoryInterface $pollAnswerRepository,
        private EntityManagerInterface $entityManager,
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
        $user = $pollAnswer['user'];

        $existTelegramUser = $this->userRepository->findByChatId($user['id']);
        if ($existTelegramUser === null) {
            $existTelegramUser = new TelegramUser(
                name: $user['first_name'],
                lastName: $user['last_name'],
                username: $user['username'],
                chatId: $user['id'],
            );
        }

        $this->userRepository->save($existTelegramUser);

        $existTelegramPollAnswer = $this->pollAnswerRepository->findByPollId($pollAnswer['poll_id']);
        if ($existTelegramPollAnswer === null) {
            $existTelegramPollAnswer = new TelegramPollAnswer(
                pollId: $pollAnswer['poll_id'],
                user: $existTelegramUser,
            );
        }

        foreach ($pollAnswer['option_ids'] as $optionId) {
            $existTelegramPollAnswer->addOptionId(new TelegramPollOption($optionId));
        }

        $this->pollAnswerRepository->save($existTelegramPollAnswer);

        $this->entityManager->flush();
    }
}
