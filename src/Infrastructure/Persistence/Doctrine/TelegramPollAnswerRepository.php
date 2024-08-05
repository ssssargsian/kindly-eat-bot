<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Entity\TelegramPollAnswer;
use App\Domain\Entity\TelegramUser;
use App\Domain\Repository\TelegramPollAnswerRepositoryInterface;
use App\Domain\Repository\TelegramUserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Stringable;

/**
 * @method TelegramPollAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelegramPollAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelegramPollAnswer[]    findAll()
 * @method TelegramPollAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method TelegramPollAnswer      get(Stringable|string $uuid, bool $withSoftDeleted = false, bool $cached = true)
 */
class TelegramPollAnswerRepository extends ServiceEntityRepository implements TelegramPollAnswerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramPollAnswer::class);
    }

    public function save(TelegramPollAnswer $telegramPollAnswer): void
    {
        $this->getEntityManager()->persist($telegramPollAnswer);
        $this->getEntityManager()->flush();
    }

    public function delete(TelegramPollAnswer $telegramPollAnswer): void
    {
        $this->getEntityManager()->remove($telegramPollAnswer);
        $this->getEntityManager()->flush();
    }

    public function findByPollId(string $pollId): ?TelegramPollAnswer
    {
        return $this->findOneBy(['pollId' => $pollId]);
    }
}
