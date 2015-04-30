<?php

namespace Quiz\LectureQuizBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'email', array('required' => 'required', 'label'=>'Email'))
            ->add('fullname', 'text', array('required' =>'required'))
            ->add('email', 'text', array('required'=>'required', 'label'=>'Username'))
            ->add('password', 'password', array('required' => 'required',
                'attr'=>array('minLength' => 8,
                              'maxLength' => 4096,
                              'pattern' => '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}',
                              'title'=>'Minimum 8 characters and contain atleast one digit,lowercase and uppercase letters'

                             )));


    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Quiz\LectureQuizBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'quiz_lecturequizbundle_user';
    }
}
