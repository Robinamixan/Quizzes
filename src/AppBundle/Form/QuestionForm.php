<?php
/**
 * Created by PhpStorm.
 * User: robinam
 * Date: 15.12.17
 * Time: 18:32
 */

namespace AppBundle\Form;


use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, array('label' => 'Text question'))
            ->add('password', PasswordType::class, array('label' => 'Password'))
            ->add('email', EmailType::class, array('label' => 'Email'))
            ->add('full_name', TextType::class, array('label' => 'Full Name'))
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}