<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;



class FormationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', null, [
                'widget' => 'single_text',
                'label' => 'Date',
                'required' => true,
                'constraints' => [
                  new LessThanOrEqual('today')
                ],
            ])
            ->add('title', null, [
                'label' => 'Titre',
                'required' => true,
            ])
            ->add('description')
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'name',
                'required' => true,
                
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
            ->add('submit', SubmitType::class, array('label' => 'Enregistrer'));
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
