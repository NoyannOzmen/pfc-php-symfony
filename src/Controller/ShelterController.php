<?php
namespace App\Controller;

use App\Entity\Association;
use App\Entity\Espece;
use App\Entity\Animal;
use App\Entity\Tag;
use App\Entity\AnimalTag;
use App\Entity\Demande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShelterController extends AbstractController
{
    #[Route('/association/profil', name: 'shelter_dashboard', methods: ['GET'])]
    public function dashboard(EntityManagerInterface $entityManager, int $id=1): Response
    {
        $association = $entityManager->getRepository(Association::class)->find($id);

        if (!$association) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashInfos.html.twig', ['association' => $association]);
    }

    #[Route('/association/profil/logo', name: 'shelter_logo', methods: ['GET'])]
    public function logoUpload(EntityManagerInterface $entityManager, int $id=1): Response
    {
        $association = $entityManager->getRepository(Association::class)->find($id);

        if (!$association) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashLogo.html.twig', ['association' => $association]);
    }

    #[Route('/association/profil/animaux', name: 'shelter_animals_list', methods: ['GET'])]
    public function shelterAllAnimals(EntityManagerInterface $entityManager, int $id=1): Response
    {
        $association = $entityManager->getRepository(Association::class)->find($id);
        $especes = $entityManager->getRepository(Espece::class)->findAll();
        $animals = $entityManager->getRepository(Animal::class)->findBy(['association' => $id]);

        if (!$association | !$especes | !$animals) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashAnimauxList.html.twig', ['association' => $association, 'especes' => $especes, 'animals' => $animals]);
    }

    #[Route('/association/profil/animaux/suivi', name: 'shelter_fostered_animals', methods: ['GET'])]
    public function shelterFosteredAnimals(EntityManagerInterface $entityManager, int $id=1): Response
    {
        $association = $entityManager->getRepository(Association::class)->find($id);
        $animals = $entityManager->getRepository(Animal::class)->findBy(['association' => $id, 'statut' => 'Accueilli']);
        $tags = $entityManager->getRepository(AnimalTag::class)->findAll();

        if (!$association |!$animals) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashAnimauxSuiviAccueil.html.twig', ['association' => $association, 'animals' => $animals, 'tags' => $tags]);
    }

    #[Route('/association/profil/animaux/nouveau-profil', name: 'shelter_create_animal', methods: ['GET'])]
    public function shelterCreateAnimal(EntityManagerInterface $entityManager, int $id=1): Response
    {
        $association = $entityManager->getRepository(Association::class)->find($id);
        $especes = $entityManager->getRepository(Espece::class)->findAll();
        $tags = $entityManager->getRepository(Tag::class)->findAll();

        if (!$association | !$especes | !$tags) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashAnimauxCreate.html.twig', ['association' => $association, 'especes' => $especes, 'tags' => $tags]);
    }

    #[Route('/association/profil/animaux/{animalId}', name: 'shelter_animal_details', methods: ['GET'], requirements: ['page' => '\d+'])]
    public function shelterAnimalDetails(EntityManagerInterface $entityManager, int $animalId, int $id=1): Response
    {
        $association = $entityManager->getRepository(Association::class)->find($id);
        $animal = $entityManager->getRepository(Animal::class)->find($animalId);
        $demandes = $entityManager->getRepository(Demande::class)->findBy(['animal' => $animalId]);
        $tags = $entityManager->getRepository(AnimalTag::class)->findBy(['animal' => $id]);

        if (!$association | !$animal | !$demandes) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashAnimauxDetails.html.twig', ['association' => $association, 'animal' => $animal, 'demandes' => $demandes, 'tags' => $tags]);
    }

    #[Route('/association/profil/demandes', name: 'shelter_requests', methods: ['GET'])]
    public function shelterRequests(EntityManagerInterface $entityManager, int $id=1): Response
    {
        $association = $entityManager->getRepository(Association::class)->find($id);
        $requestedAnimals = $entityManager->getRepository(Animal::class)->findBy(['association' => $id]);

        if (!$association | !$requestedAnimals) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashDemandes.html.twig', ['association' => $association, 'requestedAnimals' => $requestedAnimals]);
    }

    #[Route('/association/profil/demande/{requestId}', name: 'shelter_request_details', methods: ['GET'], requirements: ['page' => '\d+'])]
    public function requestDetails(EntityManagerInterface $entityManager, int $requestId, int $id=1): Response
    {
        $association = $entityManager->getRepository(Association::class)->find($id);
        $request = $entityManager->getRepository(Demande::class)->find($requestId);
        $animalId = $request->getAnimal_accueillable();
        $animal = $entityManager->getRepository(Animal::class)->find(['id' => $animalId]);
        $tags = $entityManager->getRepository(AnimalTag::class)->findBy(['animal' => $animalId]);

        if (!$association | !$request | !$animal) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashDemandeSuivi.html.twig', ['association' => $association, 'request' => $request, 'animal' => $animal, 'tags' => $tags]);
    }
}