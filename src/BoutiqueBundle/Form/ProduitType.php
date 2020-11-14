<?php

namespace BoutiqueBundle\Form;

use BoutiqueBundle\Entity\Categorie;
use BoutiqueBundle\Entity\Categorie_Boutique;
use BoutiqueBundle\Entity\Marque;
use BoutiqueBundle\Entity\Marque_Boutique;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('nomProd',TextareaType::class,[
            'label' => 'Nom du produit',
        ])
            ->add('description')
            ->add('prix')
            ->add('Qte_Stock',NumberType::class,array(
                'label' => 'Quantité en stock',
                'attr'     => array(
                'value' => '0',
                'min' => '0',
                'max' => '1000',
                'step' => '1')
            ))
            ->add('Remise',NumberType::class,array(
                'attr'     => array(
                    'value' => '0',
                    'min' => '0',
                    'max' => '100',
                    'step' => '1')
            ))
            ->add('livgrat',ChoiceType::class, [
                'label' => 'Livraison gratuite',
                'choices'  => [
                    'Non' => false,
                    'Oui' => true,

                ],
            ])
            ->add('marque',EntityType::class, array(
                'class'=> Marque_Boutique::class,

                'choice_label'=> 'marque.nomMarque',
                'multiple'=>false

            ))
            ->add('categorie', EntityType::class, array(
                'class'=> Categorie_Boutique::class,
                'choice_label'=> 'categorie.nomCategorie',
                 'multiple'=>false

            ))
            ->add('image', FileType::class, array('label' => 'Photo(JPG)','data_class' => null))

        ;

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BoutiqueBundle\Entity\Produit',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'boutiquebundle_produit';
    }


}
