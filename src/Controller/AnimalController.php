<?php
namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Tag;
use App\Entity\AnimalTag;
use App\Entity\Espece;
use App\Entity\Famille;
use App\Entity\Demande;
use App\Entity\Media;
use App\Entity\Association;
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

        if ( !$animals | !$tags | !$especes ) {
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
                $dpt = $request->request->get('_dptSelect');
                
                //* Workaround to get all checkboxes values
                $list = $request->request->all();
                unset(
                    $list['_especeDropdownSmall'],
                    $list['_especeDropdownFull'],
                    $list['_sexe'],
                    $list['_minAge'],
                    $list['_maxAge'],
                    $list['_dptSelect']
                );
                //* Queries repository to extract ID from selected species
                $tag = $entityManager->getRepository(Tag::class)->findBy(['nom' => $list]);
                foreach($tag as $tg) {
                    $excludedTags[] = $tg->getId();
                }

                $query = "SELECT * FROM Animal";
                $conditions = array();

                $statut = "En refuge";
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
                $conditions[] = "age>=$minAge";
                }
                if(! empty($maxAge)) {
                $conditions[] = "age<=$maxAge";
                }

                //* Necessary for search to function properly
                if(! empty($dpt)) {
                $query .= " JOIN Association ON association.id=animal.association_id";
                $conditions[] = "association.code_postal LIKE '$dpt%'";
                }

                if(! empty($excludedTags)) {
                //* Prevents array to string conversion error
                $str = implode(',', $excludedTags);
                $query .= " LEFT OUTER JOIN (Animal_Tag INNER JOIN Tag AS tags ON tags.id = Animal_Tag.tag_id) ON animal.id = Animal_Tag.animal_id";
                $conditions[] = "tags.id NOT IN ($str)";
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
                $rsm->addFieldResult('e', 'espece.nom', 'nom');
                $rsm->addJoinedEntityResult(Media::class , 'm', 'a', 'images_animal');
                $rsm->addFieldResult('m', 'images_animal.id', 'id');
                $rsm->addFieldResult('m', 'images_animal.url', 'url');
                $rsm->addJoinedEntityResult(Association::class , 'r', 'a', 'association');
                $rsm->addFieldResult('r', 'association.id', 'id');
                $rsm->addFieldResult('r', 'association.code_postal', 'code_postal');

                $query = $entityManager->createNativeQuery($sql, $rsm);
                dump($query);

                $searchedAnimals = $query->getResult();
                dump($searchedAnimals);
                
                return $this->render('animaux/animalList.html.twig', ['animals' => $searchedAnimals, 'tags' => $tags, 'especes' => $especes]);
                //* Redirects to itself, hopefully keeping changes
                //* return $this->redirectToRoute($request->attributes->get('_route'), ['animals' => $searchedAnimals, 'tags' => $tags, 'especes' => $especes]); */
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