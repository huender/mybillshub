<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $builder->getData()->getUser();
        $builder
            ->add('name', null, array(
                'attr' => [
                    'autofocus' => 'autofocus'
                ]
            ))
            ->add('url', null, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('isRecurrence',null, [
                'label' => 'Recurrence payment?',
                'attr' => [
                    'class' => 'form-check-input'
                ]
            ])
            ->add('paymentDay')
            ->add('paymentDate', null, [
                'format' => 'ddMMyyyy',
                'placeholder' => array(
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                )
            ])
            ->add('category', null, [
                'class' => 'AppBundle\Entity\Category',
                'query_builder' => function(EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('c')
                        ->where('c.isActive = true')
                        ->andWhere('c.user = :user')
                        ->setParameter("user", $user)
                        ->orderBy('c.name', 'ASC');
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Service'
        ));
    }
}
