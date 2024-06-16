<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\MemberService;
use App\Models\Borrow;
use Datetime;
use App\Models\Member;

class MemberTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_generate_first_member_code(): void
    {
        $this->assertTrue( ( new MemberService)->generateCode() == 'M001');
    }

    public function test_create_member() : void 
    {
        $member =  ( new MemberService)->create('lkok');
        $this->assertTrue($member->id > 0);
    }

    public function test_generate_second_member_code(): void
    {
        $member =  ( new MemberService)->create('cc');
        $this->assertTrue( ( new MemberService)->generateCode() == 'M002');
    }

    public function test_get_all_member() : void 
    {
        $delete = Member::get();
        
        foreach($delete as $d) {
            $d->delete();
        }

        $service = new MemberService;
        $member1 = $service->create('lkok');
        $member2 =  $service->create('kkk');

        $borrow = new Borrow;
        $borrow->book_id =  1;
        $borrow->member_id = $member1->id;
        $borrow->borrow_at =  new Datetime('2024-01-01 10:10:10');
        $borrow->return_at = null;
        $borrow->save();

        $members = $service->getAll();
        $this->assertTrue( isset($members[1]->id));
        $this->assertTrue( sizeof($members) > 0 );
        $this->assertTrue( $members[0]->borrowed_book == 1 );
        $this->assertTrue( $members[1]->borrowed_book == 0 );
    }

    
}
