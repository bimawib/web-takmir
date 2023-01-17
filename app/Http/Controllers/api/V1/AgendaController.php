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
        $request['user_id'] = 13; // auth('sanctum')->user()->id;
        $request['published_at'] = now();

        $request_detail = $request->agendaDetail;
        // return $detail[0]['agendaName'];
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
        
        return new AgendaResource($with_detail);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function show(Agenda $agenda)
    {
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
        
        $request['slug'] = $agenda->slug;

        $detail = AgendaDetail::where('agenda_id',$agenda->id)->get();

        $request_detail = $request->agendaDetail;

        $is_valid = $this->detailValidator($request_detail);

        $last_request_detail = array_key_last($request_detail);
        $last_unupdated_detail = array_key_last($detail->toArray());

        if($is_valid != "success"){
            return $is_valid;
        }

        $agenda->update($request->all());

        if($last_request_detail < $last_unupdated_detail){
            AgendaDetail::where('agenda_id',$agenda->id)->where('id','>',$detail[$last_request_detail]->id)->delete();
        }
        
        $detail_after = AgendaDetail::where('agenda_id',$agenda->id)->get();
        $kt = 0;
        foreach($detail_after as $da){
            AgendaDetail::where('agenda_id',$agenda->id)->where('id',$detail_after[$kt]->id)->update([
                'agenda_id'=>$agenda->id,
                'agenda_name'=>$request_detail[$kt]['agendaName'],
                'start_time'=>$request_detail[$kt]['startTime'],
                'end_time'=>$request_detail[$kt]['endTime'],
                'location'=>$request_detail[$kt]['location'],
                'keynote_speaker'=>$request_detail[$kt]['keynoteSpeaker'],
                'note'=>$request_detail[$kt]['note']
            ]);
            $kt++;
        }

        $new_detail = [];
        for($unreq = $last_unupdated_detail; $unreq < $last_request_detail; $unreq++){
            $new_detail[] = [
                'agenda_id'=>$agenda->id,
                'agenda_name'=>$request_detail[$unreq + 1]['agendaName'],
                'start_time'=>$request_detail[$unreq + 1]['startTime'],
                'end_time'=>$request_detail[$unreq + 1]['endTime'],
                'location'=>$request_detail[$unreq + 1]['location'],
                'keynote_speaker'=>$request_detail[$unreq + 1]['keynoteSpeaker'],
                'note'=>$request_detail[$unreq + 1]['note']
            ];
        }
        AgendaDetail::insert($new_detail);

        $with_detail = Agenda::where('slug',$agenda->slug)->with('agenda_detail')->first();
        
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
        //
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

}
