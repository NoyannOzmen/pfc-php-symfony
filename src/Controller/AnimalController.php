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
use Doctrine\ORM\Query\ResultSetMapping;

class AnimalController extends AbstractController
{
    #[Route('/animaux', name: 'animals')]
    public function displayAll(Request $request, EntityManagerInterface $entityManager): Response
    {
        $animals = $entityManager->getRepository(Animal::class)->findBy(['statut' => "En refuge"]);
        $tags = $entityManager->getRepository(Tag::class)->findAll();
        $especes = $entityManager->getRepository(Espece::class)->findAll();

        if (!$animals | !$tags | !$especes ) {
            throw $this->createNotFoundException(
                'We\'re missing something'
            );
        }

        if ($request->isMethod('POST')) {
                $speciesSmall = $request->request->get('_especeDropdownSmall');
                $speciesFull = $request->request->get('_especeDropdownFull');
                $sexe = $request->request->get('_sexe');
                $minAge = $request->request->get('_minAge');
                $maxAge = $request->request->get('_maxAge');
                $statut = "En refuge";

                $query = "SELECT * FROM Animal";
                $conditions = array();

                $conditions[] = "statut='$statut'";

                if(! empty($speciesSmall)) {
                $conditions[] = "espece_id=$speciesSmall";
                }
                if(! empty($speciesFull)) {
                $conditions[] = "espece_id=$speciesFull";
                }
                if(! empty($sexe)) {
                $conditions[] = "sexe='$sexe'";
                }
                if(! empty($minAge)) {
                $conditions[] = "age>$minAge";
                }
                if(! empty($maxAge)) {
                $conditions[] = "age<$maxAge";
                }

                $sql = $query;
                if (count($conditions) > 0) {
                $sql .= " WHERE " . implode(' AND ', $conditions);
                }

                $rsm = new ResultSetMapping();
                $rsm->addEntityResult(Animal::class, 'a');
                $rsm->addFieldResult('a', 'id', 'id');
                $rsm->addFieldResult('a', 'nom', 'nom');
                $rsm->addFieldResult('a', 'sexe', 'sexe');
                $rsm->addFieldResult('a', 'age', 'age');
                $rsm->addFieldResult('a', 'race', 'race');
                $rsm->addFieldResult('a', 'couleur', 'couleur');
                $rsm->addFieldResult('a', 'description', 'description');
                $rsm->addJoinedEntityResult(Espece::class , 'e', 'a', 'espece');
                $rsm->addFieldResult('e', 'espece_id', 'id');

                $query = $entityManager->createNativeQuery($sql, $rsm);
                dump($query);

                $searchedAnimals = $query->getResult();
                dump($searchedAnimals);

                $animals = $searchedAnimals;

                return $this->render('animaux/animalList.html.twig', ['animals' => $animals, 'tags' => $tags, 'especes' => $especes]);
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