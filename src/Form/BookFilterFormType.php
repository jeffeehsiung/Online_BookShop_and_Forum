<?php

namespace App\Form;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //for each genre in the database, create a checkbox, and set the label to the genre name
            ->add('genre', CheckboxType::class, [
                // display the genre name as the label
//                'label' => function (Genre $genre) {
//                    return (string) $genre->getGenre();
//                },
                'label' => 'Genre',
                'label_attr' => [
                    'class' => 'genre-label',
                    'color' => 'white',
                    'hover' => 'gold',
                ],
                'mapped' => false,
                'required' => false,
                'translation_domain' => false,
                'attr' => [
                    'class' => 'genre-checkbox',
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Genre::class,
        ]);
    }
}
