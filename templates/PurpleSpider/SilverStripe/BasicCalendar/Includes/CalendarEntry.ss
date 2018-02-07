<div class="calendarentry clearfix">

	<a name="$ID"></a>
	<h3>$Title</h3>

	<% if Image %>
		<a href="$Image.ScaleWidth(900).URL" class="lightbox">
			<% loop Image.ScaleWidth(150) %><img class="right" src="$URL" width="$Width" height="$Height" /><% end_loop %>
		</a>
	<% end_if %>

	<p class="date"><% if TBC %>TBC<% else_if NoDay %> <% else_if Date %><strong>$Date.Month()&nbsp;$Date.DayOfMonth(true)</strong>,&nbsp;$Date.DayOfWeek()&nbsp;<% end_if %><% if Time %> - $Time<% end_if %></p>

	<% if Description %>
		<p>$Description</p>
	<% end_if %>

	<div class="hr"></div>

</div>