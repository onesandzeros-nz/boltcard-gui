<% include SideBar %>
<div class="content-container unit size3of4 lastUnit">
	<% with Card %>

		<h1>$card_name</h1>

		<table>
			<tr><th>uid</th><td>$uid</td></tr>
			<tr><th>last_counter_value</th><td>$last_counter_value</td></tr>
			<tr><th>lnurlw_request_timeout_sec</th><td>$lnurlw_request_timeout_sec</td></tr>
			<tr><th>lnurlw_enable</th><td>$lnurlw_enable</td></tr>
			<tr><th>tx_limit_sats</th><td>$tx_limit_sats</td></tr>
			<tr><th>day_limit_sats</th><td>$day_limit_sats</td></tr>
			<tr><th>lnurlp_enable</th><td>$lnurlp_enable</td></tr>
			<tr><th>email_address</th><td>$email_address</td></tr>
			<tr><th>email_enable</th><td>$email_enable</td></tr>
			<tr><th>uid_privacy</th><td>$uid_privacy</td></tr>
			<tr><th>one_time_code</th><td>$one_time_code</td></tr>
			<tr><th>one_time_code_expiry</th><td>$one_time_code_expiry</td></tr>
			<tr><th>one_time_code_used</th><td>$one_time_code_used</td></tr>
			<tr><th>allow_negative_balance</th><td>$allow_negative_balance</td></tr>
			<tr><th>wiped</th><td>$wiped</td></tr>
		</table>

	<% end_with %>		
</div>