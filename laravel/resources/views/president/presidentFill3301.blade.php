@extends('layout.main')

@section('content')
<form class="form-horizontal" method="POST" action="/president/fm3301.do">
  <fieldset>
   <legend>Download FM33-01 สำหรับชมรม {{ Session::get('fullname') }}</legend>
   @include('layout.component.errornobg')
   <div class="form-group">
     <label class="col-lg-2 control-label">ชื่อประธานชมรม</label>
     <div class="col-lg-2">
       <select class="form-control" id="presidentTitle" name="presidentTitle">
         <option>นาย</option>
         <option>นางสาว</option>
         <option>ด.ช.</option>
         <option>ด.ญ.</option>
       </select>
     </div>
     <div class="col-lg-4">
       <input type="text" class="form-control" id="presidentFirstName" name="presidentFirstName" placeholder="ชื่อ">
     </div>
     <div class="col-lg-4">
       <input type="text" class="form-control" id="presidentLastName" name="presidentLastName" placeholder="นามสกุล">
     </div>
   </div>

   <div class="form-group">
     <label class="col-lg-2 control-label">ชื่อประธานที่ปรึกษาชมรม</label>
     <div class="col-lg-2">
       <select class="form-control" id="adviserTitle" name="adviserTitle">
         <option>นาย</option>
         <option>นางสาว</option>
         <option>นาง</option>
       </select>
     </div>
     <div class="col-lg-4">
       <input type="text" class="form-control" id="adviserFirstName" name="adviserFirstName" placeholder="ชื่อ">
     </div>
     <div class="col-lg-4">
       <input type="text" class="form-control" id="adviserLastName" name="adviserLastName" placeholder="นามสกุล">
     </div>
   </div>
   {{ csrf_field() }}
   <br />

   <div class="form-group">
     <div class="col-lg-10 col-lg-offset-2">
       <button type="submit" class="btn btn-success btn-block">Download</button>
     </div>
   </div>
  </fieldset>
</form>

@stop