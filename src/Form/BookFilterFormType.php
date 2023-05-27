<?php

namespace App\Form;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class BookFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('genre', EntityType::class, [
                // looks for choices from this entity
                'class' => Genre::class,
                // use custom query for the choices
                'query_builder' => function (GenreRepository $genreRepository) {
                    return $genreRepository->createQueryBuilder('g')
                        ->orderBy('g.genre', 'ASC');
                },
                // pass the return of the query builder as choices, the method is called for each entity returned
                // uses the genre.genre property as the visible option string and convert it to a string
                'choice_label' => function (Genre $genre) {
                    return (string) $genre->getGenre();
                },
                // set the color to white for the checkbox labels and pink when hovered over
                'choice_attr' =>
                    function ($choice, $key, $value) {
                        return [
                            'class' => 'genre-filter-checkbox',
                            'onchange' => 'this.form.submit()',
                        ];
                    },
                'row_attr' => [
                    // add a class to the genre filter row for HTML styling
                    'class' => 'genre-filter-row',
                ],
                // render choices as checkboxes: multiple and expanded are both true:
                // ref:https://symfony.com/doc/current/reference/forms/types/entity.html
                'multiple' => true,
                'expanded' => true,
                'label' => 'Genres',
                'label_attr' => [
                    // add a class to the genre filter label for HTML styling
                    'class' => 'genre-header',
                ],
                'attr' => [
                    // add a class to the genre filter checkboxes for HTML styling
                    'class' => 'genre-filter-container',
                    // flexbox styling for the genre filter checkboxes to display them in a column
                    'style' => 'display: flex; flex-direction: column; flex-wrap: wrap; align-items: flex-start;',
                ],
                'translation_domain' => false,
                // the returned value is an array of Genre objects: https://symfony.com/doc/current/reference/forms/types/collection.html
                'required' => false,
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
