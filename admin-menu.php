<div class="wrap">
	<h2>AB Testing Admin</h2>

	<h3>AB Tests</h3>
	<table class="widefat">
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Description</th>
				<th>Cases</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td>Contact us button</td>
				<td>red and blue contact button</td>
				<td>Red, Blue</td>
				<td></td>
			</tr>

			<tr>
				<td></td>
				<td><input type="text" class="large-text" placeholder="New case"></td>
				<td><input type="text" class="large-text" placeholder="Description"></td>
				<td><button class="button-primary">Add new case</button></td>
			</tr>
		</tbody>
	</table>


	<h3>Google Analytics</h3>


	<label>
		<input type="checkbox">
		I am using the new Google Analytics universal code
	</label>

	<h3>Sample Code</h3>
	<h4>Shortcode</h4>
	<pre>
[abtest test="1" case="red"]
<?php echo htmlentities('<a href="/sample-page" onclick="abTestEvent(1,\'red\')"></a>') ?>

[/abtest]
</pre>
<h4>PHP</h4>
<pre>
<?php echo htmlentities('<?php if(abtest(1,\'red\')): ?>') ?>

<?php echo htmlentities('<a href="/sample-page" onclick="abTestEvent(1,\'red\')"></a>') ?>

<?php echo htmlentities('<?php endif; ?>') ?>
	</pre>
		

</div>

