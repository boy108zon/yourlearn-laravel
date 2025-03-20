<table class="table table-sm table-bordered mb-0">
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
            <td><strong>Redeem Count Promo Code</strong></td>
            <td>{{ $redeemCount }}</td>
        </tr>
        
    </tbody>
</table>
