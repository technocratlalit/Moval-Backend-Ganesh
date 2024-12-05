<?php

namespace App\Http\Resources\job;

use Illuminate\Http\Resources\Json\JsonResource;

class JobDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'vehicle_class' => $this->vehicle_class,
            'registration_date' => $this->registration_date,
            'type_of_body' => $this->type_of_body,
            'manufactoring_year' => $this->manufactoring_year,
            'maker' => $this->maker,
            'model' => $this->model,
            'chassis_no' => $this->chassis_no,
            'engine_no' => $this->engine_no,
            'rc_status' => $this->rc_status,
            'seating_capacity' => $this->seating_capacity,
            'issuing_authority' => $this->issuing_authority,
            'fuel_type' => $this->fuel_type,
            'odometer_reading' => $this->odometer_reading,
            'fitness_valid_upto' => $this->fitness_valid_upto,
            'laden_weight' => $this->laden_weight,
            'unladen_weight' => $this->unladen_weight,
            'requested_value' => $this->requested_value,
            'job_id' => $this->job_id,
            'engine_transmission' => $this->engine_transmission,
            'electrical_gadgets' => $this->electrical_gadgets,
            'right_side' => $this->right_side,
            'left_body' => $this->left_body,
            'front_body' => $this->front_body,
            'back_body' => $this->back_body,
            'load_body' => $this->load_body,
            'all_glass_condition' => $this->all_glass_condition,
            'cabin_condition' => $this->cabin_condition,
            'head_lamp' => $this->head_lamp,
            'tyres_condition' => $this->tyres_condition,
            'maintenance' => $this->maintenance,
            'other_damages' => $this->other_damages,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
