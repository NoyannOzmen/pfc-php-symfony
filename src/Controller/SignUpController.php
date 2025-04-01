<?php
namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Famille;
use App\Entity\Association;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SignUpController extends AbstractController
{
  #[Route('/inscription', name: 'app_signup')]
  public function SignupChoice()
  {   
    return $this->render('signIn/inscription.html.twig');
  }

  #[Route('/famille/inscription', name: 'app_foster_signup')]
  public function fosterSignup(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Request $request): Response
  {
    if ($request->isMethod('POST')) {
        $email = $request->request->get('_email');
        $user = $entityManager->getRepository(Utilisateur::class)->findBy(['email' => $email]);

        if ($user) {
            throw $this->createNotFoundException(
                'Invalid credentials'
            );
        }

        $newUser = new Utilisateur();
        $newUser->setEmail($email);
        $newUser->setRoles(["ROLE_FOSTER"]);
        $plaintextPassword = $request->request->get('_password');
        $plaintextConfirm = $request->request->get('_confirmation');

        if ($plaintextPassword !== $plaintextConfirm) {
            throw $this->createNotFoundException(
                'Password and confirmation must match'
            );
        };

        $hashedPassword = $passwordHasher->hashPassword(
            $newUser,
            $plaintextPassword
        );
        $newUser->setPassword($hashedPassword);
        $entityManager->persist($newUser);

        $nom = $request->request->get('_nom');
        $prenom = $request->request->get('_prenom');
        $rue = $request->request->get('_rue');
        $commune = $request->request->get('_commune');
        $code_postal = $request->request->get('_code_postal');
        $pays = $request->request->get('_pays');
        $telephone = $request->request->get('_telephone');
        $hebergement = $request->request->get('_hebergement');
        $terrain = $request->request->get('_terrain');

        $newFoster = new Famille();
        $newFoster->setUtilisateur($newUser);
        $newFoster->setNom($nom);
        if (isset($prenom)) {$newFoster->setPrenom($prenom);};
        if (isset($terrain)) {$newFoster->setTerrain($terrain);};
        $newFoster->setHebergement($hebergement);
        $newFoster->setRue($rue);
        $newFoster->setCommune($commune);
        $newFoster->setCode_postal($code_postal);
        $newFoster->setPays($pays);
        $newFoster->setTelephone($telephone);
        $entityManager->persist($newFoster);

        $newUser->setAccueillant($newFoster);
        $entityManager->persist($newUser);

        $entityManager->flush();
        $this->addFlash('notice', "Création de compte réussie, bienvenue ! Merci de vous connecter à present");
    }

    return $this->render('signIn/inscriptionFam.html.twig');
  }

  #[Route('/association/inscription', name: 'app_shelter_signup')]
  public function shelterSignup(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, Request $request): Response
  {
    if ($request->isMethod('POST')) {
        $plaintextPassword = $request->request->get('_password');
        $plaintextConfirm = $request->request->get('_confirmation');

        if ($plaintextPassword !== $plaintextConfirm) {
            throw $this->createNotFoundException(
                'Password and confirmation must match'
            );
        }
        
        $nom = $request->request->get('_nom');
        $responsable = $request->request->get('_responsable');
        $rue = $request->request->get('_rue');
        $commune = $request->request->get('_commune');
        $code_postal = $request->request->get('_code_postal');
        $pays = $request->request->get('_pays');
        $telephone = $request->request->get('_telephone');
        $siret = $request->request->get('_siret');
        $site = $request->request->get('_site');
        $description = $request->request->get('_description');
        $email = $request->request->get('_email');

        $user = $entityManager->getRepository(Utilisateur::class)->findBy(['email' => $email]);

        if ($user) {
            throw $this->createNotFoundException(
                'Invalid credentials'
            );
        } else {
            $newUser = new Utilisateur();
            $newUser->setEmail($email);
            $newUser->setRoles(["ROLE_SHELTER"]);
            $hashedPassword = $passwordHasher->hashPassword(
                $newUser,
                $plaintextPassword
            );
            $newUser->setPassword($hashedPassword);
            $entityManager->persist($newUser);

            $newShelter = new Association();
            $newShelter->setUtilisateur($newUser);
            $newShelter->setNom($nom);
            $newShelter->setResponsable($responsable);
            $newShelter->setRue($rue);
            $newShelter->setCommune($commune);
            $newShelter->setCode_postal($code_postal);
            $newShelter->setPays($pays);
            $newShelter->setTelephone($telephone);
            $newShelter->setSiret($siret);
            if (isset($site)) {$newShelter->setSite($site);};
            if (isset($description)) {$newShelter->setDescription($description);};
            $entityManager->persist($newShelter);

            $entityManager->flush();
            $this->addFlash('notice', "Création de compte réussie, bienvenue ! Merci de vous connecter à present");
        }
    }

    return $this->render('signIn/inscriptionAsso.html.twig');
  }

}