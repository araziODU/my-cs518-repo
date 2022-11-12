<html>
	<div id="haut">
		<ul class="menuhaut">
			<li><a href="adminProfile.php">My Profile</a></li>
			<li><a href="approveNewUsers.php">New Users Approval</a></li>
			<li><a href="allApprovedUsers.php">All Approved Users</a></li>
			<li><a href="changeAdminPassword.php">Change Password</a></li>
			<li><a href="assignTask.php">Assign Tasks</a></li>
			<li><a href="viewAllTasks.php">View All Tasks</a></li>
            <li><a href="logout.php">Logout</a></li>

			<form action="search.php" method="GET">
				<input type="text" placeholder="Search.." name="query" />
				<input type="submit" name="action" value="Search" />
				<input type="button"  name="action"  value="SearchAnnotationTasks" />
			</form>	
			</li>
		</ul>
	</div>
</html>