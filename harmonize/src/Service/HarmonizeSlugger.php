<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class HarmonizeSlugger
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * Prend une chaine de caractères et la retourne sous forme de slug
     *
     * @param string $string
     * @return string
     */
    public function slugify(string $string): string
    {
        // return preg_replace( '/[^a-z0-9]+(?:-[a-z0-9]+)*/', '-', trim(strip_tags(strtolower($string))));

        return strtolower($this->slugger->slug($string));
    }
}
