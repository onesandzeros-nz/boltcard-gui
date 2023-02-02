
<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<% with Card %>
		<h1>$card_name Payments</h1>

		<table>
			<tr>
				<th>lnurlw_request_time</th>
				<th>amount_msats</th>
				<th>paid_flag</th>
				<th>payment_time</th>
				<th>payment_status</th>
				<th>failure_reason</th>
				<th>payment_status_time</th>
			</tr>

			<% loop Payments.Sort('lnurlw_request_time DESC') %>
				<tr>
					<td>$lnurlw_request_time.Nice ($lnurlw_request_time.Ago)</td>
					<td>$amount_msats</td>
					<td>$paid_flag</td>
					<td>$payment_time.Nice</td>
					<td>$payment_status</td>
					<td>$failure_reason</td>
					<td>$payment_status_time.Nice</td>

				</tr>

			<% end_loop %>		
		</table>
	<% end_with %>		
</div>