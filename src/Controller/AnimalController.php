<?php
namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Tag;
use App\Entity\AnimalTag;
use App\Entity\Espece;
use App\Entity\Famille;
use App\Entity\Demande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/animaux/{animalId}', name: 'animal_details', requirements: ['page' => '\d+'])]
    public function show(Request $request, EntityManagerInterface $entityManager, int $animalId): Response
    {
        $animal = $entityManager->getRepository(Animal::class)->find($animalId);
        $tags = $entityManager->getRepository(AnimalTag::class)->findBy(['animal' => $animalId]);
        $shelter = $animal->getRefuge();

        if (!$animal) {
            throw $this->createNotFoundException(
                'No Animal found for id '.$animalId
            );
        }

        if ($request->isMethod('POST')) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
            /** @var \App\Entity\Utilisateur $user */
            $user = $this->getUser();
            /** @var \App\Entity\Utilisateur $user */
            $accueillant = $user->getAccueillant();
            $id = $accueillant->getId();
    
            $famille = $entityManager->getRepository(Famille::class)->find($id);
            
            $animal = $entityManager->getRepository(Animal::class)->find($animalId);
    
            if (!$animal) {
                throw $this->createNotFoundException(
                    'No Animal found for id '.$animalId
                );
            }
    
            $newRequest = new Demande();
            $newRequest->setAnimal_accueillable($animal);
            $newRequest->setPotentiel_accueillant($famille);
            $date_debut = date('Y/m/d');
            $newRequest->setDate_debut($date_debut);
            $date_fin = date('Y/m/d', strtotime('+1 year'));
            $newRequest->setDate_fin($date_fin);
            $newRequest->setStatut_demande("En attente");
            $entityManager->persist($newRequest);
            $entityManager->flush();
        }
        
        return $this->render('animaux/animalDetails.html.twig', ['animal' => $animal, 'tags' => $tags, 'shelter' => $shelter]);
    }
}