<?php

namespace App\Http\Controllers\MotorSurvey;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortcutsModel;
use Illuminate\Validation\Rule;

class ShortcutsController extends BaseController
{
    public function shortcutsList($admin_id=null, $index=null, $message = 'Data fetched successfully', $type=null) {
        if(!empty($admin_id) && is_numeric($admin_id)) {
            $where = ['status' => 1, 'admin_id' => $admin_id];
            $response = [];
            if(!empty($index)) {
                $response = ShortcutsModel::select('tag', 'tag_description')->where($where)->pluck('tag_description', 'tag')->toArray();
            } else {
                $query = ShortcutsModel::select('id', 'tag', 'tag_description')->where($where)->get();
                if ($query->count() > 0) {
                    $response = $query->toArray();
                }
            }
            $message = empty($response) ? 'Data empty' : $message;
            return $this->sendResponse($response, $message, 200);
        } else {
            return $this->sendError("Admin id required, Please try again with admin id.");
        }
    }

    public function shortcutsListWithTagIndex($admin_id=null) {
        return $this->shortcutsList($admin_id, true, 'Data fetched successfully.');
    }

    public function saveUpdateShortcuts(Request $request, $id=null) {
        $where = ['admin_id' => $request->admin_id, 'tag' => $request->tag];
        $request->validate([
            'tag_description' => 'required',
            'admin_id' => 'required|numeric',
            'admin_branch_id' => 'required|numeric',
            'tag' => ['required', 'string', Rule::unique('tbl_shortcuts')->where(function ($query) use($where) {
                return $query->where($where);
            })->ignore($id)]
        ]);
        $data = $request->all();
        $data['status'] = 1;
        if(!empty($id)) {
            $res = ShortcutsModel::where('id', $id)->update($data);
        } else{
            $res = ShortcutsModel::insert($data);
        }
        if($res) {
            $message = empty($id) ? 'Added Successfully.' : 'Updated Successfully.';
            return $this->shortcutsList($request->admin_id, null, $message);
        } else {
            $res_type = empty($id) ? 'added' : 'updated';
            return $this->sendError("Data not $res_type, Please try again!");
        }
    }

    public function deleteShortcuts($admin_id=null, $id=null) {
        if(!empty($id) && is_numeric($id) && !empty($admin_id) && is_numeric($admin_id)) {
            $shortcut = ShortcutsModel::find($id);
            if($shortcut) {
                $shortcut->status = 7;
                $shortcut->deleted_at = date('Y-m-d H:i:s');
                if ($shortcut->save()) {
                    return $this->shortcutsList($admin_id, null, 'Data deleted successfully.');
                }
            }
            return $this->sendError('Something went wrong, Please try again.');
        } else {
            return $this->sendError("Admin id required, Please try again with admin id.");
        }
    }
}
