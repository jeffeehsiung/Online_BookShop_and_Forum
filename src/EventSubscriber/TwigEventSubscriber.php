<?php

namespace App\EventSubscriber;

use App\Repository\BookRepository;
use App\Repository\GenreRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig;
    private BookRepository $bookRepository;
    private GenreRepository $genreRepository;

    public  function __construct(\Twig\Environment $twig, GenreRepository $genreRepository, BookRepository $bookRepository) {
        $this->twig = $twig;
        $this->genreRepository = $genreRepository;
        $this->bookRepository = $bookRepository;
    }
    public function onControllerEvent(ControllerEvent $event): void
    {
        $this->twig->addGlobal('genres', $this->genreRepository->findAll());
        $this->twig->addGlobal('books', $this->bookRepository->findAll());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
