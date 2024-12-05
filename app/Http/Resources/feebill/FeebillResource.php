<?php

namespace App\Http\Resources\feebill;
use Illuminate\Http\Resources\Json\JsonResource;

class FeebillResource extends JsonResource
{
	public function toArray($request)
    {   

	
      return [
	   'id' => $this->id,
        'cgst' => $this->cgst,
        'sgst' => $this->sgst,
        'igst' =>$this->igst,
        'level1' => $this->level1,
        'level2' =>$this->level2,
        'level3' => $this->level3,
        'level4' =>$this->level4,
        'level5' => $this->level5,
        'level5_percent' =>$this->level5_percent,
        'spot_survey_fee' =>$this->spot_survey_fee,
        'reinspection_fee' => $this->reinspection_fee,
        'verification_fee' => $this->verification_fee,
        'conveyance_a' => $this->conveyance_a,
        'conveyance_b' => $this->conveyance_b,
        'conveyance_c' => $this->conveyance_c,
        'city_category' => $this->city_category,
        'created_by' =>$this->created_by,
        'updated_by' =>$this->updated_by,
        'client_id' =>$this->client_id,
         'created_at' => ($this->created_at)?$this->created_at->format('Y-m-d H:i:s'):"",
         'updated_at' => ($this->updated_at)?$this->updated_at->format('Y-m-d H:i:s'):"",
        ];

	}
}


?>