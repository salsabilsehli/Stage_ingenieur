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

class ProduitcType extends AbstractType
{
    const noir = '#000000';
    const blanc = '#FFFFFF';
    const rouge = '#FF0000';
    const vert = '#008000';
    const bleu = '#0000FF';
    const jaune = '#FFFF00';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
        ->add('couleurs',ChoiceType::class,[
            'label' =>' Veuillez choisir les couleurs disponibles de ce produit',
            'choices' => [
                    'noir' =>self::noir,
                    'blanc' =>self::blanc,
                    'rouge'=>self::rouge,
                    'vert'=>self::vert,
                    'bleu'=>self::bleu,
                    'jaune'=>self::jaune
            ],
            'expanded' => true,
            'multiple'=>true
        ])
        ;

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BoutiqueBundle\Entity\Produitc',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'boutiquebundle_produitc';
    }


}
