(function(angular){
	angular.module('admin',[])
	.factory('TestItem',function(){
		var TestItem = function(id,fields){
			this.id = id;
			this.name = fields.name;
			this.description = fields.description;
			this.cases = fields.cases;
			this.getCases = function(){
				var arr = this.cases.split(',');
				var cases = [];
				angular.forEach(arr,function(v){
					cases.push(angular.element.trim(v));
				});
				return cases;
			};
		}
		return {
			obj:TestItem
		};
	})
	.controller('AdminCtrl',['$scope','$http','TestItem',function($scope,$http,TestItem){


		$scope.test = {
			list:[],
			preview:null,
			form:{
				id:0,
				name:'',
				description:'',
				cases:''
			},
			settings:{
				isGaUniversal:false
			},
			clearForm:function(){
				this.form.id = 0;
				this.form.name = '';
				this.form.description = '';
				this.form.cases = '';
			},
			add:function(newFields){
				if(this.validate()){
					var t = new TestItem.obj(this.getNextId(),newFields);
					this.list.push(t);
					this.clearForm();
					this.save();
				}
				
			},
			validate:function(){

				if(angular.element.trim(this.form.name)=='') return false;
				if(angular.element.trim(this.form.description)=='') return false;
				if(angular.element.trim(this.form.cases)=='') return false;

				return true;
			},
			getNextId:function(){
				var max = 0;
				angular.forEach(this.list,function(v){
					if(v.id>max){
						max = v.id;
					}
				});
				return max+1;
			},
			setPreview:function(prev){
				this.preview = prev;
				// to show thickbox
				tb_show('Sample Code', '#TB_inline?width=600&height=550&inlineId=sample-code');
			},
			getPreviewShortcode:function(){
				if(!this.preview) return '';
				var str = '';
				$this = this;
				angular.forEach(this.preview.getCases(),function(v){
					str+='[abtest test="'+$this.preview.id+'" case="'+v+'"]\n';
					str+='\t<a href="/sample-page" onclick="abTestEvent('+$this.preview.id+',\''+v+'\')"></a>\n';
					str+='[/abtest]\n';

				});

				return str;
			},
			getPreviewPHP:function(){
				if(!this.preview) return '';
				var str = '';
				$this = this;
				angular.forEach(this.preview.getCases(),function(v){
					str+='<?php if(abtest('+$this.preview.id+',\''+v+'\')): ?>\n';
					str+='\t<a href="/sample-page" onclick="abTestEvent('+$this.preview.id+',\''+v+'\')"></a>\n';
					str+='<?php endif; ?>\n';

				});

				return str;
			},
			save:function(){
				$this = this;
				$http.post(ajaxurl+'?action=abtest_save',{
					list:$this.list,
					settings:$this.settings
				});
			}

		};




	}]);
})(angular);

