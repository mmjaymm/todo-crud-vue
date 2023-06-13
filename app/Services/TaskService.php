<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskService
{
    public function uploadAttachment(Request $request)
    {
        $requestData = $request->all();

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $filename = Carbon::parse(now())->timestamp; //UTC
            $pathfile = $attachment->storeAs('attachments', $filename . '.' . $attachment->getClientOriginalExtension());

            if (!$pathfile) {
                return false;
            }

            $requestData['attachment'] = $pathfile;
        }

        return $requestData;
    }
}
