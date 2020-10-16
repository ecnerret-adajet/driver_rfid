<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\ChangeOriginTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use App\ChangeOrigin;
use App\ApprovalType;

class ChangeOriginApiController extends Controller
{

    /**
     * Display approval types
     */
    public function approvalTypes()
    {
        $approvals = ApprovalType::orderBy('id','desc')->get();

        return $approvals;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $changeOrigns = ChangeOrigin::orderBy('id','desc')->get();

        $manager = new Manager();
        $resource = new Collection($changeOrigns, new ChangeOriginTransformer());
        return  $manager->createData($resource)->toArray();

        return $changeOrigns;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    /**
     * Approval process
     */
    public function approval(Request $request, ChangeOrigin $changeOrigin)
    {
        $this->validate($request, [
            'approval_type_id' => 'required'
        ]);

        $changeOrigin->approval_type_id= $request->approval_type_id;
        $changeOrigin->approval_remarks = $request->approval_remarks;
        $changeOrigin->save();

        return $changeOrigin;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
