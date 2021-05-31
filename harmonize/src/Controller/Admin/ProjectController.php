<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Form\Admin\ProjectType;
use App\Repository\ProjectRepository;
use App\Service\FileUploader;
use App\Service\HarmonizeSlugger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/projects", name="projects_")
     */
class ProjectController extends AbstractController
{
    /**
     * @Route("/browse", name="browse")
     */
    public function browse(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAll();

        return $this->render('admin/project/browse.html.twig', [
            'projects' => $projects,
        ]);
    }

    /**
     * @Route("/read/{id}", name="read", requirements={"id" : "\d+"})
     */
    public function read(Project $project): Response
    {
        return $this->render('admin/project/read.html.twig', [
            'project' => $project,
        ]);
    }

    /**
    * @Route("/add", name="add")
    *
    * @IsGranted("ROLE_SUPER_ADMIN", statusCode=403, message="Unauthorized Access")
    */
    public function add(Request $request, HarmonizeSlugger $slugger, FileUploader $file): Response
    {
        $project = new Project();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setSlug($slugger->slugify($project->getName()));
            
            $pictureFile = $form->get('picture')->getData();
            $audioFile = $form->get('audio_url')->getData();

            if ($pictureFile) {
                $newFilename = $file->uploadImageProject($pictureFile);
                $project->setPicture($newFilename);
            }

            if($audioFile) {
                $newFilename = $file->uploadAudioProject($audioFile);
                $project->setAudioUrl($newFilename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this->addFlash('succes', 'Le project ' . $project->getName() . ' a bien été créé');

            return $this->redirectToRoute('projects_browse');
        }
        
        return $this->render('admin/project/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" : "\d+"})
     * 
     * @IsGranted("ROLE_SUPER_ADMIN", statusCode=403, message="Unauthorized Access")
     */
    public function edit(Project $project, Request $request, HarmonizeSlugger $slugger, FileUploader $file): Response
    {
        $originalPicture = $project->getPicture();
        $originalAudio = $project->getAudioUrl();

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $project->setSlug($slugger->slugify($project->getName()));

            $pictureFile = $form->get('picture')->getData();
            $audioFile = $form->get('audio_url')->getData();

            if($pictureFile == null) {
                $project->setPicture($originalPicture);
            } else {
                $newFilename = $file->uploadImageProject($pictureFile);
                $project->setPicture($newFilename);
            }

            if($audioFile == null) {
                $project->setAudioUrl($originalAudio);
            } else {
                $newFilename = $file->uploadAudioProject($audioFile);
                $project->setAudioUrl($newFilename);
            }
            

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('succes', 'Le projet ' . $project->getName() . ' a bien été modifié');

            return $this->redirectToRoute('projects_read', ['id' => $project->getId()]);
        }

        return $this->render('admin/project/edit.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id" : "\d+"}, methods={"DELETE"})
     * 
     * @IsGranted("ROLE_SUPER_ADMIN", statusCode=403, message="Unauthorized Access")
     */
    public function delete(Project $project): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        $this->addFlash('succes', 'Le project ' . $project->getName() . ' a bien été supprimé');

        return $this->redirectToRoute('projects_browse');
    }
}