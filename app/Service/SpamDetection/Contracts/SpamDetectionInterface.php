<?php

namespace App\Service\SpamDetection\Contracts;


interface SpamDetectionInterface
{
    public function detect(string $body);
}