<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity
 * @ORM\Table(name="Quizzes")
 */
class Quiz
{
    /**
     * @ORM\Column(type="integer", name="id_quiz")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id_quiz;

    /**
     * @ORM\Column(type="string", length=100, name="name")
     */
    private $name;

    /**
     * @ORM\Column(type="date", name="data_of_create")
     */
    private $date_of_create;

    /**
     * @ORM\Column(type="boolean", name="flag_active")
     */
    private $flag_active;

    /**
     * @ORM\ManyToMany(targetEntity="Question")
     * @ORM\JoinTable(name="Quiz_questions",
     *     joinColumns={@ORM\JoinColumn(name="id_quiz", referencedColumnName="id_quiz")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id_question", referencedColumnName="id_question")})
     *
     */
    private $questions;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->flag_active = true;
        $this->date_of_create = new \DateTime('now');
        $this->questions = new ArrayCollection();
    }

    public function setName(string $name)
    {
        $this->victorine_name = $name;
    }

    public function setDateOfCreate(Date $date)
    {
        $this->date_of_create = $date;
    }

    public function setFlagActive(bool $flag)
    {
        $this->flag_active = $flag;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDateOfCreate()
    {
        return $this->date_of_create;
    }

    public function getFlagActive()
    {
        return $this->flag_active;
    }

    public function getIdQuiz()
    {
        return $this->id_quiz;
    }

    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;
    }
}