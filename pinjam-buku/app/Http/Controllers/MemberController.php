<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MemberRequest;
use App\Services\MemberService;

class MemberController extends Controller
{
    public function index() {
        $service = new MemberService;
        return  response()->json( $service->getAll(), 200);
    }

    public function create(MemberRequest $request) {
        $member = (new MemberService)->create($request->name);
        return response()->json( $member, 201 );
    }
}
