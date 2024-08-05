<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Infrastructure\Persistence\Doctrine\TelegramPollAnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * @final
 */
#[ORM\Entity(TelegramPollAnswerRepository::class)]
#[ORM\Table(name: 'telegram_users')]
class TelegramUser
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['telegram:read'])]
    private UuidInterface $id;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['telegram:read'])]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['telegram:read'])]
    private string $lastName;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['telegram:read'])]
    private string $username;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['telegram:read'])]
    private int $chatId;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['telegram:read'])]
    private bool $bot = false;

    #[ORM\OneToMany(targetEntity: TelegramPollAnswer::class, mappedBy: 'user', cascade: ['persist'], orphanRemoval: true)]
    private Collection $polls;

    public function __construct(
        string $name,
        string $lastName,
        string $username,
        int $chatId,
        ?UuidInterface $id = null
    ) {
        $this->name = $name;
        $this->lastName = $lastName;
        $this->username = $username;
        $this->chatId = $chatId;
        $this->id = $id ?? Uuid::uuid7();
        $this->polls = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function isBot(): bool
    {
        return $this->bot;
    }

    public function setBot(bool $bot): self
    {
        $this->bot = $bot;

        return $this;
    }

    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function setChatId(int $chatId): self
    {
        $this->chatId = $chatId;

        return $this;
    }

    /**
     * @return Collection<array-key, TelegramPollAnswer>
     */
    public function getPolls(): Collection
    {
        return $this->polls;
    }

    public function addPoll(TelegramPollAnswer $poll): self
    {
        $this->polls->add($poll);

        return $this;
    }

    public function removePoll(TelegramPollAnswer $poll): self
    {
        $this->polls->removeElement($poll);

        return $this;
    }
}
