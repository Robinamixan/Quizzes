<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 15.12.17
 * Time: 18:32
 */

namespace AppBundle\Form;


use AppBundle\Entity\Question;
use AppBundle\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Name of quiz'))

            ->add('flag_active', CheckboxType::class, array(
                'label' => 'Active',
                'attr' => array('checked' => 'checked')

            ))

            ->add('questions', CollectionType::class, array(
                'entry_type' => QuestionForm::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))

            ->add('save', SubmitType::class, array('label' => 'Create new Quiz'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Quiz::class,
        ));
    }
}