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

#[Route('/admin/section')]
final class AdminSectionController extends AbstractController
{
    #[Route(name: 'app_admin_section_index', methods: ['GET'])]
    public function index(SectionRepository $sectionRepository): Response
    {
        return $this->render('admin_section/index.html.twig', [
            'sections' => $sectionRepository->findAll(),
            'title' => 'Crud Section',
            'homepage_text' => "Administration des sections par {$this->getUser()->getUsername()}",
        ]);
    }

    #[Route('/new', name: 'app_admin_section_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_section/new.html.twig', [
            'section' => $section,
            'form' => $form,
            'title' => 'Crud Section nouveau',
            'homepage_text' => "Création d'une nouvelle section par {$this->getUser()->getUsername()}",
        ]);
    }

    #[Route('/{id}', name: 'app_admin_section_show', methods: ['GET'])]
    public function show(Section $section): Response
    {
        return $this->render('admin_section/show.html.twig', [
            'section' => $section,
            'title' => $section->getSectionTitle(),
            'homepage_text' => "Administration des sections par {$this->getUser()->getUsername()}",
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_section_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_section_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_section/edit.html.twig', [
            'section' => $section,
            'form' => $form,
            'title' => 'Crud Section modification',
            'homepage_text' => "Modification de la section {$section->getSectionTitle()} par {$this->getUser()->getUsername()}",
        ]);
    }

    #[Route('/{id}', name: 'app_admin_section_delete', methods: ['POST'])]
    public function delete(Request $request, Section $section, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_section_index', [], Response::HTTP_SEE_OTHER);
    }
}
