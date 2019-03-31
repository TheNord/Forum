<?php

namespace App\Service\SpamDetection;


class Spam
{
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    /**
     * @param string $body
     * @return bool
     * @throws \Exception
     */
    public function detect(string $body)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }

        return false;
    }
}