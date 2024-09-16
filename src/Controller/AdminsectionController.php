<?php

namespace App\Controller;

use App\Entity\Section;
use App\Form\SectionType;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/adminsection')]
final class AdminsectionController extends AbstractController
{
    #[Route(name: 'app_adminsection_index', methods: ['GET'])]
    public function index(SectionRepository $sectionRepository): Response
    {
        return $this->render('adminsection/index.html.twig', [
            'sections' => $sectionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_adminsection_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('app_adminsection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adminsection/new.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adminsection_show', methods: ['GET'])]
    public function show(Section $section): Response
    {
        return $this->render('adminsection/show.html.twig', [
            'section' => $section,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adminsection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adminsection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adminsection/edit.html.twig', [
            'section' => $section,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adminsection_delete', methods: ['POST'])]
    public function delete(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adminsection_index', [], Response::HTTP_SEE_OTHER);
    }
}
