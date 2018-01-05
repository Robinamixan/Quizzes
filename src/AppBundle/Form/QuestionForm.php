<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 15.12.17
 * Time: 18:32
 */

namespace AppBundle\Form;

use AppBundle\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question_text', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'class'         => 'form-control question_form_answers',
                    'placeholder'   => 'Question text',
                )
            ))
            ->add('answers', CollectionType::class, array(
                'label' => false,
                'entry_type' => AnswerForm::class,
                'entry_options' => array('label' => false,
                    'attr' => array('class' => 'question_form_answers_box')),
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Create new question',
                'attr' => array(
                    'class'         => 'btn btn-default',
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Question::class,
        ));
    }
}