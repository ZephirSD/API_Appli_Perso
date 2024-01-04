<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Repository\AnimalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/animal')]
class AnimalController extends AbstractController
{
    #[Route('/', name: 'app_animal_index', methods: ['GET'])]
    public function index(AnimalRepository $animalRepository): Response
    {
        $animals = $animalRepository->findAll();
        return $this->json($animals);
    }

    #[Route('/new', name: 'app_animal_new', methods: ['POST'])]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $animal = new Animal();
        $entityManager->persist($animal);
        $entityManager->flush();
        return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_animal_show', methods: ['GET'])]
    public function show(Animal $animal): Response
    {
        return $this->json($animal);
    }

    #[Route('/{id}/edit', name: 'app_animal_edit', methods: ['PUT'])]
    public function edit(EntityManagerInterface $entityManager): Response
    {
            $entityManager->flush();
            return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_animal_delete', methods: ['DELETE'])]
    public function delete(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$animal->getId(), $request->request->get('_token'))) {
            $entityManager->remove($animal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_animal_index', [], Response::HTTP_SEE_OTHER);
    }
}
