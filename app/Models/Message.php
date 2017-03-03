<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Message extends Model
{

	protected $fillable = ['customer_id', 'breeder_id', 'message', 'direction', 'read_at'];

	public function sender(){
		if($this->direction == 0){
			return User::where('id', $this->customer_id)->first()->name;
		}
		else{
			return User::where('id', $this->breeder_id)->first()->name;
		}
	}

	public function otherparty(){
		if(Auth::user()->userable_type == 'App\Models\Customer'){
			return User::where('id', $this->breeder_id)->first()->name;
		}
		else{
			return User::where('id', $this->customer_id)->first()->name;
		}
	}

}
