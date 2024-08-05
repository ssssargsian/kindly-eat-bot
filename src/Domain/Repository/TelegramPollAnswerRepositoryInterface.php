<?php

namespace App\Domain\Repository;

use App\Domain\Entity\TelegramPollAnswer;

interface TelegramPollAnswerRepositoryInterface
{
    public function save(TelegramPollAnswer $telegramPollAnswer): void;
    public function delete(TelegramPollAnswer $telegramPollAnswer): void;
    public function findByPollId(string $pollId): ?TelegramPollAnswer;
}
