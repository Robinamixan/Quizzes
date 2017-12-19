<?php

namespace AppBundle\Service;


use AppBundle\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class AddQuestionBD
{
    private $question;

    public function __construct(string $question_text)
    {
        $this->question = new Question($question_text);
    }

    public function addAnswer(string $text, bool $flag): void
    {
        $this->question->addAnswer($text, $flag);
    }

    public function pushBD(EntityManagerInterface $em): void
    {
        $em->persist($this->question);
        $em->flush();
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }
}