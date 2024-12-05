<?php

namespace App\Models;

use App\Http\Controllers\MotorValuation\WorksopBranchController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Inspection extends Model
{
    use HasFactory, SoftDeletes;
    public $table = "tbl_ms_inspection_details";

    protected $casts = [
        'date_of_appointment' => 'datetime:Y-m-d',
    ];

    public static function getTableColumns(array $excludeColumns = [])
    {
        $columns = Schema::getColumnListing((new self)->getTable());

        // Exclude specified columns
        $filteredColumns = array_diff($columns, $excludeColumns);

        return $filteredColumns;
    }

    public function getClient()
    {
        return $this->belongsTo(Clientms::class, 'client_id', 'id');
    }

    public function getClientbranch()
    {
        return $this->belongsTo(ClientBranchMs::class, 'client_branch_id', 'id');
    }

    public function adminMs()
    {
        return $this->belongsTo(Admin_ms::class, 'admin_id', 'id');
    }


    public function get_branch_details()
    {
        return $this->belongsTo(Branch::class, 'admin_branch_id', 'id');
    }

    public function get_branch_childs()
    {
        return $this->belongsTo(Admin_ms::class, 'admin_branch_id', 'id');
    }

    public function get_policy_detail()
    {
        return $this->hasOne(InspectionPolicyDetail::class, 'inspection_id', 'id');
    }

    public function get_vehicle_detail()
    {
        return $this->hasOne(InspectionVehicleDetail::class, 'inspection_id', 'id');
    }

    public function get_feebill_detail()
    {
        return $this->hasOne(InspectionFeebillReport::class, 'inspection_id', 'id');
    }

    public function get_attachment_detail()
    {
        return $this->hasOne(InspectionAttachment::class, 'inspection_id', 'id');
    }

    public function get_accident_detail()
    {
        return $this->hasOne(InspectionAccidentDetail::class, 'inspection_id', 'id');
    }

    public function get_accident_cause_detail()
    {
        return $this->hasOne(InspectionAccecidentCause::class, 'inspection_id', 'id');
    }

    public function get_final_report_detail()
    {
        return $this->hasOne(FinalReportComment::class, 'inspection_id', 'id');
    }


    public function get_files_detail()
    {
        return $this->hasOne(InspectionFiles::class, 'inspection_id', 'id');
    }

    public function get_workshop_details()
    {
        return $this->belongsTo(Workshop::class, 'workshop_id', 'id');
    }

    public function get_workshop_branch__details()
    {
        return $this->belongsTo(Workshopbranchms::class, 'workshop_branch_id', 'id');
    }

    public function get_cabin_body()
    {
        return $this->hasOne(CabinBodyAssessmentModel::class, 'inspection_id', 'id');
    }

    public function get_cabin_body_tax()
    {
        return $this->hasOne(CabinBodyTaxSettingModel::class, 'inspection_id', 'id');
    }

    public function get_tax_dep_settings()
    {
        return $this->hasOne(TaxSetting::class, 'inspection_id', 'id');
    }


    protected $guarded = [];

    // protected $fillable = [
    // 	'id',
    // 	'inspection_id',
    // 	'claim_type',
    // 	'claim_no',
    // 	'policy_no',
    // 	'policy_valid_from',
    // 	'policy_valid_to',
    // 	'client_name',
    // 	'client_branch_id',
    // 	'branch_name',
    // 	'appointing_office_code',
    // 	'operating_office_code',
    // 	'insured_name',
    // 	'insured_address',
    // 	'insured_mobile_no',
    // 	'vehicle_regn_no',
    // 	'vehicle_type',
    // 	'date_of_registration',
    // 	'chassis_no',
    // 	'engine_no',
    // 	'vehicle_make',
    // 	'vehicle_model',
    // 	'odometer_reading',
    // 	'date_time_accident',
    // 	'place_accident',
    // 	'date_time_appointment',
    // 	'place_survey',
    // 	'workshop_name',
    // 	'workshop_branch',
    // 	'contact_person',
    // 	'contact_person_mobile',
    // 	'update_by',
    // 	'updated_date',
    // 	'accident_brief_description',
    //     'damage_corroborates_with_cause_of_loss',
    //     'accompanied_insurer_officer_details',
    //     'major_physical_damages',
    //     'suspected_Internal_damages',
    //     'spot_Survey_details',
    //     'preexisting_old_damages',
    //     'preferred_mode_of_assessment',
    //     'surveyor_APP_token_number',
    //     'ILA_discussed_with',
    //     'ILA_Submitted_on',
    //     'Reference_No',
    //     'Chassis_No_PV',
    //     'Engine_No_PV',
    //     'Survey_Date_time',
    //     'loss_estimate',
    //     'insurer_liability',
    //     'Vehicular_document_observation',

    // ];
}
