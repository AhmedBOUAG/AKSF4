<?php

// src/Form/CommentType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use FOS\CommentBundle\Form\CommentType as CommentFromType;

class CommentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('pseudonyme', TextType::class, [
                    'attr' => ['class' => 'form-control label-input']
                ])
                ->add('mail', EmailType::class, [
                    'attr' => ['class' => 'form-control label-input']
        ]);
    }

    public function getParent() {
        return CommentFromType::class;
    }

}
