<?php

namespace App\Models;

use App\Models\Episode;
use App\Models\Location;
use App\Services\RickAndMortyClient;

class Character
{

    protected RickAndMortyClient $client;
    public int $id;
    public string $name;
    public string $status;
    public string $species;
    public string $type;
    public string $gender;
    public array $origin;
    public array $location;
    public string $image;
    public array $episode;
    public string $url;
    public string $created;

    public function __construct(RickAndMortyClient $client, array $attributes = [])
    {
        $this->client = $client;
        $this->id = $attributes['id'] ?? 0;
        $this->name = $attributes['name'] ?? '';
        $this->status = $attributes['status'] ?? '';
        $this->species = $attributes['species'] ?? '';
        $this->type = $attributes['type'] ?? '';
        $this->gender = $attributes['gender'] ?? '';
        $this->origin = $attributes['origin'] ?? ['name' => '', 'url' => ''];
        $this->location = $attributes['location'] ?? ['name' => '', 'url' => ''];
        $this->image = $attributes['image'] ?? '';
        $this->episode = $attributes['episode'] ?? [];
        $this->url = $attributes['url'] ?? '';
        $this->created = $attributes['created'] ?? '';
    }

}
