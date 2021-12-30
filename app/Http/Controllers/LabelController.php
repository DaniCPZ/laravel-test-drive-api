<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Response;
use App\Http\Requests\LabelRequest;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Auth::user()->labels;
        return $labels;
    }

    public function store(LabelRequest $request)
    {
        $label = Auth::user()->labels()->create($request->validated());
        return $label;
    }

    public function destroy(Label $label)
    {
        $label->delete();
        return response('', Response::HTTP_NO_CONTENT);
    }

    public function update(Label $label, LabelRequest $request)
    {
        $label->update($request->validated());
        return $label;
    }
}
