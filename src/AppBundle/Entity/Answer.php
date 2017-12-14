<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="Answers")
 */
class Answer
{
    /**
     * @ORM\Column(type="integer", name="id_answer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id_answer;

    /**
     * @ORM\Column(type="string", length=100, name="answer_text")
     */
    private $answer_text;

    /**
     * @ORM\Column(type="boolean", name="flag_right")
     */
    private $flag_right;

    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="answers")
     * @ORM\JoinColumn(name="id_question", referencedColumnName="id_question")
     */
    private $question;

    public function __construct(string $text, bool $flag, Question $question)
    {
        $this->answer_text = $text;
        $this->flag_right = $flag;
        $this->question = $question;
    }

    public function setAnswerText(string $text):void
    {
        $this->answer_text = $text;
    }

    public function setFlagRight(bool $flag):void
    {
        $this->flag_right = $flag;
    }

    public function getAnswerText():string
    {
        return $this->answer_text;
    }

    public function getFlagRight():bool
    {
        return $this->flag_right;
    }

    public function getIdAnswer():string
    {
        return $this->id_answer;
    }
}