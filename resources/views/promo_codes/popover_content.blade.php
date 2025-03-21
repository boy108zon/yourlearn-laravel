<table class="table table-sm table-bordered mb-0 w-100">
    <tbody>
        <tr>
            <td><strong>Remaining Time</strong></td>
            <td>{{ $remainingDays }}</td>
        </tr>
        <tr>
            <td><strong>Total Product Uses</strong></td>
            <td>{{ $promocode->products()->count() }}</td>
        </tr>
        <tr>
            <td><strong>Used In Orders</strong></td>
            <td>{{$usageCountInOrders }}  times.</td>
        </tr>
        
    </tbody>
</table>
