<div class="wrap" ng-app="admin" ng-controller="AdminCtrl">
	<div class="error" ng-show="test.error">{{test.error}}</div>
	<h2>AB Testing Admin <img ng-show="test.isLoading" src="<?php echo admin_url('images/spinner.gif'); ?>"></h2>

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
				<td>
					<span ng-hide="t.isEditMode">{{t.name}}</span>
					<input type="text" ng-model="t.name" ng-show="t.isEditMode" placeholder="Name"/>
				</td>
				<td>
					<span ng-hide="t.isEditMode">{{t.description}}</span>
					<input type="text" ng-model="t.description" ng-show="t.isEditMode" placeholder="Description"/>
				</td>
				<td>
					<span ng-hide="t.isEditMode">{{t.cases}}</span>
					<input type="text" ng-model="t.cases" ng-show="t.isEditMode" placeholder="Cases"/>
				</td>
				<td>
					<button class="button-secondary" ng-click="t.isEditMode = true;" ng-hide="t.isEditMode" ng-disabled="test.isLoading">Edit</button>
					<button class="button-secondary" ng-click="t.save(test)" ng-show="t.isEditMode" ng-disabled="test.isLoading">Save</button>
					<button class="button-secondary" ng-click="test.setPreview(t)" ng-disabled="test.isLoading">Get Code</button>
					<button class="button-secondary" ng-click="t.remove(test)" ng-disabled="test.isLoading">Remove</button>
				</td>
			</tr>

			<tr>
				<td></td>
				<td><input type="text" class="large-text" placeholder="Name" ng-model="test.form.name"></td>
				<td><input type="text" class="large-text" placeholder="Description" ng-model="test.form.description"></td>
				<td><input type="text" class="large-text" placeholder="Comma separated cases" ng-model="test.form.cases"></td>
				<td><button class="button-primary" ng-click="test.add(test.form);" ng-disabled="test.isLoading">Add new test</button></td>
			</tr>
		</tbody>
	</table>


	<h3>Google Analytics</h3>

	<label>
		<input type="checkbox" ng-model="test.settings.isGaUniversal" ng-change="test.save()" ng-disabled="test.isLoading" ng-true-value="true" ng-false-value="false">
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

