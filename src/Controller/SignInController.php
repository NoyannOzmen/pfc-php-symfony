<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SignInController extends AbstractController
{

  #[Route('/connexion', name: 'login', methods: ['GET'])]
    public function displayLogin(): Response
    {
        return $this->render('signIn/connexion.html.twig');
    }

  #[Route('/famille/inscription', name: 'foster_signup', methods: ['GET'])]
  public function displayFosterSignUp(): Response
  {
      return $this->render('signIn/inscriptionFam.html.twig');
  }

  #[Route('/association/inscription', name: 'shelter_signup', methods: ['GET'])]
  public function displayShelterSignUp(): Response
  {
      return $this->render('signIn/inscriptionAsso.html.twig');
  }

}