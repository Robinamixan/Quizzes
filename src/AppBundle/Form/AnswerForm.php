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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answer_text', TextType::class, array(
                'label' => false,
                'attr' => array(
                    'class'         => 'form-control quiz_form_questions',
                    'placeholder'   => 'Question text',
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