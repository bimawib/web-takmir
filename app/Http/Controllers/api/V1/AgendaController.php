<?php

namespace App\Http\Controllers\api\V1;

use App\Models\Agenda;
use App\Models\AgendaDetail;
use Illuminate\Http\Request;
use App\Filters\V1\AgendaFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AgendaResource;
use App\Http\Resources\V1\AgendaCollection;
use App\Http\Requests\V1\StoreAgendaRequest;
use App\Http\Requests\V1\UpdateAgendaRequest;
use Illuminate\Support\Facades\Validator;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = new AgendaFilter();
        $queryItems = $filter->transform($request); // ['nama kolom', 'operator ex : like, <, =', 'value']

        $includeDetails = $request->query('includeDetails');
        $agenda = Agenda::where($queryItems);

        if(isset($includeDetails) && $includeDetails == 1){
            $agenda = $agenda->with('agenda_detail');
        }

        return new AgendaCollection($agenda->paginate()->appends($request->query()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAgendaRequest $request)
    {
        $user = auth('sanctum')->user();
        if($user->is_admin == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to store agenda!'
                ]
            ],403);
        }

        $request['user_id'] = auth('sanctum')->user()->id;
        $request['published_at'] = now();

        $request['slug'] = $this->slugCreate($request['title']);

        $request_detail = $request->agendaDetail;
        $is_valid = $this->detailValidator($request_detail);

        if($is_valid != "success"){
            return $is_valid;
        }

        $create = Agenda::create($request->all());

        foreach($request_detail as $value){
            AgendaDetail::create([
                'agenda_id'=>$create->id,
                'agenda_name'=>$value['agendaName'],
                'start_time'=>$value['startTime'],
                'end_time'=>$value['endTime'],
                'location'=>$value['location'],
                'keynote_speaker'=>$value['keynoteSpeaker'],
                'note'=>$value['note']
            ]);
        }
        
        $with_detail = Agenda::where('slug',$request->slug)->with('agenda_detail')->first();
        $slug_after = $with_detail->slug . $with_detail->id;

        Agenda::where('slug',$request->slug)->update(['slug'=>$slug_after]); // update slug dengan id

        $with_fixed_slug = Agenda::where('slug',$slug_after)->with('agenda_detail')->first();

        return new AgendaResource($with_fixed_slug);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function show(Agenda $agenda)
    {
        $agenda = Agenda::where('slug',$agenda->slug)->with('agenda_detail')->first();
        return new AgendaResource($agenda);
        // should use slug instead for public reading
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAgendaRequest $request, Agenda $agenda)
    {
        $user = auth('sanctum')->user();
        if($user->is_admin == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to update agenda!'
                ]
            ],403);
        }
        $detail = AgendaDetail::where('agenda_id',$agenda->id)->get();
        $request_detail = $request->agendaDetail;

        $request['slug'] = $this->slugCreate($request->title, $agenda->title, $agenda->id) ?? $agenda->slug;
        // slug tidak akan berubah jika 3 kata pertama dari title sebelum dan sesudah diupdate sama

        $last_request_detail = array_key_last($request_detail);
        $last_unupdated_detail = array_key_last($detail->toArray());

        $is_valid = $this->detailValidator($request_detail);
        if($is_valid != "success"){
            return $is_valid;
        }

        $agenda->update($request->all());

        if(!isset($last_request_detail)){
            AgendaDetail::where('agenda_id',$agenda->id)->delete();

            $with_detail = Agenda::where('slug',$request['slug'])->with('agenda_detail')->first();
        
            return new AgendaResource($with_detail);
        }

        if($last_request_detail < $last_unupdated_detail){
            AgendaDetail::where('agenda_id',$agenda->id)->where('id','>',$detail[$last_request_detail]->id)->delete();
        }
        
        function requestArray($agenda_id,$req_dtl,$arr){
            return [
                'agenda_id'=>$agenda_id,
                'agenda_name'=>$req_dtl[$arr]['agendaName'],
                'start_time'=>$req_dtl[$arr]['startTime'],
                'end_time'=>$req_dtl[$arr]['endTime'],
                'location'=>$req_dtl[$arr]['location'],
                'keynote_speaker'=>$req_dtl[$arr]['keynoteSpeaker'],
                'note'=>$req_dtl[$arr]['note']
            ];
        }

        $detail_after = AgendaDetail::where('agenda_id',$agenda->id)->get();
        $arr_counter = 0;
        foreach($detail_after as $da){
            $asu = requestArray($agenda->id, $request_detail, $arr_counter);
            AgendaDetail::where('agenda_id',$agenda->id)->where('id',$detail_after[$arr_counter]->id)->update($asu);
            $arr_counter++;
        }

        $new_detail = []; // cek buat ada tidaknya agenda detail dari agenda sebelumnya yg sudah dibuat
        $is_dtl_set = 1;
        $dtl_add = 0;
        
        if(!isset($last_unupdated_detail)){
            $is_dtl_set = 0;
            $dtl_add = 1;
        }

        for($unreq = $last_unupdated_detail + $is_dtl_set; $unreq < $last_request_detail + $is_dtl_set + $dtl_add; $unreq++){
            $new_detail[] = requestArray($agenda->id, $request_detail, $unreq);
        }

        AgendaDetail::insert($new_detail);

        $with_detail = Agenda::where('slug',$request['slug'])->with('agenda_detail')->first();
        
        return new AgendaResource($with_detail);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agenda $agenda)
    {
        $user = auth('sanctum')->user();
        if($user->is_admin == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to delete agenda!'
                ]
            ],403);
        }

        AgendaDetail::where('agenda_id',$agenda->id)->delete();
        Agenda::destroy($agenda->id);
    }

    private function detailValidator($request_detail){
        $validator = Validator::make($request_detail,[
            '*.agendaName'=>'required|max:255',
            '*.startTime'=>'required|date_format:Y-m-d H:i:s',
            '*.endTime'=>'required|date_format:Y-m-d H:i:s',
            '*.location'=>'required|max:255',
            '*.keynoteSpeaker'=>'required|max:255',
            '*.note'=>'required|max:255'
        ]);
        if($validator->fails()){ 
            return $validator->messages();
        } else {
            return "success";
        }
    }

    public function slugCreate($name, $name_before = '', $model_id = ''){
        $splitter = explode(" ", $name);
        $attacher = implode("-", array_splice($splitter,0,3));

        $splitter_2 = explode(" ", $name_before);
        $attacher_2 = implode("-", array_splice($splitter_2,0,3));

        if($name_before != '' && strtolower($attacher) == strtolower($attacher_2)){
            return null;
        }

        $randomizer = rand(100,999);
        $existing_id = $model_id ?? null;

        $slug = $attacher."-".$randomizer.$model_id;
        return strtolower($slug);
    }

    public function dashboardIndex(Request $request){
        $user = auth('sanctum')->user();
        if($user->is_admin == 0){
            return response()->json([
                'error'=>[
                    'status'=>403,
                    'message'=>'You dont have ability to access agenda index!'
                ]
            ],403);
        }
        $filter = new AgendaFilter();
        $queryItems = $filter->transform($request); // ['nama kolom', 'operator ex : like, <, =', 'value']

        $includeDetails = $request->query('includeDetails');
        $agenda = Agenda::where($queryItems);

        if(isset($includeDetails) && $includeDetails == 1){
            $agenda = $agenda->with('agenda_detail');
        }

        return new AgendaCollection($agenda->paginate()->appends($request->query()));
    }

}
