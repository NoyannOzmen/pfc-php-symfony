<?php
namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Association;
use App\Entity\Espece;
use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\Query\ResultSetMapping;

class AssociationController extends AbstractController
{
    #[Route('/associations', name: 'associations')]
    public function displayAll(Request $request, EntityManagerInterface $entityManager): Response
    {
        $associations = $entityManager->getRepository(Association::class)->findAll();
        /* $associations = array(); */
        $especes = $entityManager->getRepository(Espece::class)->findAll();

        if ( !$associations | !$especes ) {
            throw $this->createNotFoundException(
                'We\'re missing something'
            );
        }

        if ($request->isMethod('POST')) {
            $dptSmall = $request->request->get('_dptSelectSmall');
            $dptFull = $request->request->get('_dptSelectFull');
            $species = $request->request->get('_espece');
            $name = $request->request->get('_shelterNom');

            $query = "SELECT * FROM Association";
            $conditions = array();

            if(! empty($dptSmall)) {
            $conditions[] = "code_postal LIKE '$dptSmall%'";
            }
            if(! empty($dptFull)) {
            $conditions[] = "code_postal LIKE '$dptFull%'";
            }
/*             if(! empty($species)) {
            $conditions[] = "sexe='$species'";
            } */
            if(! empty($name)) {
            $conditions[] = "nom LIKE '$name'";
            }

            $sql = $query;
            if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
            }

            $rsm = new ResultSetMapping();
            $rsm->addEntityResult(Association::class, 'a');
            $rsm->addFieldResult('a', 'id', 'id');
            $rsm->addFieldResult('a', 'nom', 'nom');
            $rsm->addFieldResult('a', 'code_postal', 'code_postal');
            $rsm->addJoinedEntityResult(Media::class , 'm', 'a', 'images_association');
            $rsm->addFieldResult('m', 'images_association.id', 'id');
            $rsm->addFieldResult('m', 'images_association.url', 'url');

            $query = $entityManager->createNativeQuery($sql, $rsm);

            $searchedShelters = $query->getResult();

            $associations = $searchedShelters;

            /* return $this->render('association/associationList.html.twig', ['associations' => $associations, 'especes' => $especes]); */
    }
        
        return $this->render('association/associationList.html.twig', ['associations' => $associations, 'especes' => $especes]);
    }

    #[Route('/associations/{id}', name: 'shelter_details', requirements: ['page' => '\d+'])]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $association = $entityManager->getRepository(Association::class)->find($id);
        
        $animals = $association->getPensionnaires()->filter(function(Animal $animals) {
            return $animals->getStatut() === "En refuge";
        });

        if (!$association) {
            throw $this->createNotFoundException(
                'No Shelter found for id '.$id
            );
        }
        
        return $this->render('association/associationDetails.html.twig', ['association' => $association, 'animals' => $animals]);
    }
}