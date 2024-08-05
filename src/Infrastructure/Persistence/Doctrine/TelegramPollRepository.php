<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Entity\TelegramUser;
use App\Domain\Repository\TelegramUserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Stringable;

/**
 * @method TelegramUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelegramUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelegramUser[]    findAll()
 * @method TelegramUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method TelegramUser      get(Stringable|string $uuid, bool $withSoftDeleted = false, bool $cached = true)
 */
class TelegramPollRepository extends ServiceEntityRepository implements TelegramUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramUser::class);
    }

    public function findById(int $id): ?TelegramUser
    {
        return $this->find($id);
    }

    public function findByUsername(string $username): ?TelegramUser
    {
        return $this->findOneBy(['username' => $username]);
    }

    public function findByChatId(int $chatId): ?TelegramUser
    {
        return $this->findOneBy(['chatId' => $chatId]);
    }

    public function save(TelegramUser $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function delete(TelegramUser $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }
}
