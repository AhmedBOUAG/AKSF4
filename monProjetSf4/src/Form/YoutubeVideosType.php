<?php

namespace App\Form;

use App\Entity\YoutubeVideos;
use App\Entity\CategorieYoutube;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class YoutubeVideosType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title', TextType::class, [
                    'attr' => ['class' => 'form-control']
                ])
                ->add('linkYoutube', TextType::class, [
                    'attr' => ['class' => 'form-control'],
                ])
                ->add('description')
                ->add('categorie', EntityType::class, [
                    'class' => CategorieYoutube::class,
                    'attr' => ['class' => 'form-control w-50'],
                    'placeholder' => '----------------'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => YoutubeVideos::class,
        ]);
    }

}
