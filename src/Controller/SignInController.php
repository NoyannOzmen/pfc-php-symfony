<?php
namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SignInController extends AbstractController
{
/*   public function index(UserPasswordHasherInterface $passwordHasher): Response
  {
      // ... e.g. get the user data from a registration form
      $user = new Utilisateur();
      $plaintextPassword = ...;

      // hash the password (based on the security.yaml config for the $user class)
      $hashedPassword = $passwordHasher->hashPassword(
          $user,
          $plaintextPassword
      );
      $user->setPassword($hashedPassword);
  } */

  public function index(): Response
  {
      // usually you'll want to make sure the user is authenticated first,
      // see "Authorization" below
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

  #[Route('/famille/inscription', name: 'foster_signup', methods: ['GET'])]
  public function newFoster(): Response
  {
      return $this->render('signIn/inscriptionFam.html.twig');
  }

  #[Route('/association/inscription', name: 'shelter_signup', methods: ['GET'])]
  public function newShelter(): Response
  {
      return $this->render('signIn/inscriptionAsso.html.twig');
  }

}