<div class="wrap" ng-app="admin" ng-controller="AdminCtrl">
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
			<tr ng-repeat="t in test.list">
				<td>{{t.id}}</td>
				<td>{{t.name}}</td>
				<td>{{t.description}}</td>
				<td>{{t.cases}}</td>
				<td>
					<button class="button-secondary">Edit</button>
					<button class="button-secondary" ng-click="test.setPreview(t)">Get Code</button>
				</td>
			</tr>

			<tr>
				<td></td>
				<td><input type="text" class="large-text" placeholder="Name" ng-model="test.form.name"></td>
				<td><input type="text" class="large-text" placeholder="Description" ng-model="test.form.description"></td>
				<td><input type="text" class="large-text" placeholder="Comma separated cases" ng-model="test.form.cases"></td>
				<td><button class="button-primary" ng-click="test.add(test.form);">Add new test</button></td>
			</tr>
		</tbody>
	</table>


	<h3>Google Analytics</h3>


	<label>
		<input type="checkbox" ng-model="test.settings.isGaUniversal">
		I am using the new Google Analytics universal code
	</label>

	<?php add_thickbox(); ?>
	<div id="sample-code" style="display:none;">
		<h3 ng-show="test.preview">Sample code for "{{test.preview.name}}" AB test</h3>

		<h4 ng-show="test.preview">Shortcode</h4>
		<pre>{{test.getPreviewShortcode()}}</pre>

		<h4 ng-show="test.preview">PHP</h4>
		<pre>{{test.getPreviewPHP()}}</pre>
	</div>

		

</div>

