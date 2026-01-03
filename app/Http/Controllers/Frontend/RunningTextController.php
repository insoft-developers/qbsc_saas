<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\RunningText;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RunningTextController extends Controller
{
    use CommonTrait;
    public function index()
    {
        
        $level = Auth::user()->level;

        if($level !== 'owner') {
            return redirect()->route('dashboard')->with('error', 'Ubah Running Text hanya bisa dilakukan di User yang berlevel "OWNER" ');
        }
        
        $view = 'running-text';
        $query = RunningText::where('comid', $this->comid())->first();
        if ($query) {
            $running_text = $query->text;
        } else {
            $running_text = '';
        }
        return view('frontend.setting.running_text.running_text', compact('view', 'running_text'));
    }

    public function update(Request $request)
    {
    
        $comid = $this->comid();
        RunningText::updateOrCreate(
            ['comid' => $comid], // kondisi pencarian
            [
                'text' => $request->running_text,
                'comid' => $comid
                
            ],
        );

        return response()->json(true);
    }
}
