<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 10.12.17
 * Time: 21:51
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Access;
use AppBundle\Entity\Answer;
use AppBundle\Entity\PassageCondition;
use AppBundle\Entity\Passage;
use AppBundle\Entity\Question;
use AppBundle\Entity\User;
use AppBundle\Entity\Quiz;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class MySQLController extends Controller
{

    /**
     * @param Request $request
     *
     * @Route("/connect_db", name="connect_db")
     */
    public function createAction()
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: createAction(EntityManagerInterface $em)
        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setUsername('n.usiand');
        $user->setPassword('22222');
        $user->setEmail('n.usiand@test.loc');
        $user->setFullName('Nikola Usian');

        $access = $this->getDoctrine()
            ->getRepository(Access::class)
            ->find('ROLE_USER');

        $user->setAccess($access);

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new product with id '.$user->getIdUser());
    }

    // if you have multiple entity managers, use the registry to fetch them
    public function editAction()
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $em2 = $doctrine->getManager('other_connection');
    }

    /**
     * @param Request $request
     *
     * @Route("/show_db", name="show_db")
     */
    public function showAction()
    {
        $id_user = 2;

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id_user);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$id_user
            );
        }

        return new Response('user with id '.$user->getUsername());
    }

    /**
     * @param Request $request
     *
     * @Route("/add_question_bd", name="show_db")
     */
    public function addQuestionAction(EntityManagerInterface $em)
    {
        $em = $this->getDoctrine()->getManager();
//        $quest1 = $this->getDoctrine()
//            ->getRepository(Question::class)
//            ->find(1);
//
//        $quest2 = $this->getDoctrine()
//            ->getRepository(Question::class)
//            ->find(2);
//
//        $victor = new Quiz("Вясёлка");
//        $victor->addQuestion($quest1);
//        $victor->addQuestion($quest2);


        $quiz = $this->getDoctrine()
            ->getRepository(Quiz::class)
            ->find(1);

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find(6);

        $condition = $this->getDoctrine()
            ->getRepository(PassageCondition::class)
            ->find(1);

        $question = $this->getDoctrine()
            ->getRepository(Question::class)
            ->find(1);

        $answer = $this->getDoctrine()
            ->getRepository(Answer::class)
            ->find(1);


        $passage = new Passage($quiz, $user, $condition);

        $passage->addResult($question, $answer);

        $em->persist($passage);

        $em->flush();

//        $add = new AddQuestionBD('Быть или не быть?');
//        $add->addAnswer('Быть',false);
//        $add->addAnswer('Не быть',false);
//        $add->addAnswer('Сложна',true);
//        $add->pushBD($em);
//        $question = $add->getQuestion();
        return new Response('question with id '. $passage->getIdPassage());
    }
}