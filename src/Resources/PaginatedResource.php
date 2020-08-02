<?php

namespace Si6\Base\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class PaginatedResource extends JsonResource
{
    public function toArray($request)
    {
        $paginated = $this->resource->toArray();

        return [
            'data'  => $this->resource->getCollection(),
            'meta'  => $this->meta($paginated),
        ];
    }

    /**
     * Gather the meta data for the response.
     *
     * @param array $paginated
     * @return array
     */
    protected function meta($paginated)
    {
        return Arr::except(
            $paginated,
            [
                'data',
                'path',
                'first_page_url',
                'last_page_url',
                'prev_page_url',
                'next_page_url',
            ]
        );
    }
}
