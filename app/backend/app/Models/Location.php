<?php

namespace App\Models;

class Location
{
    public int $id;
    public string $name;
    public string $type;
    public string $dimension;
    public array $residents; // array of URLs
    public string $url;
    public string $created;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->type = $data['type'];
        $this->dimension = $data['dimension'];
        $this->residents = $data['residents'];
        $this->url = $data['url'];
        $this->created = $data['created'];
    }

    public static function fromApi(array $data): self
    {
        return new self([
            'id' => $data['id'] ?? 0,
            'name' => $data['name'] ?? '',
            'type' => $data['type'] ?? '',
            'dimension' => $data['dimension'] ?? '',
            'residents' => $data['residents'] ?? [],
            'url' => $data['url'] ?? '',
            'created' => $data['created'] ?? '',
        ]);
    }
}
