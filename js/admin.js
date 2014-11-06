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
			this.save = function(test){


				if(test.validate(this)){
					
					this.isLoading = true;
					var $item = this;
					test.save(function(){
						$item.isEditMode = false;
						$item.isLoading = false;
					});
				}
			}
			this.remove = function(test){

				if(!confirm('Test will be removed permanently')) return;

				var idx = test.list.indexOf(this);
				this.isLoading = true;
				var $item = this;
				if(idx>=0){
					test.list.splice(idx,1);
				}
				test.save(function(){
					if(idx>=0){
						test.list.splice(idx,1);
					}
					$item.isLoading = false;
				});
			}
		}
		return {
			obj:TestItem
		};
	})
	.controller('AdminCtrl',['$scope','$http','TestItem','$window','$timeout',function($scope,$http,TestItem,$window,$timeout){


		$scope.test = {
			list:[],
			preview:null,
			isLoading:false,
			form:{
				id:0,
				name:'',
				description:'',
				cases:''
			},
			error:'',
			settings:{
				isGaUniversal:false
			},
			resyncTimer:0,
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
			validate:function(frm){
				var form = frm || this.form;
				if(angular.element.trim(form.name)=='') return false;
				if(angular.element.trim(form.description)=='') return false;
				if(angular.element.trim(form.cases)=='') return false;

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
			getItemCases:function(){

			},
			getPreviewShortcode:function(){
				if(!this.preview) return '';
				var str = '';
				var $this = this;
				angular.forEach($this.preview.getCases(),function(v){
					str+='[abtest test="'+$this.preview.id+'" case="'+v+'"]\n';
					str+='\t<a href="/sample-page" onclick="abTestEvent('+$this.preview.id+',\''+v+'\')">Sample</a>\n';
					str+='[/abtest]\n';

				});

				return str;
			},
			getPreviewPHP:function(){
				if(!this.preview) return '';
				var str = '';
				var $this = this;
				angular.forEach($this.preview.getCases(),function(v){
					str+='<?php if(LightABTest::abtest('+$this.preview.id+',\''+v+'\')): ?>\n';
					str+='\t<a href="/sample-page" onclick="abTestEvent('+$this.preview.id+',\''+v+'\')">Sample</a>\n';
					str+='<?php endif; ?>\n';

				});

				return str;
			},
			save:function(cb){
				var $this = this;
				$this.isLoading = true;
				$http.post(ajaxurl+'?action=abtest_save',{
					list:$this.list,
					settings:$this.settings
				}).success(function(ret){
					$this.isLoading = false;
					if(typeof cb=='function') cb();
				}).error(function(){
					$this.resync();
					
				});
			},
			resync:function(){
				this.resyncTimer = 10;
				this.error = 'Connection lost, reconnecting in '+this.resyncTimer+'s';
				var $this = this;
				var cb = function(){
					$this.resyncTimer--;
					
					if($this.resyncTimer>0){
						$this.error = 'Connection lost, reconnecting in '+$this.resyncTimer+'s';
						$timeout(cb,1000);
					}else{
						$this.error = '';
						$this.save();
					}
					
				}
				$timeout(cb,1000);
			},
			load:function(){
				var $this = this;
				$this.isLoading = true;
				$http.get(ajaxurl+'?action=abtest_load')
				.success(function(ret){
					if(ret.success){
						$this.list.length = 0;
						angular.forEach(ret.data.list,function(l){
							$this.list.push(new TestItem.obj(l.id,l));
						});
						angular.forEach(ret.data.settings,function(v,k){
							$this.settings[k] = v;
							
						});

					}
					
					$this.isLoading = false;
				});
			},
			init:function(){
				var $this = this;
				$this.load();
			}

		};


		$scope.test.init();

	}]);
})(angular);

