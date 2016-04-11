<?php namespace App\Http\Controllers;

use DB;
use Input;
use Config;
use Session;
use Redirect;
use Operation;
use Validator;

/*
|--------------------------------------------------------------------------
| Operation Controller
|--------------------------------------------------------------------------
|
| This controller handle general operation (e.g.login logout) for every mode
| This controller also handle page render that doesn't fall into Audition,
| Confirmation or Registration controller
|
*/

class OperationController extends Controller{

  /**
   * Operation class instance
   *
   * @var \App\Confirmation
   */
  private $operation;

  /**
   * Construct new operation instance
   *
   * @return void
   */
  public function __construct(){
    $this->operation = new Operation;
  }

  /**
   * Login handler
   *
   * @return Redirection
   */
  public function login(){
    Session::flush();
    Session::regenerate();

    $sid = Input::get('sid');
    $nid = Input::get('nid');

    $validator = Validator::make(array(
      'sid' => $sid,
      'nid' => $nid
    ),array(
      'sid' => 'required|numeric',
      'nid' => 'required|numeric'
    ));

    if($validator->fails()){
      return Redirect::back()->with('error','รูปแบบข้อมูลไม่ถูกต้องหรือมีข้อมูลเป็นค่าว่าง');
    }

    if ($this->operation->authenticateUser($sid, $nid)) {
      switch(Config::get('applicationConfig.mode')){
        case 'confirmation':
          return Redirect::to('/confirm');
        break;
        case 'audition':
          return Redirect::to('/audition');
        break;
        case 'war':

        break;
        default:
          abort(503);
        break;
      }

        return Redirect::to('/');
    } else {
        return Redirect::back()->with('error','รหัสประจำตัวนักเรียนหรือรหัสประชาชนไม่ถูกต้อง');
    }
  }

  /**
   * Log the user out
   *
   * @return Redirection
   */
  public function logout(){
    $this->operation->logout();
    return Redirect::to('/');
  }

  /**
   * Render page for any user that already have club
   *
   * @return view
   */
  public function confirmed(){
    DB::table('confirmation')->where('national_id', Session::get('national_id'))->first();
    DB::table('audition')->where('national_id', Session::get('national_id'))->where('status', 1)->first();
    DB::table('registration')->where('national_id', Session::get('national_id'))->first();
  }
}
