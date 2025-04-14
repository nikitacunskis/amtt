<?php

namespace App\Interfaces;

interface RickAndMortyClientInterface
{
    public function list(int $page = 1): array;

    public function showCharacter(string $id): array;
    public function showLocation(string $id): array;
    public function showEpisode(string $id): array;
    public function search(array $filters): array;
}