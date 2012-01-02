$(document).ready(function() { 
	
	// Minimze toolbar
	$('#lp-minimize').click(function() {
		toolbarCollapse();
		
	});
	
	// Show queries
	$('#lp-queries').click(function() {
		toolbarExpand();
		$('#li3-perf-content div').hide();
		$('#li3-perf-queries').show();
	});
	
	// Show timers
	$('#lp-timing').click(function() {
		toolbarExpand();
		$('#li3-perf-content div').hide();
		$('#li3-perf-timing').show();
	});
	
	// Show variables
	$('#lp-variables').click(function() {
		toolbarExpand();
		$('#li3-perf-content div').hide();
		$('#li3-perf-vars').show();
	});
	
	// Show logs
	$('#lp-log').click(function() {
		toolbarExpand();
		$('#li3-perf-content div').hide();
		$('#li3-perf-log').show();
		$('#li3-perf-log div').show();
		
		$.get('/li3_perf/tail', function(data){
			$('#error-log').html(data);
		});
	});
	
	$.get('/li3_perf/tail', function(data){
		$('#error-log').html(data);
	});
	
});

function toolbarExpand() {
	$('#li3-perf-toolbar').css({
		'overflow': 'auto'
	});
	$('#li3-perf-toolbar').animate({
		height: '100%'
	});
}

function toolbarCollapse() {
	$('#li3-perf-toolbar').css({
		'overflow': 'hidden'
	});
	$('#li3-perf-toolbar').animate({
		height: '24px'
	});
}