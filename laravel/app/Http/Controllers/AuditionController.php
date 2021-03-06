<?php namespace App\Http\Controllers;

use DB;
use Input;
use Config;
use Session;
use Redirect;
use Audition;
use Operation;

/*
|--------------------------------------------------------------------------
| Audition Controller
|--------------------------------------------------------------------------
|
| This controller handle both UI and backend for confirmation mode
|
|
*/

class AuditionController extends Controller{

  /**
   * Audition class instance
   *
   * @var \App\Audition
   */
  private $audition;

  /**
   * Construct new audition instance
   *
   * @return void
   */
  public function __construct(){
    $this->audition = new Audition();
  }

  /**
   * Show audition page
   *
   * @return view
   */
  public function showAuditionPage(){
    if(Operation::userLoggedIn()){
      if(Operation::haveClub(true)){
        //Already have club
        return Redirect::to('/confirmed');
      }else{
        //No club have accepted the user
        $available = $this->audition->getAuditionClub();
        $selected = $this->audition->getSelected();
        $pass = $this->audition->getAuditionPassed();
        return view('audition')->with('data',array(
          'available' => $available,
          'selected' => $selected,
          'pass' => $pass
        ));
      }
    }else{
      return Redirect::to('/login');
    }
  }

  /**
   * Call audition model to add user to audition queue
   *
   * @return Redirection
   */
  public function addUserToQueue(){
    if(Operation::userLoggedIn()){
      if(!Operation::haveClub(true)){
        $club_code = Input::get('club_code');
        $add = $this->audition->addUserToQueue($club_code);
        if($add === true){
          return Redirect::to('/audition');
        }else{
          return Redirect::to('/audition')->with('error', $add);
        }
      }else{
        return Redirect::to('/confirmed')->with('error', 'นักเรียนเลือกชมรมแล้ว ไม่สามารถเพิ่มข้อมูลได้');
      }
    }else{
      return Redirect::to('/login');
    }
  }

  /**
   * Call audition model to remove user from audition queue
   *
   * @return Redirection
   */
  public function removeUserFromQueue(){
    if(Operation::userLoggedIn()){
      if(Audition::havePendingAudition()){
        $club_code = Input::get('club_code');
        $remove = $this->audition->removeUserFromQueue($club_code);
        if($remove === true){
          return Redirect::to('/audition');
        }else{
          return Redirect::to('/audition')->with('error', $remove);
        }
      }else{
        return Redirect::to('/confirmed')->with('error', 'นักเรียนยังไม่ได้เลือกชมรม ไม่สามารถลบข้อมูลได้');
      }
    }else{
      return Redirect::to('/login');
    }
  }

  /**
   * Call audition model to confirm user
   *
   * @return Redirection
   */
  public function confirmUser(){
    if(Operation::userLoggedIn()){
      $club_code = Input::get('club_code');
      $insert = DB::table('audition')
                  ->where('national_id', Session::get('national_id'))
                  ->where('club_code', $club_code)
                  ->where('status', 1)
                  ->where('year', Config::get('applicationConfig.operation_year'))
                  ->first();
      if(!is_null($insert)){
        $confirm = $this->audition->confirmAudition($club_code);
        if($confirm === true){
          return Redirect::to('/audition');
        }else{
          return Redirect::to('/audition')->with('error', $confirm);
        }
      }else{
        abort(400, "Illegel Audition confirm");
      }
    }else{
      return Redirect::to('/login');
    }
  }
}
