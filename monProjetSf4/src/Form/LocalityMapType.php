<?php

namespace App\Form;

use App\Entity\LocalityMap;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class LocalityMapType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('localityType', TextType::class, [
                    'attr' => ["class" => "form-control"]
                ])
                ->add('picto', TextType::class, [
                    'attr' => ["class" => "form-control"]
                ])
                ->add('color', ColorType::class, [
                    'attr' => ["class" => "form-control"]
                ])
                ->add('coordinated', HiddenType::class, [
                    'attr' => ['class' => 'hidden-row']
        ]);

        $builder->get('coordinated')
                ->addModelTransformer(new CallbackTransformer(
                    function ($tagsAsArray) {
                        return implode(', ', $tagsAsArray);
                    },
                    function ($tagsAsString) {
                        return explode(', ', $tagsAsString);
                    }
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => LocalityMap::class,
        ]);
    }

}
