<?php
namespace App\Controller;

use App\Entity\Animal;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StaticPageController extends AbstractController
{
    #[Route('/', name: 'accueil', methods: ['GET'])]
    public function homepage(EntityManagerInterface $entityManager): Response
    {
        $animals = $entityManager->getRepository(Animal::class)->findBy(['statut' => 'En refuge']);
        return $this->render('staticPages/accueil.html.twig', ['animals' => $animals]);
    }

    #[Route('/a-propos', name: 'a_propos', methods: ['GET'])]
    public function apropos(): Response
    {
        return $this->render('staticPages/aPropos.html.twig');
    }

    #[Route('/contact', name: 'contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('staticPages/contact.html.twig');
    }

    #[Route('/devenir-famille-d-accueil', name: 'devenir_famille', methods: ['GET'])]
    public function becomeFoster(): Response
    {
        return $this->render('staticPages/devenirFamille.html.twig');
    }

    #[Route('/faq', name: 'faq', methods: ['GET'])]
    public function faqs(): Response
    {
        return $this->render('staticPages/faq.html.twig');
    }

    #[Route('/infos-legales', name: 'infos_legales', methods: ['GET'])]
    public function legalInfos(): Response
    {
        return $this->render('staticPages/infosLegales.html.twig');
    }

    #[Route('/plan', name: 'plan', methods: ['GET'])]
    public function siteMap(): Response
    {
        return $this->render('staticPages/plan.html.twig');
    }

    #[Route('/rgpd', name: 'rgpd', methods: ['GET'])]
    public function gprd(): Response
    {
        return $this->render('staticPages/rgpd.html.twig');
    }

}