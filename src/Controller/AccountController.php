<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function account(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('account/account.html.twig', [
            'user' => $this->getUser(),
        ]);
    }


    #[Route('/account/delete', name: 'app_account_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            throw new AccessDeniedException();
        }

        // CSRF token check
        if (!$this->isCsrfTokenValid('delete-account', $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('app_account');
        }

        // Optional: verify password before deleting (uncomment if you add a password field to the form)
        $plainPassword = $request->request->get('password');
        if ($plainPassword) {
            if (!$passwordHasher->isPasswordValid($user, $plainPassword)) {
                $this->addFlash('error', 'Incorrect password.');
                return $this->redirectToRoute('app_account');
            }
        }

        // remove user
        $em->remove($user);
        $em->flush();

        // invalidate session / logout
        $request->getSession()->invalidate();

        // option: redirect to homepage
        return $this->redirectToRoute('app_login');
    }
}
