<?php
namespace App\Controller;

use App\Entity\Famille;
use App\Entity\Demande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FosterController extends AbstractController
{

    #[Route('/famille/profil', name: 'foster_profile', methods: ['GET'])]
    public function displayProfile(EntityManagerInterface $entityManager): Response
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

    #[Route('/famille/profil/update', name: 'foster_profile_update', methods: ['POST'])]
    public function fosterUpdate(EntityManagerInterface $entityManager, Request $request): Response
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

        $nom = $request->request->get('_nom');
        $prenom = $request->request->get('_prenom');
        $hebergement = $request->request->get('_hebergement');
        $terrain = $request->request->get('_terrain');
        $rue = $request->request->get('_rue');
        $commune = $request->request->get('_commune');
        $code_postal = $request->request->get('_code_postal');

        if (isset($nom)) {$famille->setNom($nom);};
        if (isset($prenom)) {$famille->setPrenom($prenom);};
        if (isset($hebergement)) {$famille->setHebergement($hebergement);};
        if (isset($terrain)) {$famille->setTerrain($terrain);};
        if (isset($rue)) {$famille->setRue($rue);};
        if (isset($commune)) {$famille->setCommune($commune);};
        if (isset($$code_postal)) {$famille->setCode_postal($code_postal);};

        $entityManager->flush();
        
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
        if (!$requests) {
            $requests = [];
        }

        if (!$famille) {
            throw $this->createNotFoundException(
                'No famille found for id '.$id
            );
        }
        
        return $this->render('foster/profilDemande.html.twig', ['famille' => $famille, 'requests' => $requests]);
    }

    #[Route('/famille/profil/delete', name: 'foster_delete_account', methods: ['GET'])]
    public function deleteAccount(EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
  
        /** @var \App\Entity\Utilisateur $user */
        $user = $this->getUser();
        /** @var \App\Entity\Utilisateur $user */
        $accueillant = $user->getAccueillant();
        $id = $accueillant->getId();

        $famille = $entityManager->getRepository(Famille::class)->find($id);
        
/*         $requests = $entityManager->getRepository(Demande::class)->findBy(['famille' => $id]);
        if (!$requests) {
            $requests = [];
        } */

        $entityManager->remove($famille);
        $entityManager->remove($user);
        $entityManager->flush();
        
        return $this->render('staticPages/accueil.html.twig');
    }
}