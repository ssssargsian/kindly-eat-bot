<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Infrastructure\Persistence\Doctrine\TelegramPollAnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;

/**
 * @final
 */
#[ORM\Entity(TelegramPollAnswerRepository::class)]
#[ORM\Table(name: 'telegram_poll_answers')]
class TelegramPollAnswer
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['pollAnswer:read'])]
    private UuidInterface $id;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['pollAnswer:read'])]
    private string $pollId;

    #[ORM\ManyToOne(targetEntity: TelegramUser::class, cascade: ['persist'], inversedBy: 'polls')]
    #[Groups(['pollAnswer:read'])]
    private TelegramUser $user;

    #[ORM\ManyToMany(targetEntity: TelegramPollOption::class, cascade: ['persist'])]
    #[ORM\JoinTable(
        name: 'poll_answers_options',
        joinColumns: [new JoinColumn(name: 'poll_answer_id', referencedColumnName: 'id')],
        inverseJoinColumns: [new JoinColumn(name: "poll_option_id", referencedColumnName: 'id')]
    )]
    private Collection $optionIds;

    public function __construct(
        string $pollId,
        TelegramUser $user,
        ?UuidInterface $id = null
    ) {
        $this->pollId = $pollId;
        $this->user = $user;
        $this->id = $id ?? Uuid::uuid7();
        $this->optionIds = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPollId(): string
    {
        return $this->pollId;
    }

    public function setPollId(string $pollId): self
    {
        $this->pollId = $pollId;

        return $this;
    }

    public function getUser(): TelegramUser
    {
        return $this->user;
    }

    public function setUser(TelegramUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<array-key, TelegramPollOption>
     */
    public function getOptionIds(): Collection
    {
        return $this->optionIds;
    }

    public function addOptionId(TelegramPollOption $option): self
    {
        if (!$this->optionIds->contains($option)) {
            $this->optionIds->add($option);
        }

        return $this;
    }

    public function removeOptionId(TelegramPollOption $option): self
    {
        $this->optionIds->removeElement($option);

        return $this;
    }
}
