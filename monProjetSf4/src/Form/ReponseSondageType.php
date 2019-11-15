<?php

namespace App\Form;

use App\Entity\ReponseSondage;
use App\Entity\QuestionSondage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReponseSondageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reponse')
            ->add('question', EntityType::class,[
                'class' => QuestionSondage::class,
                'choice_label' => 'titre',
                'label_attr' => ['class' => 'control-label'] 
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReponseSondage::class,
        ]);
    }
}
