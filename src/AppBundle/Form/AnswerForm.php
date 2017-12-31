<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 15.12.17
 * Time: 18:32
 */

namespace AppBundle\Form;


use AppBundle\Entity\Answer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('flag_right', CheckboxType::class, array(
                'label' => false,
                'attr' => array(
                    'class'         => 'question_form_flag_right',
                ),
                'required' => false,
            ))
            ->add('answer_text', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'class'         => 'form-control question_form_answer_text',
                    'placeholder'   => 'Answer text',
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Answer::class,
        ));
    }
}