<?php

namespace BoutiqueBundle\Form;

use BoutiqueBundle\Entity\Categorie_Boutique;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('categorie', EntityType::class, array(
            'class'=> Categorie_Boutique::class,
            'choice_label'=> 'categorie.nomCategorie',
            'multiple'=>false

        ))
            ->add('livgrat',ChoiceType::class, [
                'label' => 'Livraison gratuite',
                'choices'  => [
                    'je ne sais pas'=>'rien',
                    'Non' => false,
                    'Oui' => true,



                ],
            ])

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BoutiqueBundle\Entity\Filtre'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'boutiquebundle_filtre';
    }


}
