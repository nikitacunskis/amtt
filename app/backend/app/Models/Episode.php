<?php

namespace App\Models;

class Episode
{
    public int $id;
    public string $name;
    public string $air_date;
    public string $episode;
    public array $characters; 
    public string $url;
    public string $created;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->air_date = $data['air_date'];
        $this->episode = $data['episode'];
        $this->characters = $data['characters'];
        $this->url = $data['url'];
        $this->created = $data['created'];
    }

    public static function fromApi(array $data): self
    {
        return new self([
            'id' => $data['id'] ?? 0,
            'name' => $data['name'] ?? '',
            'air_date' => $data['air_date'] ?? '',
            'episode' => $data['episode'] ?? '',
            'characters' => $data['characters'] ?? [],
            'url' => $data['url'] ?? '',
            'created' => $data['created'] ?? '',
        ]);
    }
}
