<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<article>
		<h1>$Title</h1>
		<div class="content">
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