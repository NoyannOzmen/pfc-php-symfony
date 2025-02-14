<?php
namespace App\Controller;

use App\Entity\Animal;
use App\Entity\Association;
use App\Entity\Espece;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AssociationController extends AbstractController
{
    #[Route('/associations', name: 'associations', methods: ['GET'])]
    public function displayAll(EntityManagerInterface $entityManager): Response
    {
        $associations = $entityManager->getRepository(Association::class)->findAll();
        $especes = $entityManager->getRepository(Espece::class)->findAll();

        if (!$associations | !$especes ) {
            throw $this->createNotFoundException(
                'We\'re missing something'
            );
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