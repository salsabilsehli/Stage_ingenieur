<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminType extends AbstractType
{
    const noir = 'assets_souk/css/skin-11.css';
    const rouge = 'assets_souk/css/skin-10.css';
    const jaune = 'assets_souk/css/skin-9.css';
    const vert = 'assets_souk/css/skin-8.css';
    const marron = 'assets_souk/css/skin-6.css';
    const violet = 'assets_souk/css/skin-4.css';
    const bleu = 'assets_souk/css/skin-3.css';
    const vertclair = 'assets_souk/css/skin-1.css';



    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raisonsociale',TextareaType::class,[
                'label'=>'Raison sociale'
            ])
            ->add('num')
        ->add('theme',ChoiceType::class,[
            'choices' => [
                'noir' =>self::noir,
                'marron' =>self::marron,
                'rouge'=>self::rouge,
                'vert'=>self::vert,
                'bleu'=>self::bleu,
                'jaune'=>self::jaune,
                'violet'=>self::violet,
                'vert clair'=>self::vertclair

            ],
            'expanded' => true,
            'multiple'=>false
    ]);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_user';
    }


}
