<?php

namespace App\Domain\Entity;

use App\Infrastructure\Persistence\Doctrine\TelegramPollAnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * @final
 */
#[ORM\Entity(TelegramPollAnswerRepository::class)]
#[ORM\Table(name: 'telegram_poll_options')]
class TelegramPollOption
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['pollOption:read'])]
    private UuidInterface $id;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['pollOption:read'])]
    private int $pollId;

    public function __construct(
        int $pollId,
        ?UuidInterface $id = null
    ) {
        $this->pollId = $pollId;
        $this->id = $id ?? Uuid::uuid7();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPollId(): int
    {
        return $this->pollId;
    }

    public function setPollId(int $pollId): self
    {
        $this->pollId = $pollId;

        return $this;
    }
}
