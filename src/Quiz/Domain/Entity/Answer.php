<?php

declare(strict_types=1);

namespace App\Quiz\Domain\Entity;

use App\Quiz\Domain\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Answer
 *
 * @package App\Quiz\Domain\Entity
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
#[ORM\Entity(repositoryClass: AnswerRepository::class)]
#[ORM\Table(name: 'platform_quiz_answer')]
class Answer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private ?string $text;

    #[ORM\Column(type: 'boolean')]
    private ?bool $correct;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy:'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question;

    /**
     * This field will not be persisted
     * It is used to store the answer given by student (type="boolean") in the form
     */
    private bool $workout_correct_given = false;


    public function __construct()
    {
        //$this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCorrect(): ?bool
    {
        return $this->correct;
    }

    public function setCorrect(bool $correct): self
    {
        $this->correct = $correct;

        return $this;
    }

    public function getWorkoutCorrectGiven(): ?bool
    {
        return $this->workout_correct_given;
    }

    public function setWorkoutCorrectGiven(bool $workout_correct_given): self
    {
        $this->workout_correct_given = $workout_correct_given;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

}
