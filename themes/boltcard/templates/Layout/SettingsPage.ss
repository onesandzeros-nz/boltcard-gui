<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<h1>$Title</h1>
		<div class="content">
			<% if not certExists || not macaroonExists %>
			<p style="color:#b80000;">
				<strong>
					<% if not certExists %>
					Add the tls.cert file <br/>
					<% end_if %>
					<% if not macaroonExists %>
					Add the admin.macaroon file
					<% end_if %>
				</strong>
			</p>
			<% end_if %>
			<table>
				<% loop Settings %>
				<tr>
					<td>$name</td>
					<td>$value</td>
				</tr>
				<% end_loop %>
			</table>
			<p><a class="btn btn-primary" href="settings/edit">Edit</a></p>
		</div>
		
	</article>
		$Form
		$CommentsForm
</div>