<?php

namespace App\Service\SpamDetection;


use App\Service\SpamDetection\Contracts\SpamDetectionInterface;

class KeyHeldDown implements SpamDetectionInterface
{
    public function detect(string $body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Your reply contains spam');
        }
    }
}