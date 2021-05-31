<?php

namespace App\Controller\Admin;

use App\Entity\MusicGenre;
use App\Form\MusicGenreType;
use App\Repository\MusicGenreRepository;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/music", name="music_")
*/
class MusicGenreController extends AbstractController
{
    /**
     * @Route("/browse", name="browse")
     */
    public function browse(MusicGenreRepository $musicGenreRepository): Response
    {
        return $this->render('admin/music_genre/browse.html.twig', [
            'musicGenre' => $musicGenreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add (Request $request): Response
    {
        $music = new MusicGenre();

        $form = $this->createForm(MusicGenreType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($music);
            $em->flush();

            $this->addFlash('succes', 'Le Genre ' . $music->getName() . ' a bien été créer');
            return $this->redirectToRoute('music_browse');
        }

        return $this->render('admin/music_genre/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * 
     * @IsGranted("ROLE_SUPER_ADMIN", statusCode=403, message="Unauthorized Access")
     */
    public function delete(MusicGenre $musicGenre, ProjectRepository $projectRepository, MusicGenreRepository $musicGenreRepository): Response
    {
        $setMusicGenre = $musicGenreRepository->findAll();
        
        $musicGenreId = $musicGenre->getId();
        $projects = $projectRepository->findProjectsByMusicGenre($musicGenreId);
        
        foreach($projects as $project) {
            $project->setMusicGenre($setMusicGenre[0]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($musicGenre);
        $entityManager->flush();

        $this->addFlash('succes', 'Le Genre ' . $musicGenre->getName() . ' a bien été supprimé');
            
        return $this->redirectToRoute('music_browse');
    }
}
