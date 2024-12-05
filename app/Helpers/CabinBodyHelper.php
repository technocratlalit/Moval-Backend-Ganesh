<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;


Class CabinBodyHelper {

    public static function commonCabinBodyGstApply($quantities = [], $tax_settings = []) {
        try {

            $multipleGstOnEstParts = [];
            $noneMultipleGstOnEstParts = [];
            $multipleGstOnAssParts = [];
            $noneMultipleGstOnAssParts = [];
            $multipleGstOnBilledLabour = [];
            $noneMultipleGstOnBilledLabour = [];
            $multipleLabourAssGst = [];
            $noneMultipleLabourAssGst = [];
            $multipleLabourEstGst = [];
            $noneMultipleLabourEstGst = [];
            $multipleGSTonBilled = [];
            $noneMultipleGSTonBilled = [];

            if(!empty($tax_settings)) {
                $partsGstValues = [];
                $laborGstValues = [];
                $billedGstValues = [];
                foreach ($quantities as $item) {
                    $gst = !empty($item['gst']) ? $item['gst'] : 0;
                    if (!empty($item['category'])) {
                        $partsGstValues[$gst] = $gst;
                    }
                    if ($item['est_lab'] > 0 || $item['ass_lab'] > 0) {
                        $laborGstValues[$gst] = $gst;
                    }
                    if ($item['billed_part_amt'] > 0 || $item['billed_lab_amt'] > 0) {
                        $billedGstValues[$gst] = $gst;
                    }
                    if (!empty($item['quantities'])) {
                        foreach ($item['quantities'] as $key) {
                            $gstSub = !empty($key['gst']) ? $key['gst'] : 0;
                            if (!empty($key['category'])) {
                                $partsGstValues[$gstSub] = $gstSub;
                            }
                            if ($key['est_lab'] > 0 || $key['ass_lab'] > 0) {
                                $laborGstValues[$gstSub] = $gstSub;
                            }
                            if ($key['billed_part_amt'] > 0 || $key['billed_lab_amt'] > 0) {
                                $billedGstValues[$gstSub] = $gstSub;
                            }
                        }
                    }
                }

                if (!empty($tax_settings['set_mutilple_gst_on_parts']) && $tax_settings['gst_parts_est_amt'] > 0) {
                    $multipleGstOnEstParts = array_unique(array_values($partsGstValues));
                } else {
                    $nonGstEstPart = ($tax_settings['gst_parts_est_amt'] > 0) ? intval($tax_settings['gst_parts_est_amt']) : 0;
                    $noneMultipleGstOnEstParts[$nonGstEstPart] = $nonGstEstPart;
                }

                if (!empty($tax_settings['set_mutilple_gst_on_parts']) && $tax_settings['gst_parts_ass_amt'] > 0) {
                    $multipleGstOnAssParts = array_unique(array_values($partsGstValues));
                } else {
                    $nonGstEstPart = ($tax_settings['gst_parts_ass_amt'] > 0) ? intval($tax_settings['gst_parts_ass_amt']) : 0;
                    $noneMultipleGstOnAssParts[$nonGstEstPart] = $nonGstEstPart;
                }

                if (!empty($tax_settings['set_mutilple_gst_on_labour']) && !empty($tax_settings['gst_on_ass_lab']) && $tax_settings['est_ass_gst_lab_per'] > 0) {
                    $multipleLabourAssGst = array_unique(array_values($laborGstValues));
                    $multipleGstOnBilledLabour = array_unique(array_values($laborGstValues));
                } else {
                    $GSTLabourPer = ($tax_settings['est_ass_gst_lab_per'] > 0 && !empty($tax_settings['gst_on_ass_lab'])) ? intval($tax_settings['est_ass_gst_lab_per']) : 0;
                    $noneMultipleLabourAssGst[$GSTLabourPer] = $GSTLabourPer;
                    $noneMultipleGstOnBilledLabour[$GSTLabourPer] = $GSTLabourPer;
                }

                if (!empty($tax_settings['set_mutilple_gst_on_labour']) && !empty($tax_settings['gst_on_est_lab']) && $tax_settings['est_ass_gst_lab_per'] > 0) {
                    $multipleLabourEstGst = array_unique(array_values($laborGstValues));
                } else {
                    $GSTLabourPer = ($tax_settings['est_ass_gst_lab_per'] > 0 && !empty($tax_settings['gst_on_est_lab'])) ? intval($tax_settings['est_ass_gst_lab_per']) : 0;
                    $noneMultipleLabourEstGst[$GSTLabourPer] = $GSTLabourPer;
                }

                if (!empty($tax_settings['multiple_gst_on_billed_amount']) && $tax_settings['gst_parts_bill_amt'] > 0) {
                    $multipleGSTonBilled = array_unique(array_values($billedGstValues));
                } else {
                    $GSTBilledPartPer = ($tax_settings['gst_parts_bill_amt'] > 0) ? intval($tax_settings['gst_parts_bill_amt']) : 0;
                    $noneMultipleGSTonBilled[$GSTBilledPartPer] = $GSTBilledPartPer;
                }
            } else {
                $multipleGstOnEstParts = [];
                $noneMultipleGstOnEstParts = ['0' => 0];
                $multipleGstOnAssParts = [];
                $noneMultipleGstOnAssParts = ['0' => 0];
                $multipleGstOnBilledLabour = [];
                $noneMultipleGstOnBilledLabour = ['0' => 0];
                $multipleLabourAssGst = [];
                $noneMultipleLabourAssGst = ['0' => 0];
                $multipleLabourEstGst = [];
                $noneMultipleLabourEstGst = ['0' => 0];
                $multipleGSTonBilled = [];
                $noneMultipleGSTonBilled = ['0' => 0];
            }

            $response = [
                'multipleGstOnEstParts' => !empty($multipleGstOnEstParts) ? array_combine($multipleGstOnEstParts, $multipleGstOnEstParts) : [],
                'noneMultipleGstOnEstParts' => !empty($noneMultipleGstOnEstParts) ? array_combine($noneMultipleGstOnEstParts, $noneMultipleGstOnEstParts) : [],
                'multipleGstOnAssParts' => !empty($multipleGstOnAssParts) ? array_combine($multipleGstOnAssParts, $multipleGstOnAssParts) : [],
                'noneMultipleGstOnAssParts' => !empty($noneMultipleGstOnAssParts) ? array_combine($noneMultipleGstOnAssParts, $noneMultipleGstOnAssParts) : [],
                'multipleLabourAssGst' => !empty($multipleLabourAssGst) ? array_combine($multipleLabourAssGst, $multipleLabourAssGst) : [],
                'noneMultipleLabourAssGst' => !empty($noneMultipleLabourAssGst) ? array_combine($noneMultipleLabourAssGst, $noneMultipleLabourAssGst) : [],
                'multipleLabourEstGst' => !empty($multipleLabourEstGst) ? array_combine($multipleLabourEstGst, $multipleLabourEstGst) : [],
                'noneMultipleLabourEstGst' => !empty($noneMultipleLabourEstGst) ? array_combine($noneMultipleLabourEstGst, $noneMultipleLabourEstGst) : [],
                'multipleGstOnBilledLabour' => !empty($multipleGstOnBilledLabour) ? array_combine($multipleGstOnBilledLabour, $multipleGstOnBilledLabour) : [],
                'noneMultipleGstOnBilledLabour' => !empty($noneMultipleGstOnBilledLabour) ? array_combine($noneMultipleGstOnBilledLabour, $noneMultipleGstOnBilledLabour) : [],
                'multipleGSTonBilled' => !empty($multipleGSTonBilled) ? array_combine($multipleGSTonBilled, $multipleGSTonBilled) : [],
                'noneMultipleGSTonBilled' => !empty($noneMultipleGSTonBilled) ? array_combine($noneMultipleGSTonBilled, $noneMultipleGSTonBilled) : [],
            ];
            return $response;

        } catch (\Exception $e) {
            return [];
        }
    }

    public static function arrangeQuantityAmountGstWise($tax_settings = [], $quantities = []) {
        try {
            $response = [];
            $parts_amount_gst = [];
            $labour_amount_gst = [];
            foreach ($quantities as $item) {
                $gst = !empty($item['gst']) ? intval($item['gst']) : intval(0);
                $portion = !empty($item['portion']) ? intval($item['portion']) : 0;
                $metal = 0;
                $glass = 0;
                $rubberPlastic = 0;
                $fiber = 0;
                $recondition = 0;
                $supplementary = 0;
                $metalEst = 0;
                $glassEst = 0;
                $rubberPlasticEst = 0;
                $fiberEst = 0;
                $reconditionEst = 0;
                $supplementaryEst = 0;
                if (!empty($item['category'])) {
                    switch ($item['category']) {
                        case 1: //1 = Metal
                            $metal = ($item['ass_amt'] > 0) ? $item['ass_amt'] : 0;
                            $metalEst = ($item['est_amt'] > 0) ? $item['est_amt'] : 0;
                            break;
                        case 2: //2=Glass
                            $glass = ($item['ass_amt'] > 0) ? $item['ass_amt'] : 0;
                            $glassEst = ($item['est_amt'] > 0) ? $item['est_amt'] : 0;
                            break;
                        case 3: //3= Rubber
                            $rubberPlastic = ($item['ass_amt'] > 0) ? $item['ass_amt'] : 0;
                            $rubberPlasticEst = ($item['est_amt'] > 0) ? $item['est_amt'] : 0;
                            break;
                        case 4: //4 = Fiber
                            $fiber = ($item['ass_amt'] > 0) ? $item['ass_amt'] : 0;
                            $fiberEst = ($item['est_amt'] > 0) ? $item['est_amt'] : 0;
                            break;
                        case 5: //5 = Recondition
                            $recondition = ($item['ass_amt'] > 0) ? $item['ass_amt'] : 0;
                            $reconditionEst = ($item['est_amt'] > 0) ? $item['est_amt'] : 0;
                            break;
                        case 6: //6 = Supplementary
                            $supplementary = ($item['ass_amt'] > 0) ? $item['ass_amt'] : 0;
                            $supplementaryEst = ($item['est_amt'] > 0) ? $item['est_amt'] : 0;
                            break;
                        default:
                            break;
                    }
                }
                if (empty($item['quantities'])) {
                    if (isset($tax_settings['multipleGstOnAssParts']) && !empty($tax_settings['multipleGstOnAssParts']) && isset($tax_settings['multipleGstOnAssParts'][$gst])) {
                        if (isset($response[$portion]['parts'][$gst]['assessed'])) {
                            $response[$portion]['parts'][$gst]['assessed']['metal'] += $metal;
                            $response[$portion]['parts'][$gst]['assessed']['glass'] += $glass;
                            $response[$portion]['parts'][$gst]['assessed']['rubberPlastic'] += $rubberPlastic;
                            $response[$portion]['parts'][$gst]['assessed']['fiber'] += $fiber;
                            $response[$portion]['parts'][$gst]['assessed']['recondition'] += $recondition;
                            $response[$portion]['parts'][$gst]['assessed']['supplementary'] += $supplementary;
                        } else {
                            $response[$portion]['parts'][$gst]['assessed'] = [
                                'metal' => $metal,
                                'glass' => $glass,
                                'rubberPlastic' => $rubberPlastic,
                                'fiber' => $fiber,
                                'recondition' => $recondition,
                                'supplementary' => $supplementary,
                            ];
                        }
                        $parts_amount_gst[$portion][$gst] = $gst;
                    } elseif (isset($tax_settings['noneMultipleGstOnAssParts']) && !empty($tax_settings['noneMultipleGstOnAssParts'])) {
                        $rate = intval(reset($tax_settings['noneMultipleGstOnEstParts']));
                        $parts_amount_gst[$portion][$rate] = $rate;
                        if (isset($response[$portion]['parts'][$rate]['assessed'])) {
                            $response[$portion]['parts'][$rate]['assessed']['metal'] += $metal;
                            $response[$portion]['parts'][$rate]['assessed']['glass'] += $glass;
                            $response[$portion]['parts'][$rate]['assessed']['rubberPlastic'] += $rubberPlastic;
                            $response[$portion]['parts'][$rate]['assessed']['fiber'] += $fiber;
                            $response[$portion]['parts'][$rate]['assessed']['recondition'] += $recondition;
                            $response[$portion]['parts'][$rate]['assessed']['supplementary'] += $supplementary;
                        } else {
                            $response[$portion]['parts'][$rate]['assessed'] = [
                                'metal' => $metal,
                                'glass' => $glass,
                                'rubberPlastic' => $rubberPlastic,
                                'fiber' => $fiber,
                                'recondition' => $recondition,
                                'supplementary' => $supplementary,
                            ];
                        }
                    }

                    if ($item['ass_lab'] > 0) {
                        if (isset($tax_settings['multipleLabourAssGst']) && !empty($tax_settings['multipleLabourAssGst']) && isset($tax_settings['multipleLabourAssGst'][$gst])) {
                            if (isset($response[$portion]['labour'][$gst]['assessed'])) {
                                $response[$portion]['labour'][$gst]['assessed'] += $item['ass_lab'];
                            } else {
                                $response[$portion]['labour'][$gst]['assessed'] = $item['ass_lab'];
                            }
                            $labour_amount_gst[$portion][$gst] = $gst;
                        } elseif (isset($tax_settings['noneMultipleLabourAssGst']) && !empty($tax_settings['noneMultipleLabourAssGst'])) {
                            $lab_rate = intval(reset($tax_settings['noneMultipleLabourAssGst']));
                            $labour_amount_gst[$portion][$gst] = $gst;
                            if (isset($response[$portion]['labour'][$lab_rate]['assessed'])) {
                                $response[$portion]['labour'][$lab_rate]['assessed'] += $item['ass_lab'];
                            } else {
                                $response[$portion]['labour'][$lab_rate]['assessed'] = $item['ass_lab'];
                            }
                        }
                    }
                }

                if (!empty($item['category'])) {
                    if (isset($tax_settings['multipleGstOnEstParts']) && !empty($tax_settings['multipleGstOnEstParts']) && isset($tax_settings['multipleGstOnEstParts'][$gst])) {
                        if (isset($response[$portion]['parts'][$gst]['estimated'])) {
                            $response[$portion]['parts'][$gst]['estimated']['metal'] += $metalEst;
                            $response[$portion]['parts'][$gst]['estimated']['glass'] += $glassEst;
                            $response[$portion]['parts'][$gst]['estimated']['rubberPlastic'] += $rubberPlasticEst;
                            $response[$portion]['parts'][$gst]['estimated']['fiber'] += $fiberEst;
                            $response[$portion]['parts'][$gst]['estimated']['recondition'] += $reconditionEst;
                            $response[$portion]['parts'][$gst]['estimated']['supplementary'] += $supplementaryEst;
                        } else {
                            $response[$portion]['parts'][$gst]['estimated'] = [
                                'metal' => $metalEst,
                                'glass' => $glassEst,
                                'rubberPlastic' => $rubberPlasticEst,
                                'fiber' => $fiberEst,
                                'recondition' => $reconditionEst,
                                'supplementary' => $supplementaryEst,
                            ];
                        }
                        $parts_amount_gst[$portion][$gst] = $gst;
                    } elseif (isset($tax_settings['noneMultipleGstOnEstParts']) && !empty($tax_settings['noneMultipleGstOnEstParts'])) {
                        $rate = intval(reset($tax_settings['noneMultipleGstOnEstParts']));
                        $parts_amount_gst[$portion][$rate] = $rate;
                        if (isset($response[$portion]['parts'][$rate]['estimated'])) {
                            $response[$portion]['parts'][$rate]['estimated']['metal'] += $metalEst;
                            $response[$portion]['parts'][$rate]['estimated']['glass'] += $glassEst;
                            $response[$portion]['parts'][$rate]['estimated']['rubberPlastic'] += $rubberPlasticEst;
                            $response[$portion]['parts'][$rate]['estimated']['fiber'] += $fiberEst;
                            $response[$portion]['parts'][$rate]['estimated']['recondition'] += $reconditionEst;
                            $response[$portion]['parts'][$rate]['estimated']['supplementary'] += $supplementaryEst;
                        } else {
                            $response[$portion]['parts'][$rate]['estimated'] = [
                                'metal' => $metalEst,
                                'glass' => $glassEst,
                                'rubberPlastic' => $rubberPlasticEst,
                                'fiber' => $fiberEst,
                                'recondition' => $reconditionEst,
                                'supplementary' => $supplementaryEst,
                            ];
                        }
                    }
                }

                if ($item['est_lab'] > 0) {
                    if (isset($tax_settings['multipleLabourEstGst']) && !empty($tax_settings['multipleLabourEstGst']) && isset($tax_settings['multipleLabourEstGst'][$gst])) {
                        if (isset($response[$portion]['labour'][$gst]['estimated'])) {
                            $response[$portion]['labour'][$gst]['estimated'] += ($item['est_lab'] > 0) ? $item['est_lab'] : 0;
                        } else {
                            $response[$portion]['labour'][$gst]['estimated'] = ($item['est_lab'] > 0) ? $item['est_lab'] : 0;
                        }
                        $labour_amount_gst[$portion][$gst] = $gst;
                    } elseif (isset($tax_settings['noneMultipleLabourEstGst']) && !empty($tax_settings['noneMultipleLabourEstGst'])) {
                        $lab_rate = intval(reset($tax_settings['noneMultipleLabourEstGst']));
                        $labour_amount_gst[$portion][$lab_rate] = $lab_rate;
                        if (isset($response[$portion]['labour'][$lab_rate]['estimated'])) {
                            $response[$portion]['labour'][$lab_rate]['estimated'] += ($item['est_lab'] > 0) ? $item['est_lab'] : 0;
                        } else {
                            $response[$portion]['labour'][$lab_rate]['estimated'] = ($item['est_lab'] > 0) ? $item['est_lab'] : 0;
                        }
                    }
                }

                if (!empty($item['quantities'])) {
                    foreach ($item['quantities'] as $child_item) {
                        $childGst = !empty($child_item['gst']) ? intval($child_item['gst']) : intval(0);
                        $childMetal = 0;
                        $childGlass = 0;
                        $childRubberPlastic = 0;
                        $childFiber = 0;
                        $childRecondition = 0;
                        $childSupplementary = 0;

                        if (!empty($child_item['category'])) {
                            switch ($child_item['category']) {
                                case 1: //1 = Metal
                                    $childMetal = ($child_item['ass_amt'] > 0) ? $child_item['ass_amt'] : 0;
                                    break;
                                case 2: //2=Glass
                                    $childGlass = ($child_item['ass_amt'] > 0) ? $child_item['ass_amt'] : 0;
                                    break;
                                case 3: //3= Rubber
                                    $childRubberPlastic = ($child_item['ass_amt'] > 0) ? $child_item['ass_amt'] : 0;
                                    break;
                                case 4: //4 = Fiber
                                    $childFiber = ($child_item['ass_amt'] > 0) ? $child_item['ass_amt'] : 0;
                                    break;
                                case 5: //5 = Recondition
                                    $childRecondition = ($child_item['ass_amt'] > 0) ? $child_item['ass_amt'] : 0;
                                    break;
                                case 6: //6 = Supplementary
                                    $childSupplementary = ($child_item['ass_amt'] > 0) ? $child_item['ass_amt'] : 0;
                                    break;
                                default:
                                    break;
                            }

                            if (isset($tax_settings['multipleGstOnAssParts']) && !empty($tax_settings['multipleGstOnAssParts']) && isset($tax_settings['multipleGstOnAssParts'][$childGst])) {
                                if (isset($response[$portion]['parts'][$childGst]['assessed'])) {
                                    $response[$portion]['parts'][$childGst]['assessed']['metal'] += $childMetal;
                                    $response[$portion]['parts'][$childGst]['assessed']['glass'] += $childGlass;
                                    $response[$portion]['parts'][$childGst]['assessed']['rubberPlastic'] += $childRubberPlastic;
                                    $response[$portion]['parts'][$childGst]['assessed']['fiber'] += $childFiber;
                                    $response[$portion]['parts'][$childGst]['assessed']['recondition'] += $childRecondition;
                                    $response[$portion]['parts'][$childGst]['assessed']['supplementary'] += $childSupplementary;
                                } else {
                                    $response[$portion]['parts'][$childGst]['assessed'] = [
                                        'metal' => $childMetal,
                                        'glass' => $childGlass,
                                        'rubberPlastic' => $childRubberPlastic,
                                        'fiber' => $childFiber,
                                        'recondition' => $childRecondition,
                                        'supplementary' => $childSupplementary,
                                    ];
                                }
                                $parts_amount_gst[$portion][$childGst] = $childGst;
                            } elseif (isset($tax_settings['noneMultipleGstOnAssParts']) && !empty($tax_settings['noneMultipleGstOnAssParts'])) {
                                $rate = intval(reset($tax_settings['noneMultipleGstOnEstParts']));
                                $parts_amount_gst[$portion][$rate] = $rate;
                                if (isset($response[$portion]['parts'][$rate]['assessed'])) {
                                    $response[$portion]['parts'][$rate]['assessed']['metal'] += $childMetal;
                                    $response[$portion]['parts'][$rate]['assessed']['glass'] += $childGlass;
                                    $response[$portion]['parts'][$rate]['assessed']['rubberPlastic'] += $childRubberPlastic;
                                    $response[$portion]['parts'][$rate]['assessed']['fiber'] += $childFiber;
                                    $response[$portion]['parts'][$rate]['assessed']['recondition'] += $childRecondition;
                                    $response[$portion]['parts'][$rate]['assessed']['supplementary'] += $childSupplementary;
                                } else {
                                    $response[$portion]['parts'][$rate]['assessed'] = [
                                        'metal' => $childMetal,
                                        'glass' => $childGlass,
                                        'rubberPlastic' => $childRubberPlastic,
                                        'fiber' => $childFiber,
                                        'recondition' => $childRecondition,
                                        'supplementary' => $childSupplementary,
                                    ];
                                }
                            }
                        }

                        if ($child_item['ass_lab'] > 0) {
                            if (isset($tax_settings['multipleLabourAssGst']) && !empty($tax_settings['multipleLabourAssGst']) && isset($tax_settings['multipleLabourAssGst'][$childGst])) {
                                if (isset($response[$portion]['labour'][$childGst]['assessed'])) {
                                    $response[$portion]['labour'][$childGst]['assessed'] += $child_item['ass_lab'];
                                } else {
                                    $response[$portion]['labour'][$childGst]['assessed'] = $child_item['ass_lab'];
                                }
                                $labour_amount_gst[$portion][$childGst] = $childGst;
                            } elseif (isset($tax_settings['noneMultipleLabourAssGst']) && !empty($tax_settings['noneMultipleLabourAssGst'])) {
                                $lab_rate = reset($tax_settings['noneMultipleLabourAssGst']);
                                $labour_amount_gst[$portion][$lab_rate] = intval($lab_rate);
                                if (isset($response[$portion]['labour'][$lab_rate]['assessed'])) {
                                    $response[$portion]['labour'][$lab_rate]['assessed'] += $child_item['ass_lab'];
                                } else {
                                    $response[$portion]['labour'][$lab_rate]['assessed'] = $child_item['ass_lab'];
                                }
                            }
                        }
                    }
                }
            }

            for ($i = 1; $i <= 2; $i++) {
                if (isset($labour_amount_gst[$i])) {
                    $arr = $labour_amount_gst[$i];
                    sort($arr);
                    $labour_amount_gst[$i] = $arr;
                }
                if (isset($parts_amount_gst[$i])) {
                    $arr = $parts_amount_gst[$i];
                    sort($arr);
                    $parts_amount_gst[$i] = $arr;
                }
            }
            return ['response' => $response, 'parts_gst' => $parts_amount_gst, 'labour_gst' => $labour_amount_gst];
        } catch (\Exception $e) {
            return [];
        }
    }

    public static function calculateQuantitiesAmountTaxBase($gst_wise_value = [], $parts_gst = [], $labour_gst = [], $tax_dep = []) {

        $response = [];
        $metalDepPer = (isset($tax_dep['MetalDepPer']) && $tax_dep['MetalDepPer'] > 0 && empty($tax_dep['IsZeroDep'])) ? $tax_dep['MetalDepPer'] : 0;
        $rubberDepPer = (isset($tax_dep['RubberDepPer']) && $tax_dep['RubberDepPer'] > 0 && empty($tax_dep['IsZeroDep'])) ? $tax_dep['RubberDepPer'] : 0;
        $glassDepPer = (isset($tax_dep['GlassDepPer']) && $tax_dep['GlassDepPer'] > 0 && empty($tax_dep['IsZeroDep'])) ? $tax_dep['GlassDepPer'] : 0;
        $fibreDepPer = (isset($tax_dep['FibreDepPer']) && $tax_dep['FibreDepPer'] > 0 && empty($tax_dep['IsZeroDep'])) ? $tax_dep['FibreDepPer'] : 0;

        if(!empty($gst_wise_value)) {
            foreach ($gst_wise_value as $portion => $portion_item) {
                $response[$portion]['parts_details'] = [];
                $response[$portion]['parts_details_total'] = [];
                $response[$portion]['parts_total'] = [];
                $response[$portion]['labour_details'] = [];
                $response[$portion]['labour_details_total'] = [];
                $response[$portion]['labour_total'] = [];
                foreach ($portion_item as $type => $type_item) {
                    switch ($type) {
                        case 'parts':
                            if(isset($parts_gst[$portion]) && !empty($parts_gst[$portion])) {
                                foreach ($parts_gst[$portion] as $gst) {
                                    if (isset($type_item[$gst]) && !empty($type_item[$gst])) {
                                        foreach ($type_item[$gst] as $key => $item) {
                                            $response[$portion]['parts_details'][$gst][$key] = $item;
                                            foreach ($item as $index => $total) {
                                                if($total > 0) {
                                                    switch ($index) {
                                                        case 'metal':
                                                            $dep_percent = !empty($metalDepPer) ? $metalDepPer : 0;
                                                            break;
                                                        case 'glass':
                                                            $dep_percent = !empty($glassDepPer) ? $glassDepPer : 0;
                                                            break;
                                                        case 'rubberPlastic':
                                                            $dep_percent = !empty($rubberDepPer) ? $rubberDepPer : 0;
                                                            break;
                                                        case 'fiber':
                                                            $dep_percent = !empty($fibreDepPer) ? $fibreDepPer : 0;
                                                            break;
                                                        case 'recondition':
                                                        case 'supplementary':
                                                            $dep_percent = 0;
                                                            break;
                                                        default:
                                                            $dep_percent = 0;
                                                            break;
                                                    }

                                                    switch ($key) {
                                                        case 'assessed':
                                                            $dep = 0;
                                                            $amt_after_dep = $total;
                                                            if(!empty($dep_percent)) {
                                                                $dep = (($total * $dep_percent) / 100);
                                                                $amt_after_dep = ($total - $dep);
                                                            }
                                                            $gst_amount = (($amt_after_dep * $gst) / 100);
                                                            $amt_after_gst = ($amt_after_dep + $gst_amount);
                                                            $response[$portion]['parts_details_total'][$gst][$key][$index]['total'] = $total;
                                                            $response[$portion]['parts_details_total'][$gst][$key][$index]['dep'] = $dep;
                                                            $response[$portion]['parts_details_total'][$gst][$key][$index]['amt_after_dep'] = $amt_after_dep;
                                                            $response[$portion]['parts_details_total'][$gst][$key][$index]['gst_amount'] = $gst_amount;
                                                            $response[$portion]['parts_details_total'][$gst][$key][$index]['amt_after_gst'] = $amt_after_gst;
                                                            if(isset($response[$portion]['parts_total'][$key][$index])) {
                                                                $response[$portion]['parts_total'][$key][$index] += $amt_after_gst;
                                                            } else {
                                                                $response[$portion]['parts_total'][$key][$index] = $amt_after_gst;
                                                            }
                                                            break;
                                                        case 'estimated':
                                                            $gst_amount = (($total * $gst) / 100);
                                                            $amt_after_gst = ($total + $gst_amount);
                                                            $response[$portion]['parts_details_total'][$gst][$key][$index]['total'] = $total;
                                                            $response[$portion]['parts_details_total'][$gst][$key][$index]['gst_amount'] = $gst_amount;
                                                            $response[$portion]['parts_details_total'][$gst][$key][$index]['amt_after_gst'] = $amt_after_gst;
                                                            if(isset($response[$portion]['parts_total'][$key][$index])) {
                                                                $response[$portion]['parts_total'][$key][$index] += $amt_after_gst;
                                                            } else {
                                                                $response[$portion]['parts_total'][$key][$index] = $amt_after_gst;
                                                            }
                                                            break;
                                                        default: break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            break;
                        case 'labour':
                            if(isset($labour_gst[$portion]) && !empty($labour_gst[$portion])) {
                                foreach ($labour_gst[$portion] as $gst) {
                                    if (isset($type_item[$gst]) && !empty($type_item[$gst])) {
                                        $response[$portion]['labour_details'][$gst] = $type_item[$gst];
                                        foreach ($type_item[$gst] as $key => $item) {
                                            if($item > 0) {
                                                $gst_amount = (($item * $gst) / 100);
                                                $amt_after_gst = ($item + $gst_amount);
                                                $response[$portion]['labour_details_total'][$gst][$key]['total'] = $item;
                                                $response[$portion]['labour_details_total'][$gst][$key]['gst_amount'] = $gst_amount;
                                                $response[$portion]['labour_details_total'][$gst][$key]['amt_after_gst'] = $amt_after_gst;
                                                if (isset($response[$portion]['labour_total'][$key])) {
                                                    $response[$portion]['labour_total'][$key] += $amt_after_gst;
                                                } else {
                                                    $response[$portion]['labour_total'][$key] = $amt_after_gst;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            break;
                        default: break;
                    }
                }
            }
        }
        return $response;
    }
}
