<?php

namespace BoutiqueBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nomCategorie')
            ->add('couleur',ChoiceType::class, [
                'label' => 'Cette catégorie admet- elle un choix de couleur ?',
                'choices'  => [
                    'Non' => false,
                    'Oui' => true,

                ],
            ])
            ->add('taille',ChoiceType::class, [
                'label' => 'Cette catégorie admet- elle un choix de taille ?',
                'choices'  => [
                    'Non' => false,
                    'Oui' => true,

                ],
            ])

        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BoutiqueBundle\Entity\Categorie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'boutiquebundle_categorie';
    }


}
