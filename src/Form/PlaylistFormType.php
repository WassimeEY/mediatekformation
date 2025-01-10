<?php

namespace App\Form;

use App\Entity\Playlist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use \Symfony\Component\Form\Extension\Core\Type\SubmitType;
use \Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PlaylistFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
            'attr' => ['rows' => 10],   //ça nous permet d'avoir un élément "textarea" plus grand verticalement.
            ])
            ->add('formations',null,[
            'attr' => ['size' => 30],
            'disabled' => true
            ])    
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
