<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<h1>$Title</h1>
		<div class="content">$Content</div>
		<table>
			<thead>
				<tr>
					<th>Card ID</th>
					<th>Card Name</th>
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
					<td><a href="CardController/view/$card_id">Details</a></td>
					<td><a href="CardController/payments/$card_id">Payments</a></td>
					<td><a href="CardController/wipe/$card_id">Wipe</a></td>
				</tr>
			<% end_loop %>
			</tbody>
		</table>
		<a href="CardController/createcard" class="btn btn-primary">Create Bolt Card</a>

	</article>
		$Form
		$CommentsForm
</div>