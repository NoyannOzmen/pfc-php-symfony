<?php
namespace App\Controller;

use App\Entity\Association;
use App\Entity\Espece;
use App\Entity\Animal;
use App\Entity\Tag;
use App\Entity\AnimalTag;
use App\Entity\Demande;
use App\Entity\Famille;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShelterController extends AbstractController
{
    #[Route('/association/profil', name: 'shelter_dashboard', methods: ['GET'])]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $refuge = $user->getRefuge();
        $id = $refuge->getId();

        $association = $entityManager->getRepository(Association::class)->find($id);

        if (!$association) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashInfos.html.twig', ['association' => $association]);
    }

    #[Route('/association/profil/update', name: 'shelter_profile_update', methods: ['POST'])]
    public function fosterUpdate(EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $accueillant = $user->getRefuge();
        $id = $accueillant->getId();

        $association = $entityManager->getRepository(Association::class)->find($id);

        if (!$association) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }

        $nom = $request->request->get('_nom');
        $president = $request->request->get('_president');
        $rue = $request->request->get('_rue');
        $commune = $request->request->get('_commune');
        $code_postal = $request->request->get('_code_postal');
        $pays = $request->request->get('_pays');
        $telephone = $request->request->get('_telephone');
        $siret = $request->request->get('_siret');
        $site = $request->request->get('_site');
        $description = $request->request->get('_description');

        if (isset($nom)) {$association->setNom($nom);};
        if (isset($president)) {$association->setResponsable($president);};
        if (isset($rue)) {$association->setRue($rue);};
        if (isset($commune)) {$association->setCommune($commune);};
        if (isset($$code_postal)) {$association->setCode_postal($code_postal);};
        if (isset($$pays)) {$association->setPays($pays);};
        if (isset($$telephone)) {$association->setTelephone($telephone);};
        if (isset($$siret)) {$association->setSiret($siret);};
        if (isset($$site)) {$association->setSite($site);};
        if (isset($$description)) {$association->setDescription($description);};

        $entityManager->flush();
        $message = "Updated successfully !";

        return $this->render('shelter/dashInfos.html.twig', ['association' => $association, 'message' => $message]);
    }

    #[Route('/association/profil/logo', name: 'shelter_logo', methods: ['GET'])]
    public function logoUpload(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $refuge = $user->getRefuge();
        $id = $refuge->getId();

        $association = $entityManager->getRepository(Association::class)->find($id);

        if (!$association) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashLogo.html.twig', ['association' => $association]);
    }

    #[Route('/association/profil/animaux', name: 'shelter_animals_list', methods: ['GET'])]
    public function shelterAllAnimals(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $refuge = $user->getRefuge();
        $id = $refuge->getId();

        $association = $entityManager->getRepository(Association::class)->find($id);
        $especes = $entityManager->getRepository(Espece::class)->findAll();
        $animals = $entityManager->getRepository(Animal::class)->findBy(['association' => $id]);

        if (!$animals) {
            $animals = [];
        }

        if (!$association | !$especes) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashAnimauxList.html.twig', ['association' => $association, 'especes' => $especes, 'animals' => $animals]);
    }

    #[Route('/association/profil/animaux/suivi', name: 'shelter_fostered_animals', methods: ['GET'])]
    public function shelterFosteredAnimals(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $refuge = $user->getRefuge();
        $id = $refuge->getId();

        $association = $entityManager->getRepository(Association::class)->find($id);
        $animals = $entityManager->getRepository(Animal::class)->findBy(['association' => $id, 'statut' => 'Accueilli']);
        $tags = $entityManager->getRepository(AnimalTag::class)->findAll();

        if (!$animals) {
            $animals = [];
        }

        if (!$association) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashAnimauxSuiviAccueil.html.twig', ['association' => $association, 'animals' => $animals, 'tags' => $tags]);
    }

    #[Route('/association/profil/animaux/nouveau-profil', name: 'shelter_create_animal', methods: ['GET'])]
    public function shelterCreateAnimal(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $refuge = $user->getRefuge();
        $id = $refuge->getId();

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
    public function shelterAnimalDetails(EntityManagerInterface $entityManager, int $animalId): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $refuge = $user->getRefuge();
        $id = $refuge->getId();

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
    public function shelterRequests(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $refuge = $user->getRefuge();
        $id = $refuge->getId();

        $association = $entityManager->getRepository(Association::class)->find($id);
        $requestedAnimals = $association->getPensionnaires()->filter(
            function(Animal $animals) {
                return $animals->getStatut() === "En refuge";
            });

        if (!$association | !$requestedAnimals) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashDemandes.html.twig', ['association' => $association, 'requestedAnimals' => $requestedAnimals]);
    }

    #[Route('/association/profil/demande/{requestId}', name: 'shelter_request_details', methods: ['GET'], requirements: ['page' => '\d+'])]
    public function requestDetails(EntityManagerInterface $entityManager, int $requestId): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $refuge = $user->getRefuge();
        $id = $refuge->getId();
        
        $association = $entityManager->getRepository(Association::class)->find($id);
        $request = $entityManager->getRepository(Demande::class)->find($requestId);
        $animalId = $request->getAnimal_accueillable();
        $animal = $entityManager->getRepository(Animal::class)->find(['id' => $animalId]);
        $tags = $entityManager->getRepository(AnimalTag::class)->findBy(['animal' => $animalId]);
        $fosterId = $request->getPotentiel_accueillant();
        $famille = $entityManager->getRepository(Famille::class)->find(['id' => $fosterId]);

        if (!$association | !$request | !$animal) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashDemandeSuivi.html.twig', ['association' => $association, 'request' => $request, 'animal' => $animal, 'tags' => $tags, 'famille' => $famille]);
    }

    #[Route('/association/profil/delete', name: 'shelter_delete_account', methods: ['GET'])]
    public function deleteAccount(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $refuge = $user->getRefuge();
        $id = $refuge->getId();

        $shelter = $entityManager->getRepository(Association::class)->find($id);

        $entityManager->remove($shelter);
        $entityManager->remove($user);
        $entityManager->flush();
        
        return $this->redirectToRoute('accueil');
    }
}