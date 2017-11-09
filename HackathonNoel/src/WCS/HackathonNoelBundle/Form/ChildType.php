<?php

namespace WCS\HackathonNoelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WCS\HackathonNoelBundle\Form\GiftType;


class ChildType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',       TextType::class, array(
                    'label' => "Ton Nom",
                ))
                ->add('firstname',  TextType::class, array(
                    'label' => 'Ton Prénom',
                ))
                ->add('address',    TextType::class, array(
                    'label' => 'Ton Adresse',
                ))
                ->add('zipcode',    TextType::class, array(
                    'label' => 'Code postal',
                ))
                ->add('city',       TextType::class, array(
                    'label' => 'Ta Ville',
                ))
                ->add('country',    TextType::class, array(
                    'label' => 'Ton Pays',
                ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WCS\HackathonNoelBundle\Entity\Child'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'wcs_hackathonnoelbundle_child';
    }


}
