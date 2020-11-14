<?php

namespace BoutiqueBundle\Form;

use BoutiqueBundle\Entity\Categorie;
use BoutiqueBundle\Entity\Marque;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('nomProd')
            ->add('description')
            ->add('prix')
            ->add('Qte_Stock',NumberType::class,array(
                'attr'     => array(
                'value' => '0',
                'min' => '0',
                'max' => '1000',
                'step' => '1')
            ))
            ->add('marque',EntityType::class, array(
                'class'=> Marque::class,
                'choice_label'=> 'nomMarque',
                'multiple'=>false

            ))
            ->add('categorie', EntityType::class, array(
                'class'=> Categorie::class,
                'choice_label'=> 'nomCategorie',
                 'multiple'=>false

            ))
            ->add('image', FileType::class, array('label' => 'Photo(JPG)','data_class' => null));;

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
