<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Http\Requests;
use App\Models\User;
use DB;
use Auth;

class Sessions extends Model
{

    protected $table = 'sessions';

	public $timestamps = false;

	// /**
	//  * Returns all the guest users.
	//  *
	//  * @param  \Illuminate\Database\Eloquent\Builder  $query
	//  * @return \Illuminate\Database\Eloquent\Builder
	//  */
	// public function scopeGuests($query)
	// {
	// 	return $query->whereNull('user_id');
	// }

	/**
	 * Returns all the registered users.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function getAllUserSessions()
	{
		return Sessions::all();
	}

	/**
	 * Updates the session of the current user.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function updateUserSession($id)
	{

        if(Sessions::find('user_id'=>$id)){
            dd("TRUE");
        }
	}

	/**
	 * Returns the user that belongs to this entry.
	 *
	 * @return \Cartalyst\Sentry\Users\EloquentUser
	 */
	public function user()
	{
		return $this->belongsTo('Cartalyst\Sentry\Users\EloquentUser'); # Sentry 3
		// return $this->belongsTo('Cartalyst\Sentry\Users\Eloquent\User'); # Sentry 2
	}


}
