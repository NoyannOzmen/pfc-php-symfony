<?php
namespace App\Controller;

use App\Entity\Famille;
use App\Entity\Demande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FosterController extends AbstractController
{

    #[Route('/famille/profil', name: 'foster_profile', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $accueillant = $user->getAccueillant();
        $id = $accueillant->getId();

        $famille = $entityManager->getRepository(Famille::class)->find($id);

        if (!$famille) {
            throw $this->createNotFoundException(
                'No famille found for id '.$id
            );
        }
        
        return $this->render('foster/profilInfos.html.twig', ['famille' => $famille]);
    }

    #[Route('/famille/profil/demandes', name: 'foster_profile_requests', methods: ['GET'])]
    public function displayRequests(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $accueillant = $user->getAccueillant();
        $id = $accueillant->getId();

        $famille = $entityManager->getRepository(Famille::class)->find($id);

        $requests = $entityManager->getRepository(Demande::class)->findBy(['famille' => $id]);

        if (!$famille | !$requests) {
            throw $this->createNotFoundException(
                'No famille found for id '.$id
            );
        }
        
        return $this->render('foster/profilDemande.html.twig', ['famille' => $famille, 'requests' => $requests]);
    }

}