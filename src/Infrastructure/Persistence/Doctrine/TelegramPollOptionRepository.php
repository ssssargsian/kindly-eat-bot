<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Entity\TelegramPollAnswer;
use App\Domain\Entity\TelegramPollOption;
use App\Domain\Entity\TelegramUser;
use App\Domain\Repository\TelegramPollAnswerRepositoryInterface;
use App\Domain\Repository\TelegramPollOptionRepositoryInterface;
use App\Domain\Repository\TelegramUserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Stringable;

/**
 * @method TelegramPollOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method TelegramPollOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method TelegramPollOption[]    findAll()
 * @method TelegramPollOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method TelegramPollOption      get(Stringable|string $uuid, bool $withSoftDeleted = false, bool $cached = true)
 */
class TelegramPollOptionRepository extends ServiceEntityRepository implements TelegramPollOptionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TelegramPollOption::class);
    }

    public function save(TelegramPollOption $telegramPollOption): void
    {
        $this->getEntityManager()->persist($telegramPollOption);
        $this->getEntityManager()->flush();
    }

    public function delete(TelegramPollOption $telegramPollOption): void
    {
        $this->getEntityManager()->remove($telegramPollOption);
        $this->getEntityManager()->flush();
    }
}
