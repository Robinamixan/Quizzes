<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="Questions")
 */
class Question
{
    /**
     * @ORM\Column(type="integer", name="id_question")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id_quetion;

    /**
     * @ORM\Column(type="string", length=300, name="question_text")
     */
    private $question_text;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question", cascade={"ALL"}, indexBy="id_answer")
     */
    private $answers;

    public function __construct(string $text)
    {
        $this->question_text = $text;
    }

    public function setQuestionText(string $text):void
    {
        $this->question_text = $text;
    }

    public function addAnswer(string $text, bool $flag):void
    {
        $this->answers[] = new Answer($text, $flag, $this);
    }

    public function getQuestionText():string
    {
        return $this->question_text;
    }

    public function getAnswers():array
    {
        return $this->answers;
    }

    public function getIdQuestion():string
    {
        return $this->id_quetion;
    }
}