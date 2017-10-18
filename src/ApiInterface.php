<?php

namespace DocFalcon;

use Psr\Http\Message\ResponseInterface;

interface ApiInterface
{
    /**
     * @param array $document Document structure for pdf generation
     * @param string $path Path or resource where the pdf will be saved
     * @return ResponseInterface
     * @throws Error
     */
    public function generate($document, $path);
}