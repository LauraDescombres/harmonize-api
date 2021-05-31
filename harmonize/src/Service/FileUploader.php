<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

Class FileUploader
{
    public function rename(UploadedFile $picture)
    {
        return uniqid() . '.' . $picture->guessExtension();
    }

    public function uploadImageProject(?UploadedFile $picture)
    {
        if ($picture !== null) {
            $newFileName = $this->rename($picture);
            $picture->move($_ENV['PICTURE_PROJECT'], $newFileName);
            return $newFileName;
        }

        return null;
    }

    public function uploadImageProfile(?UploadedFile $picture)
    {
        if ($picture !== null) {
            $newFileName = $this->rename($picture);
            $picture->move($_ENV['PICTURE_PROFIL'], $newFileName);
            return $newFileName;
        }

        return null;
    }

    public function uploadAudioProject(?UploadedFile $audio)
    {
        if ($audio !== null) {
            $newFileName = $this->rename($audio);
            $audio->move($_ENV['AUDIO_PROJECT'], $newFileName);
            return $newFileName;    
        }
        return null;
    }

    public function uploadAudioComment(?UploadedFile $audio)
    {
        if ($audio !== null) {
            $newFileName = $this->rename($audio);
            $audio->move($_ENV['AUDIO_COMMENT'], $newFileName);
            return $newFileName;    
        }
        return null;
    }
}