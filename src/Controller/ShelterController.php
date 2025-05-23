<?php
namespace App\Controller;

use App\Entity\Association;
use App\Entity\Espece;
use App\Entity\Animal;
use App\Entity\Tag;
use App\Entity\AnimalTag;
use App\Entity\Media;
use App\Entity\Demande;
use App\Entity\Famille;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Form\MediaType;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/association/profil/logo', name: 'shelter_logo')]
    public function logoUpload(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/images/animaux')] string $mediaDirectory
    ): Response
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

        $newLogo = new Media();
        $form = $this->createForm(MediaType::class, $newLogo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $mediaFile */
            $mediaFile = $form->get('media')->getData();

            // this condition is needed because the 'media' field is not required
            if ($mediaFile) {
                $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                /* $newFilename = $safeFilename.'-'.uniqid().'.'.$mediaFile->guessExtension(); */
                // Without uniqid, it looks like this
                $newFilename = $safeFilename.'.'.$mediaFile->guessExtension();

                // Move the file to the directory where medias are stored
                try {
                    $mediaFile->move($mediaDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'mediaFilename' property to store the file name
                // instead of its contents
                $newLogo->setmediaFilename($newFilename);
                $newLogo->setOrdre(1);
                $newLogo->setAssociationId($association);
                $newLogo->setUrl('/images/animaux/' . $newFilename);
            }

            $entityManager->persist($newLogo);
            $entityManager->flush();

            return $this->redirectToRoute('shelter_dashboard');
        }

        return $this->render('shelter/dashLogo.html.twig', [
            'form' => $form,
            'association' => $association
        ]);
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

    #[Route('/association/profil/animaux/nouveau-profil', name: 'shelter_create_animal')]
    public function shelterCreateAnimal(EntityManagerInterface $entityManager, Request $request): Response
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

        if ($request->isMethod('POST')) {
            $animalForm = $request->request->get('create_animal');
            $tagForm = $request->request->get('create_tag');

            if (isset($animalForm)) {
                $name = $request->request->get('_nom_animal');
                $sex = $request->request->get('_sexe_animal');
                $age = $request->request->get('_age_animal');
                $species = $request->request->get('_espece_animal');
                $espece = $entityManager->getRepository(Espece::class)->find($species);
                $race = $request->request->get('_race_animal');
                $colour = $request->request->get('_couleur_animal');
                $description = $request->request->get('_description_animal');

                $animalTags = $request->request->all('_tag');
                foreach($animalTags as $tag) {
                    $tag = $entityManager->getRepository(Tag::class)->find($tag);
                    $tagsToAdd[] = $tag;
                };
                
                $newAnimal = new Animal();
                $newAnimal->setNom($name);
                $newAnimal->setSexe($sex);
                $newAnimal->setAge($age);
                $newAnimal->setEspece($espece);
                if (isset($race)) {
                    $newAnimal->setRace($race);
                }
                $newAnimal->setCouleur($colour);
                $newAnimal->setDescription($description);
                $newAnimal->setRefuge($association);
                if (isset($tagsToAdd)) {
                    foreach ($tagsToAdd as $tagToAdd) {
                        $tag = $entityManager->getRepository(AnimalTag::class)->find($tagToAdd);
                        $newAnimal->addTag($tag);
                    }
                };
                $newAnimal->setStatut('En Refuge');
                $entityManager->persist($newAnimal);

                $animalId = $newAnimal->getId();
                $newPhoto = new Media();
                $newPhoto->setOrdre(1);
                $newPhoto->setAnimalId($animalId);
                $newPhoto->setUrl('/images/animal_empty.webp');
                $entityManager->persist($newPhoto);

                $this->addFlash('notice', "Profil animal créé avec succès");
            } 
            
            if (isset($tagForm)) {
                $tagName = $request->request->get('_name_tag');
                $tagDesc = $request->request->get('_desc_tag');

                $newTag = new Tag();
                $newTag->setNom($tagName);
                $newTag->setDescription($tagDesc);
                $entityManager->persist($newTag);

                $this->addFlash('notice', "Nouveau tag créé avec succès");
            }
            $entityManager->flush();
            return $this->redirectToRoute('shelter_create_animal');
        }

        return $this->render('shelter/dashAnimauxCreate.html.twig', ['association' => $association, 'especes' => $especes, 'tags' => $tags]);
    }

    #[Route('/association/profil/animaux/{animalId}', name: 'shelter_animal_details', requirements: ['page' => '\d+'])]
    public function shelterAnimalDetails(
            Request $request,
            EntityManagerInterface $entityManager,
            SluggerInterface $slugger,
            #[Autowire('%kernel.project_dir%/public/images/animaux')] string $mediaDirectory,
            int $animalId
        ): Response
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

        if (!$demandes) {
            $demandes = [];
        }

        if (!$association | !$animal ) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
    
        $newPhoto = new Media();
        $form = $this->createForm(MediaType::class, $newPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $mediaFile */
            $mediaFile = $form->get('media')->getData();

            // this condition is needed because the 'media' field is not required
            if ($mediaFile) {
                $originalFilename = pathinfo($mediaFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                /* $newFilename = $safeFilename.'-'.uniqid().'.'.$mediaFile->guessExtension(); */
                // Without uniqid, it looks like this
                $newFilename = $safeFilename.'.'.$mediaFile->guessExtension();

                // Move the file to the directory where medias are stored
                try {
                    $mediaFile->move($mediaDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'mediaFilename' property to store the file name
                // instead of its contents
                $newPhoto->setmediaFilename($newFilename);
                $newPhoto->setOrdre(1);
                $newPhoto->setAnimalId($animal);
                $newPhoto->setUrl('/images/animaux/' . $newFilename);
            }

            $entityManager->persist($newPhoto);
            $entityManager->flush();

            return $this->redirectToRoute('shelter_animal_details', ['animalId' => $animalId ]);
        }

        return $this->render('shelter/dashAnimauxDetails.html.twig', ['form' => $form, 'association' => $association, 'animal' => $animal, 'demandes' => $demandes, 'tags' => $tags]);
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
        $requested = $association->getPensionnaires();
        
        $requestedAnimals = $requested->filter(
            function(Animal $animals) {
                return $animals->getStatut() === "En refuge";
            });

        if (!$association | !$requestedAnimals) {
            throw $this->createNotFoundException(
                'No shelter found for id '.$id
            );
        }
        
        return $this->render('shelter/dashDemandes.html.twig', ['association' => $association, 'requestedAnimals' => $requestedAnimals ]);
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

        $sheltered = $entityManager->getRepository(Animal::class)->findBy(['association' => $id]);

        if (!$sheltered) {
            $entityManager->remove($shelter);
            $entityManager->remove($user);
            $entityManager->flush();
            
            return $this->redirectToRoute('accueil');
        }

        $this->addFlash('notice',
            'Vous accueillez actuellement un ou plusieurs animaux enregistrés sur notre site.
            Merci de contacter un administrateur afin de supprimer votre compte !'
        );
        return $this->redirectToRoute('shelter_dashboard');
    }

    #[Route('/association/profil/demandes/{requestId}/deny', name: 'shelter_deny_request', methods: ['POST'], requirements: ['page' => '\d+'])]
    public function denyRequest(EntityManagerInterface $entityManager, int $requestId): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $request = $entityManager->getRepository(Demande::class)->find($requestId);

        $request->setStatut_demande("Refusée");
        $entityManager->persist($request);
        $entityManager->flush();
        
        return $this->redirectToRoute('shelter_request_details', array('requestId' => $requestId));
    }

    #[Route('/association/profil/demandes/{requestId}/accept', name: 'shelter_accept_request', methods: ['POST'], requirements: ['page' => '\d+'])]
    public function acceptRequest(EntityManagerInterface $entityManager, int $requestId): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $request = $entityManager->getRepository(Demande::class)->find($requestId);

        $request->setStatut_demande("Acceptée");
        $entityManager->persist($request);
        $entityManager->flush();
        
        return $this->redirectToRoute('shelter_request_details', array('requestId' => $requestId));
    }
}