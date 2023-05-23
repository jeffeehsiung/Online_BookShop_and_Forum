<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('goodreads_book_id')
            ->add('best_book_id')
            ->add('work_id')
            ->add('books_count')
            ->add('original_publication_year')
            ->add('isbn')
            ->add('isbn13')
            ->add('language_code')
            ->add('likes')
            ->add('image_url')
            ->add('small_image_url')
            ->add('dislikes')
            ->add('author')
            ->add('genre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
