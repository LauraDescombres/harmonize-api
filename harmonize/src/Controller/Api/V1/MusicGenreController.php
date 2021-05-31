<?php

namespace App\Controller\Api\V1;

use App\Repository\MusicGenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/api/v1/music_genre", name="api_v1_music_genre")
*/
class MusicGenreController extends AbstractController
{
    /**
     * @Route("", name="browse", methods={"GET"})
     */
    public function browse(MusicGenreRepository $musicGenreRepository): Response
    {
        $musics = $musicGenreRepository->findAll();
        
        return $this->json($musics, 200, [], [
            'groups' => ['genre_browse'],
        ]);
    }
}
