<?php

namespace App\Controller\api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/**
 * Renvoie les informations de la personne authentifiée.
 *
 * Avec le firewall "api" (jwt: ~), Symfony lit le header "Authorization: Bearer <token>",
 * vérifie la signature et l'expiration du token, puis recharge l'utilisateur
 * depuis la base grâce au claim "username" (ici l'email, cf. app_user_provider).
 */
final class MeController extends AbstractController
{
    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    public function me(#[CurrentUser] ?User $user): JsonResponse
    {
        // Filet de sécurité : normalement, l'access_control (IS_AUTHENTICATED_FULLY)
        // renvoie déjà un 401 avant d'arriver ici si le token est absent ou invalide.
        if (!$user) {
            return $this->json(['message' => 'Authentification requise.'], 401);
        }

        // On choisit explicitement les champs exposés : ne jamais renvoyer l'entité
        // brute, qui contiendrait notamment le mot de passe hashé.
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
        ]);
    }
}
