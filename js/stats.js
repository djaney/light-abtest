function abTestEvent(id,testCase){
	var testName = false;
	var testCase = testCase || false;



		for(var i in abtest_tests){
			if(abtest_tests[i].id==id){
				testName = abtest_tests[i].name;
				break;
			}
		}
		if(testName && testCase && abtest_settings){

			var isUniversal = abtest_settings.isGaUniversal || false;
			if(isUniversal){
				if(typeof ga=='function'){
					ga('send', 'event', 'Light AB Testing', testName, testCase);
				}else{
					console.log('no google analytics: ga()','send', 'event', 'Light AB Testing', testName, testCase);
				}
			}else{
				if(typeof _gaq=='object'){
					_gaq.push(['_trackEvent', 'Light AB Testing', testName, testCase]);
				}else{
					console.log('no google analytics: _gaq.push()', 'Light AB Testing', testName, testCase);
				}
				
			}

			
		}else{
			console.error('missing variables and files',testName,testCase,abtest_settings);
		}
	
}
