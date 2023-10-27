@extends('layouts.master')

@section('content')
   
   <h1 class="mt-3 mb-3 border-bottom border-info">Orders</h1>
   <button type="button" class="btn btn-primary rounded-0" data-toggle="modal" data-target="#orderModal">New Order</button>
   <div class="table-responsive">
      <table class="table table-hover">
         <thead>
            <th scope="col">col</th>
            <th scope="col" width="30%">Actions</th>
         </thead>
         <tbody>
            <tr>
               <td>value 1</td>
               <td>
                  <i class="fa fa-pencil"></i> Edit
                  <i class="fa fa-trash"></i> Remove
               </td>
            </tr>
            <tr>
               <td>value 2</td>
               <td>
                  <i class="fa fa-pencil"></i> Edit
                  <i class="fa fa-trash"></i> Remove
               </td>
            </tr>
            <tr>
               <td>value 3</td>
               <td>
                  <i class="fa fa-pencil"></i> Edit
                  <i class="fa fa-trash"></i> Remove
               </td>
            </tr>
         </tbody>
      </table>
   </div>
@endsection