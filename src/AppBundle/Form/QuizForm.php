<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 15.12.17
 * Time: 18:32
 */

namespace AppBundle\Form;


use AppBundle\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'class'         => 'form-control quiz_form_quiz_name',
                    'placeholder'   => 'Quiz name',
                )
            ))

            ->add('description', TextareaType::class, array(
                'label' => false,
                'attr' => array(
                    'class'         => 'form-control quiz_form_quiz_description',
                    'placeholder'   => 'Quiz description',
                )
            ))

            ->add('flag_active', CheckboxType::class, array(
                'label' => false,
                'attr' => array(
                    'checked' => 'checked',
                    'hidden' => true,
                    )

            ))

            ->add('questions', CollectionType::class, array(
                'label' => false,
                'entry_type' => QuizQuestionForm::class,
                'entry_options' => array('label' => false,
                    'attr' => array('class' => 'quiz_form_questions_box')),
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))

            ->add('save', SubmitType::class, array(
                'label' => 'Create new Quiz',
                'attr' => array(
                    'class'         => 'btn btn-default',
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Quiz::class,
        ));
    }
}