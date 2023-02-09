<nav class="primary">
	<span class="nav-open-button">²</span>
	<ul>
		<% loop $Menu(1) %>
			<li class="$LinkingMode"><a href="$Link" title="$Title.XML">$MenuTitle.XML</a></li>
		<% end_loop %>
		<% with SettingsController %>
		<li class="$LinkingMode"><a href="$Link" title="Settings">Settings</a></li>
		<% end_with %>
	</ul>
</nav>
