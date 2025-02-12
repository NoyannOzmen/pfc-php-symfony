<?php
namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Tag;
use App\Entity\AnimalTag;
use App\Entity\Espece;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnimalController extends AbstractController
{
    #[Route('/animaux', name: 'animals', methods: ['GET'])]
    public function displayAll(EntityManagerInterface $entityManager): Response
    {
        $animals = $entityManager->getRepository(Animal::class)->findAll();
        $tags = $entityManager->getRepository(Tag::class)->findAll();
        $especes = $entityManager->getRepository(Espece::class)->findAll();

        if (!$animals | !$tags | !$especes ) {
            throw $this->createNotFoundException(
                'We\'re missing something'
            );
        }
        
        return $this->render('animaux/animalList.html.twig', ['animals' => $animals, 'tags' => $tags, 'especes' => $especes]);
    }

    #[Route('/animaux/{id}', name: 'animal_details', requirements: ['page' => '\d+'])]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $animal = $entityManager->getRepository(Animal::class)->find($id);
        $tags = $entityManager->getRepository(AnimalTag::class)->findBy(['animal' => $id]);
        $shelter = $animal->getRefuge();

        if (!$animal) {
            throw $this->createNotFoundException(
                'No Animal found for id '.$id
            );
        }
        
        return $this->render('animaux/animalDetails.html.twig', ['animal' => $animal, 'tags' => $tags, 'shelter' => $shelter]);
    }
}