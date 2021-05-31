<?php

namespace App\Controller\Api\V1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadController extends AbstractController
{
    /**
     * @Route("/api/v1/download/project/{filename}", name="download_project")
     * 
     * @return BinaryFileResponse
     */
    public function downloadAudioProject($filename)
    {
        $path = $this->getParameter('kernel.project_dir'). "/public/audio/projects/" . $filename . '.mp3';

        $response = new BinaryFileResponse($path);

        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,'audio.mp3');

        return $response;
    }

    /**
     * @Route("/api/v1/download/comment/{filename}", name="download_comment")
     * 
     * @return BinaryFileResponse
     */
    public function downloadAudioCommment($filename)
    {
        $path = $this->getParameter('kernel.project_dir'). "/public/audio/" . $filename . '.mp3';

        $response = new BinaryFileResponse($path);

        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,'audio.mp3');

        return $response;
    }
}