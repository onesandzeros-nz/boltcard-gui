<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<h1>Cards List</h1>
		<table>
			<thead>
				<tr>
					<th>Card ID</th>
					<th>Card Name</th>
					<th>Payments</th>
					<th>Total</th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<% loop Cards %>
				<tr>
					<td>$card_id</td>
					<td>$card_name</td>
					<td>$Payments.Count</td>
					<td>$PaymentsTotal</td>
					<td><a href="CardController/view/$card_id">Details</a></td>
					<td><a href="CardController/payments/$card_id">Payments</a></td>
					<td><a href="CardController/wipe/$card_id">Wipe</a></td>
				</tr>
			<% end_loop %>
			</tbody>
		</table>
		<a href="CardController/createcard" class="btn btn-primary">Create Bolt Card</a>
		<a href="CardController/wipecard" class="btn btn-primary">Wipe Bolt Card</a>

	</article>
</div>
