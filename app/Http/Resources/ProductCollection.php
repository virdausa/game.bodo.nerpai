<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
{
    public $wrap = '';

    public function toArray(Request $request): array
    {
        return ['products' => $this->collection];
    }
}
