@extends('layouts.master')

@section('content')
<h1 class="border-bottom border-success border-3">Orders</h1>

<ul class="nav nav-tabs mb-3" id="salesTab" role="tablist">
   <li class="nav-item" role="presentation">
    <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">Pending</button>
   </li>
   <li class="nav-item" role="presentation">
    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="true">Completed</button>
   </li>
</ul>

<div class="tab-content" id="salesTabContent">
   <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
      <div class="table-responsive">
         <table id="transactionTable" class="stripe hover">
            <thead>
               <tr>
                  <th width="5%"></th>
                  <th>Name</th>
                  <th>Source</th>
                  <th>Details</th>
                  <th>Status</th>
                  <th>Type</th>
                  <th>Date</th>
               </tr>
            </thead>
            <tbody>
<!--                @foreach (json_decode($pendings) as $pending)
               <tr>
                  <td></td>
                  <td>{{ !empty($pending->name) ? $pending->name : '' }}</td>
                  <td>{{ $pending->qty }}</td>
                  <td>
                     {{ $pending->qty > 1 ? \Illuminate\Support\Str::plural($pending->unit) : $pending->unit }}
                  </td>
                  <td></td>
                  <td></td>
               </tr>
               @endforeach -->
            </tbody>
         </table>
      </div>
   </div>

</div>

<script type="text/javascript">
$(document).ready(function(){
   $(function(){
      let currentTab = "{{ request()->get('tab') }}";

      if (currentTab != "") {
         $(".nav-link").removeClass("active");
         $(".tab-pane").removeClass("show active");

         $("#"+currentTab+"-tab").addClass("active");
         $("#"+currentTab).addClass("show active");
      }
   });

   $(".nav-link").click(function(){
      let currentTab = $(this).attr("aria-controls");

      window.history.replaceState(null, null, "?tab="+currentTab);
   });

   let transactionTable = $('#transactionTable').DataTable( {
         dom: 'Bfrtip',
         lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
         ],
         columns: [
            {
               className: 'dt-control',
               orderable: false,
               data: null,
               defaultContent: ''
            },
            { data: 'name' },
            { data: 'source' },
            { data: 'details' },
            { data: 'status' },
            { data: 'type' },
            { data: 'created' }
         ],
         order: [[5, 'desc']],
         buttons: [
            'pageLength', 
            {
               extend: 'copy',
               split: ['print', 'csv', 'excel'],
            },
            {
               extend: 'collection',
               text: 'New Order',
               buttons: [
                  {
                     text: 'Order in Retail Store',
                     action: function ( e, dt, node, config ) {
                        alert('Retail Store');
                     }
                  },
                  {
                     text: 'Order in Restaurant',
                     action: function ( e, dt, node, config ) {
                        alert('Restaurant');
                     }
                  }
               ]
            }
         ],
         drawCallback: function() {
            var api = this.api();
            var rowCount = api.rows({page: 'current'}).count();
            
            for (var i = 0; i < api.page.len() - (rowCount === 0? 1 : rowCount); i++) {
              $('#transactionTable tbody').append($("<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>"));
            }
         }
   });
});
</script>
@endsection