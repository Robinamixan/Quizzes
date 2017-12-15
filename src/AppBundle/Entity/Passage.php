<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @ORM\ManyToOne(targetEntity="Condition")
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

    public function __construct(Quiz $quiz, User $user, Condition $condition)
    {
        $this->user = $user;
        $this->quiz = $quiz;
        $this->condition = $condition;
    }

    public function addResult(Question $question, Answer $answer)
    {
        $this->results[] = new Result($question, $answer, $this);
    }

    public function getIdPassage()
    {
        return $this->id_passage;
    }
}