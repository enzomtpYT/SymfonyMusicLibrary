<?php

namespace App\Form;

use App\Entity\Artiste;
use App\Entity\Release;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReleaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('thumbnailURL')
            ->add('category')
            ->add('artiste', EntityType::class, [
                'class' => Artiste::class,
                'placeholder' => 'Sélectionner un artiste',
'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Release::class,
            'csrf_protection' => false,
        ]);
    }
}
