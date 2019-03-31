<?php

namespace App\Service\SpamDetection;


use App\Service\SpamDetection\Contracts\SpamDetectionInterface;

class InvalidKeywords implements SpamDetectionInterface
{
    protected $keywords = [
        'yahoo customer support'
    ];

    public function detect(string $body)
    {
        foreach ($this->keywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new \Exception('Your reply contains spam');
            }
        }
    }
}