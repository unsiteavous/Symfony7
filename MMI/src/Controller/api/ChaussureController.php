<?php

namespace App\Controller\api;

use App\Entity\Chaussure;
use App\Repository\ChaussureRepository;
use App\Repository\TailleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/chaussure', name: 'api_chaussure_')]
final class ChaussureController extends AbstractController
{
    #[Route('s/', name: 'index')]
    public function index(ChaussureRepository $chaussureRepository): Response
    {
        $chaussures = $chaussureRepository->findAll();
        return $this->json($chaussures, 200, [], ['groups' => ['chaussure:list']]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, TailleRepository $tailleRepository, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        $chaussure = $serializer->deserialize($request->getContent(), Chaussure::class, 'json', [
            "groups" => "api_chaussure_create",
        ]);
        $chaussure->setName(htmlspecialchars($data['name']) ?? null);

        // On récupère les tailles existantes en base plutôt que de laisser
        // le serializer en recréer de nouvelles instances détachées.
        foreach ($data['taille'] ?? [] as $tailleId) {
            $taille = $tailleRepository->find($tailleId);
            if ($taille) {
                $chaussure->addTaille($taille);
            }
        }

        $errors = $validator->validate($chaussure);

        // S'il y a des erreurs, on s'arrête là et on retourne les erreurs à l'envoyeur :
        if ($errors->count()) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }
            return $this->json($messages, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {

            // Sinon on enregistre en base de données :
            $em->persist($chaussure);
            $em->flush();
            return $this->json(null, Response::HTTP_CREATED);
        }
    }
}
