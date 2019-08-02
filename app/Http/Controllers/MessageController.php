<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

use App\Http\Requests;
use App\Models\Message;
use App\Models\User;
use App\Models\Image;
use App\Models\Video;
use App\Http\Controllers\Controller;

use Auth;
use Storage;

class MessageController extends Controller
{

	/*
		Maps:
			Map breeders and customers with different icons at admin portal

		Comm:
			Build email function similar to mailwoman
			Build sms function

		Todo:
			Run chat server on php artisan serve
  */
  
  /**
   * Get the messages to show to the front emd
   *
   * @param  Thread $threadid
   * @return View
   */
  public function getMessages($threadId = '')
  {
    $chatPort = 9090;
    $userName = Auth::user()->name;
    $userId   = Auth::user()->id;

    // Customer
    if (Auth::user()->userable_type == 'App\Models\Customer') {
      $userType   = 'Customer';
      $otherName  = '';

      $threads = Message::where('customer_id', '=', $userId)
                ->orderBy('created_at', 'DESC')
                ->get()
                ->unique('breeder_id');

      $tampered = false;
      if ($threadId == '' && isset($threads[0])) {
        $tampered = true;
        $threadId = $threads[0]->breeder_id;
      }

      $messages = Message::where('customer_id', '=', $userId)
                ->where('breeder_id',  $threadId)
                ->orderBy('created_at', 'ASC')
                ->get();

      foreach($messages as $message) {
          if ($message->read_at == NULL) {
            $message->read_at = date('Y-m-d H:i:s');
            $message->save();
          }
      }

      if (!$tampered && sizeof($messages) <= 0 && sizeof($threads) > 0) {
        $user = User::where('id', $threadId)->first();
        if (!isset($user) || $user->userable_type != 'App\Models\Breeder')
          return Redirect::route('messages');
        $otherName = $user->name;
      }
      else if ($threadId != '') {
        $user = User::where('id', $threadId)->first();
        $otherName = $user->name;
      }

      return view(
        'user.customer.messages',
        compact(
          "chatPort",
          "userName",
          "userId",
          "userType",
          "threads",
          "threadId",
          "messages",
          "otherName"
        )
      );
    }

    // Breeder
    elseif (Auth::user()->userable_type == 'App\Models\Breeder') {
      $userType   = 'Breeder';
      $otherName  = '';

      $threads = Message::where('breeder_id', '=', $userId)
                ->orderBy('created_at', 'DESC')
                ->get()
                ->unique('customer_id');

      $tampered = false;
      if ($threadId == '' && isset($threads[0])) {	// no message selected
        $tampered = true;
        $threadId = $threads[0]->customer_id;	// get the 1st customer's id
      }

      // get the breeder's messages
      $messages = Message::where('breeder_id', '=', $userId)
                  ->where('customer_id',  $threadId)
                  ->orderBy('created_at', 'ASC')
                  ->get();

      foreach ($messages as $message) {
        if ($message->read_at == NULL) {
          $message->read_at = date('Y-m-d H:i:s');
          $message->save();
        }
      }


      if (!$tampered && sizeof($messages) <= 0 && sizeof($threads) > 0) {
        $user = User::where('id', $threadId)->first();
        if (!isset($user) || $user->userable_type == 'App\Models\Customer')
          return Redirect::route('messages');
        $otherName = $user->name;
      }
      else if ($threadId != '') {
        $user = User::where('id', $threadId)->first();
        $otherName = $user->name;
      }

      return view(
        'user.breeder.messages',
        compact(
          "chatPort",
          "userName",
          "userId",
          "userType",
          "threads",
          "threadId",
          "messages",
          "otherName"
        )
      );
    }
  }

  /**
   * Upload one media for a product and store it in the storage folder (not in DB yet)
   *
   * @param  Request $request
   * @return JSON
   */
  public function uploadMedia(Request $request)
  {

    if  ($request->hasFile('medium')) {
      $file = $request->file('medium');
    
      // check if the file had a problem in uploading
      if ($file->isValid()) {
        $fileExtension = $file->getClientOriginalExtension();

        // create media info object based on media type
        if($this->isImage($fileExtension)) {
          $mediaInfo = $this->createMediaInfo($fileExtension);
          $mediaInfo['mediaType'] = 'image';
        }
        else if ($this->isVideo($fileExtension)) {
          $mediaInfo = $this->createMediaInfo($fileExtension);
          $mediaInfo['mediaType'] = 'video';
        }
        else {
          return response()->json('Invalid file extension', 500);
        } 

        // store the media in the directory based on Media Info
        Storage::disk('public')->put(
          $mediaInfo['directoryPath'].$mediaInfo['filename'], file_get_contents($file)
        );

        // return a json in chat.js
        return response()->json([
          'media_type' => $mediaInfo['mediaType'],
          'media_url' => $mediaInfo['directoryPath'] . $mediaInfo['filename']
        ]);
      }
      else {
        return response()->json('Upload failed', 500);
      }
    }
    else {
      return response()->json('No files detected', 500);
    }
  }

	public function getBreederMessagesAdmin($threadId = ''){
		$chatPort = 9090;
    	$userName = Auth::user()->name;
    	$userId = Auth::user()->id;

		$userType = 'Breeder';
		$otherName = '';

		$threads = Message::where('admin_id', '=', $userId)
			->where('breeder_id','!=', 0)
			->where('customer_id','=',0)
			->orderBy('created_at', 'DESC')
			//->groupBy('breeder_id')
			->get()
			->unique('breeder_id');

		$tampered = false;
		if($threadId == '' && isset($threads[0])){
			$tampered = true;
			$threadId = $threads[0]->breeder_id;
		}

		$messages = Message::where('admin_id', '=', $userId)
			->where('breeder_id',  $threadId)
			->orderBy('created_at', 'ASC')
			->get();

		foreach($messages as $message){
			if($message->read_at == NULL){
				$message->read_at = date('Y-m-d H:i:s');
				$message->save();
			}
		}

		if(!$tampered && sizeof($messages) <= 0 && sizeof($threads) > 0){
			$user = User::where('id', $threadId)->first();
			if(!isset($user) || $user->userable_type != 'App\Models\Breeder')
				return Redirect::route('messages');
			$otherName = $user->name;
		}else if($threadId != ''){
			$user = User::where('id', $threadId)->first();
			$otherName = $user->name;
		}

		return view('user.admin.messages', compact("chatPort", "userName", "userId", "userType", "threads", "threadId", "messages", "otherName"));

	}

	public function getCustomerMessagesAdmin($threadId = ''){
		$chatPort = 9090;
    	$userName = Auth::user()->name;
    	$userId = Auth::user()->id;

		$userType = 'Customer';
		$otherName = '';

		$threads = Message::where('admin_id', '=', $userId)
			->where('customer_id','!=', 0)
			->where('breeder_id','=',0)
			->orderBy('created_at', 'DESC')
			//->groupBy('breeder_id')
			->get()
			->unique('customer_id');

		$tampered = false;
		if($threadId == '' && isset($threads[0])){
			$tampered = true;
			$threadId = $threads[0]->customer_id;
		}

		$messages = Message::where('admin_id', '=', $userId)
			->where('customer_id',  $threadId)
			->orderBy('created_at', 'ASC')
			->get();


		foreach($messages as $message){
			if($message->read_at == NULL){
				$message->read_at = date('Y-m-d H:i:s');
				$message->save();
			}
		}

		if(!$tampered && sizeof($messages) <= 0 && sizeof($threads) > 0){
			$user = User::where('id', $threadId)->first();
			if(!isset($user) || $user->userable_type != 'App\Models\Customer')
				return Redirect::route('messages');
			$otherName = $user->name;
		}else if($threadId != ''){
			$user = User::where('id', $threadId)->first();
			$otherName = $user->name;
		}

		return view('user.admin.messages', compact("chatPort", "userName", "userId", "userType", "threads", "threadId", "messages", "otherName"));
	}

  /**
   * Count unread messages depending on the user type
   * @return Integer sizeof($count)
   */ 
  public function countUnread()
  {
    $userId = Auth::user()->id;
    if (Auth::user()->userable_type == 'App\Models\Customer') {
      $count = Message::where('customer_id', '=', $userId)
              ->where('read_at', NULL)
              ->where('direction', 1) //from breeder to customer
                ->orderBy('created_at', 'ASC')
                ->groupBy('breeder_id')
                ->get();
    }
    else if (Auth::user()->userable_type == 'App\Models\Breeder') {
      $count = Message::where('breeder_id', '=', $userId)
              ->where('read_at', NULL)
              ->where('direction', 0) //from customer to breeder
              ->orderBy('created_at', 'ASC')
              ->groupBy('customer_id')
              ->get();
    }
    else {
      $count = Message::where('admin_id', '=', $userId)
              ->where('read_at', NULL)
              ->where('direction', 0) //from customer to breeder
              ->orderBy('created_at', 'ASC')
              ->groupBy('customer_id')
              ->get();
    }

    return sizeof($count);
  }

  /**
     * Get appropriate media info depending on extension
     *
     * @param  String           $extension
     * @return AssociativeArray $mediaInfo
     */
  private function createMediaInfo($extension)
  {
    $mediaInfo = [];

    /* change the file name for better security */
    $mediaInfo['filename'] = '_message_'
      . md5(time())
      . '_'
      . $extension;

    /* create mediaInfo object based on media type */
    if ($this->isImage($extension)) {
      $mediaInfo['directoryPath'] = '/images/message/';
      $mediaInfo['type'] = new Image;
    }
    else if ($this->isVideo($extension)) {
      $mediaInfo['directoryPath'] = '/videos/message/';
      $mediaInfo['type'] = new Video;
    }

    return $mediaInfo;
  }

  /**
   * Check if media is Image depending on extension
   *
   * @param  String   $extension
   * @return Boolean
   */
  private function isImage($extension)
  {
      return (
        $extension == 'jpg'   ||
        $extension == 'jpeg'  ||
        $extension == 'png'
      ) ? true : false;
  }

  /**
   * Check if media is Video depending on extension
   *
   * @param  String   $extension
   * @return Boolean
   */
  private function isVideo($extension)
  {
      return (
        $extension == 'mp4' ||
        $extension == 'mkv' ||
        $extension == 'avi' ||
        $extension == 'flv'
      ) ? true : false;
  }

}
