<?php

namespace App\Twig;

use App\Repository\CategorieRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('categorieNavbar', [$this, 'categorie']),
        ];
    }

    public function categorie(): array
    {
        return $this->categorieRepository->findAll();
    }
}