<?php

namespace App\Controller\Api\V1;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Form\SearchProjectType;
use App\Repository\CommentRepository;
use App\Repository\ProjectRepository;
use App\Service\FileUploader;
use App\Service\HarmonizeSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
* @Route("/api/v1/projects", name="api_v1_projects_")
*/
class ProjectController extends AbstractController
{
    /**
     * @Route ("", name="browse", methods={"GET"})
     */
    public function browse(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->findAll();
        
        return $this->json($projects, 200, [], [
            'groups' => ['project_browse'],
        ]);
    }

    /**
     * @Route ("/search", name="search_projects", methods={"GET"})
     */
    public function search(ProjectRepository $projectRepository, Request $request): Response
    {
        
        // on recuperes tous les projets
        $projects = $projectRepository->findAll();
        // dd($projects);

        // on cree/appelle le formulaire
        $form = $this->createForm(SearchProjectType::class, null, [
            'csrf_protection' => false,
        ]);
        // dd($form);

        // on recuperes le contenu de la requete
        $json = json_decode($request->getContent(), true);
        // dd($json);
        
        //  on asssocie le contenu de la requete au formulaire
        $search = $form->submit($json);
        // dd($form);
            dd($search);
        // si le form est valid on appelle notre custom query search et on lui transmets les donnees recuperees dans la requete

            $projects = $projectRepository->search($search->get('keywords')->getData());
        
        return $this->json($projects, 200, [], [
            'groups' => ['project_browse'],
        ]);
    }

    
    /**
    * @Route("/{id}", name="read", methods={"GET"}, requirements={"id": "\d+"})
    */
    public function read(Project $project): Response
    {
        return $this->json($project, 200, [], [
            'groups' => ['project_read'],
        ]);
    }

    /**
     * @Route ("", name="add", methods={"POST"})
     * 
     */
    public function add(Request $request, HarmonizeSlugger $slugger): Response 
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project, [
            'csrf_protection' => false,
        ]);

        $json = json_decode($request->getContent(), true);

        $form->submit($json);

            if ($form->isValid()) {
            $project->setSlug($slugger->slugify($project->getName()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->json($project, 201, [
                'id_project' => $project->getId(),
            ], 
            [
            'groups' => ['project_read'],
            ]);
        }

        return $this->json($form->getErrors(true, false)->__toString(), 400);  
    }

    /**
     * @Route("/{id}", name="edit", methods={"PUT", "PATCH"}, requirements={"id": "\d+"})
     */
    public function edit(Project $project, Request $request): Response
    {
        $form = $this->createForm(ProjectType::class, $project, [
            'csrf_protection' => false,
        ]);

        $json = json_decode($request->getCOntent(), true);
        $form->submit($json);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->json($project, 200, [], [
                'groups' => ['project_read'],
            ]);
        }
        return $this->json($form->getErrors(true, false)->__toString(), 400);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id": "\d+"})
     */
    public function delete(Project $project): Response
    {
        $em =$this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        return $this->json(null, 204);
    }

    /**
     * @Route ("/{type}/{sort}", name="browse_order_by", methods={"GET"}, requirements={"type": "\D+", "sort": "\D+"})
     */
    public function browseOrderBy(ProjectRepository $projectRepository, string $type, string $sort): Response
    {
        $projects = $projectRepository->orderBy($type, $sort);
        return $this->json($projects, 200, [], [
            'groups' => ['project_browse'],
        ]);
    }
    /**
     * @Route ("/hottest", name="browse_hottest", methods={"GET"})
     */
    public function browseHottest(CommentRepository $commentRepository, ProjectRepository $projectRepository) : Response
    {
        $orderedProjects = $commentRepository->orderByHottest();

        foreach ($orderedProjects as $key => $project) {
            $orderedProjectsId[$key] = $project['project_id'];
        }

        $projectObject = $projectRepository->findBy(
            ['id' => $orderedProjectsId]
        );

        return $this->json($projectObject, 200, [], [
            'groups' => ['project_browse'],
        ]);
    }

    /**
     * @Route ("/music_genre/{id}", name="browse_music_genre", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function browseByMusicGenre(ProjectRepository $projectRepository, int $id): Response
    {
        $projects = $projectRepository->orderByGenre($id);
        
        return $this->json($projects, 200, [], [
            'groups' => ['project_browse'],
        ]);
    }

    
    /**
     * @Route ("/upload/{id}", name="upload", methods={"POST"}, requirements={"id": "\d+"})
     */
    public function upload(FileUploader $file, Request $request, ProjectRepository $projectRepository, int $id, ValidatorInterface $validator) {
        
        // On cherche le projet par son id.
        $project = $projectRepository->find($id);
        
        // On recupére les objet files contenue dans la requête
        $picture = $request->files->get('picture');
        $audio = $request->files->get('audio_url');

        if ($picture !== null || $audio !== null) {

            $violationsAudio = $validator->validate(
                $audio,
                    new File([
                        'mimeTypes' => [
                            'audio/*',
                        ],
                    ]),     
            );

            $violationsImage = $validator->validate(
                $picture,
                    new File([
                        'mimeTypes' => [
                            'image/*',
                        ],
                    ]),     
            );

            if ($violationsAudio->count() > 0 || $violationsImage->count() > 0) {
                return $this->json("Mauvais format", 400);
            } else {
             
                $newImageName = $file->uploadImageProject($picture);
                $project->setPicture($newImageName);

                $newAudioName = $file->uploadAudioProject($audio);
                $project->setAudioUrl($newAudioName);

                $em = $this->getDoctrine()->getManager();
                $em->persist($project);
                $em->flush(); 
        
                return $this->json($project, 201, [], [
                'groups' => ['project_read']
                ]);
            }
        }

        return $this->json("Erreur", 400);
        
    }
        
}
