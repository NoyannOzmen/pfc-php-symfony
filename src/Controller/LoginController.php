<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
  public function grantAccess(): Response
  {
      $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

      /** @var \App\Entity\Utilisateur $user */
      $user = $this->getUser();

      return new Response('Well hi there !');
  }

  #[Route('/connexion', name: 'login')]
    public function displayLogin(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('signIn/connexion.html.twig', ['last_username' => $lastUsername,'error' => $error,]);
    }
  
  #[Route('/deconnexion', name: 'logout')]
  public function logout()
  {
      throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
  }
}