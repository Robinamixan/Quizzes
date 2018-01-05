<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity
 * @ORM\Table(name="Passages")
 */
class Passage
{
    /**
     * @ORM\Column(type="integer", name="id_passage")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id_passage;

    /**
     * @ORM\ManyToOne(targetEntity="PassageCondition")
     * @ORM\JoinColumn(name="id_condition", referencedColumnName="id_condition")
     */
    private $condition;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz")
     * @ORM\JoinColumn(name="id_quiz", referencedColumnName="id_quiz")
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Result", mappedBy="passage", cascade={"ALL"}, indexBy="id_result")
     */
    private $results;

    /**
     * @ORM\Column(type="date", name="date_of_create")
     */
    private $date_of_create;

    public function __construct(Quiz $quiz, User $user, PassageCondition $condition)
    {
        $this->user = $user;
        $this->quiz = $quiz;
        $this->condition = $condition;
        $this->date_of_create = new \DateTime('now');
    }

    public function addResult(Question $question, Answer $answer, \DateTime $time)
    {
        $this->results[] = new Result($question, $answer, $time, $this);
    }

    public function setDateOfCreate(Date $date)
    {
        $this->date_of_create = $date;
    }

    public function getIdPassage()
    {
        return $this->id_passage;
    }

    public function getQuiz()
    {
        return $this->quiz;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getDateOfCreate()
    {
        return $this->date_of_create;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function setCondition(PassageCondition $condition)
    {
        $this->condition = $condition;
    }
}