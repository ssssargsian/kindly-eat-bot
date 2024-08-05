<?php

namespace App\Domain\Repository;

use App\Domain\Entity\TelegramUser;

interface TelegramUserRepositoryInterface
{
    public function findById(int $id): ?TelegramUser;
    public function findByUsername(string $username): ?TelegramUser;
    public function findByChatId(int $chatId): ?TelegramUser;
    public function save(TelegramUser $user): void;
    public function delete(TelegramUser $user): void;
}
