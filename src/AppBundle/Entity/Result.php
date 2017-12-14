<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Time;

/**
 * @ORM\Entity
 * @ORM\Table(name="Results")
 */
class Result
{
    /**
     * @ORM\Column(type="integer", name="id_result")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id_result;

    /**
     * @ORM\Column(type="time", name="time")
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="Passage", inversedBy="results")
     * @ORM\JoinColumn(name="id_passage", referencedColumnName="id_passage")
     */
    private $passage;

    /**
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumn(name="id_question", referencedColumnName="id_question")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="Answer")
     * @ORM\JoinColumn(name="id_answer", referencedColumnName="id_answer")
     */
    private $answer;

    public function __construct(Question $question, Answer $answer, Passage $passage)
    {
        $this->passage = $passage;
        $this->answer = $answer;
        $this->question = $question;
        $this->time = new Time(12);
    }

    public function getAnswer()
    {
        return $this->answer;
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function getIdResult()
    {
        return $this->id_result;
    }
}