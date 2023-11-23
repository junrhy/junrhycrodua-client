@extends('layouts.master')

@section('content')
<h1 class="border-bottom border-success border-3">Sales</h1>

<ul class="nav nav-tabs mb-3" id="salesTab" role="tablist">
   <li class="nav-item" role="presentation">
    <button class="nav-link active" id="transaction-tab" data-bs-toggle="tab" data-bs-target="#transaction" type="button" role="tab" aria-controls="transaction" aria-selected="true">Transactions</button>
   </li>
</ul>

<div class="tab-content" id="salesTabContent">
   <div class="tab-pane fade show active" id="transaction" role="tabpanel" aria-labelledby="transaction-tab">
      <div class="table-responsive">
         <table id="transactionTable" class="stripe hover">
            <thead>
               <tr>
                  <th width="5%"></th>
                  <th>Product</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Amount</th>
                  <th>Date</th>
               </tr>
            </thead>
            <tbody>
               @foreach (json_decode($transactions) as $transaction)
               <tr>
                  <td></td>
                  <td>{{ !empty($transaction->name) ? $transaction->name : '' }}</td>
                  <td>{{ $transaction->qty }}</td>
                  <td>
                     {{ $transaction->qty > 1 ? \Illuminate\Support\Str::plural($transaction->unit) : $transaction->unit }}
                  </td>
                  <td></td>
                  <td></td>
               </tr>
               @endforeach
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
            { data: 'qty' },
            { data: 'unit' },
            { data: 'amount' },
            { data: 'created' }
         ],
         order: [[5, 'desc']],
         buttons: [
            'pageLength', 
            {
               extend: 'copy',
               split: ['print', 'csv', 'excel'],
            }
         ],
         drawCallback: function() {
            var api = this.api();
            var rowCount = api.rows({page: 'current'}).count();
            
            for (var i = 0; i < api.page.len() - (rowCount === 0? 1 : rowCount); i++) {
              $('#transactionTable tbody').append($("<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>"));
            }
         }
   });
});
</script>
@endsection