<?php

namespace App\Http\Controllers\Api\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class ChapterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
   
        return [
            'id' => $this->id,
            'chapter_name' => $this->chapter_name,
            'chapter_icon' =>  url('uploads/subjects/'.$this->chapter_icon),
            'file' => $this->file,
            'topic' => $this->topic,
            //'video_link' =>$this->video_link
        ];
    }

}