<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Borrow;

class MemberService {

    public  function create($name) {
        $member = new Member;
        $member->code = $this->generateCode();
        $member->name = $name;
        $member->save();
        return $member;
    }

    public  function generateCode() {
        $member = Member::all();

        if($member == null) {
            return 'M001';
        }

        return 'M00' . (sizeof($member) + 1 );
    }

    public  function getAll() {
        $members = Member::get();
        $borrowed = Borrow::where('return_at', null)->get();
        $datas = [];

        foreach($members as $member) {
            $tmp = [
                'id' => $member->id,
                'code' => $member->code,
                'name' => $member->name,
                'borrowed_book' => 0
            ];

            foreach($borrowed as $b) {
                if($member->id == $b->member_id) {
                    $tmp['borrowed_book'] = 1;
                }
            }

            array_push($datas, (object)$tmp);
        } 

        return $datas;
    }

}