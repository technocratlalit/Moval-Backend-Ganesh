<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssismentCalculation extends Model
{
    use HasFactory;

    public $table = "tbl_ms_assisment_calculations";

    protected $fillable = [
        'inspection_id',
        'vehicle_reg_no',
        'Insurerd_name',
        'totalestonlypart',
        'totalreconditionestonly',
        'totalendoresmentestonly',
        'partMetalAssamount',
        'partRubberAssamount',
        'partGlassAssamount',
        'partFibreAssamount',
        'totalendoresmentAss',
        'totalassparts',
        'totalestparts',
        'totalreconditionAss',
        'totalreconditionEst',
        'totalAssWithReconditon',
        'totalEstWithReconditon',
        'totallabourass',
        'totallabourest',
        'total_labourAmtWithGst',
        'total_labestAmtWithoutGST',
        'total_labassAmtWithoutGST',
        'total_paintingassAmtWithoutGST',
        'paintinglabass',
        'IMTPaintingLabAss',
        'PaintingMaterialDepAmt',
        'PaitingMaterialAfterDep',
        'paintinglabest',
        'IMTPaintingLabEst',
        'netlabourAss',
        'netlabourEst',
        'totalass',
        'totalest',
        'ImposedClause',
        'CompulsoryDeductable',
        'SalvageAmt',
        'CustomerLiability',
        'TowingCharges',
        'netbody',
        'alltotalass',
        'total_EstAmt',
        'totalmateriallab',
        'totalMetalAmt',
        'totalRubberAmt',
        'totalGlassAmt',
        'totalFibreAmt',
        'totalReconditionAmt',
        'totalMetalIMTAmt',
        'totalRubberIMTAmt',
        'DepAmtMetal',
        'DepAmtMetal',
        'DepAmtGlass',
        'insurer_liability',
        'DepAmtFibre',
        'DepAmtIMTMetal',
        'DepAmtIMTRubber',
        'less_voluntary_excess',
        'additional_towing',
        // Add any other columns here
    ];

    /**
     * Create or update a record based on inspection_id.
     *
     * @param array $attributes
     * @return AssismentCalculation
     */
    
    public static function createOrUpdate(array $attributes)
    {
        $inspectionId = $attributes['inspection_id'];

        $record = self::where('inspection_id', $inspectionId)->first();

        if ($record) {
            // Update existing record
            $record->update($attributes);
        } else {
            // Create new record
            $record = self::create($attributes);
        }

        return $record;
    }

}
