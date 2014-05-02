<?php
    require_once(dirname(__FILE__)."/../common.php");
    if (!isset($loginSession))
        doUnauthenticatedRedirect();

    if (!$loginSession->isAdministrator)
        doUnauthorizedRedirect();
	
	
		$projectId = @$_GET['id'];
	try {
		$projectArray = ($db->readProjectChartEmployees($projectId));
		$projectOther = ($db->readProjectChartOther($projectId));
		$projectDepartments = ($db->readProjectChartProjects($projectId));
	} catch (Exception $ex) {
        handleDBException($ex);
        return;
	}

/*
 *	An administrator should be able to generate a report on each 
 *	project which should display the 
 *	people associated with the project and the total cost.
*/

?>
<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

  // Load the Visualization API and the piechart package.
  google.load('visualization', '1.0', {'packages':['corechart']});

  // Set a callback to run when the Google Visualization API is loaded.
  google.setOnLoadCallback(drawChart);
  google.setOnLoadCallback(drawChart2);

  // Callback that creates and populates a data table,
  // instantiates the pie chart, passes in the data and
  // draws it.
  
  function drawChart() {

	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Costs');
	data.addColumn('number', 'Dollars');
	data.addRows([
	
	].concat(<?= json_encode($projectOther) ?>)
	.concat(<?= json_encode($projectArray) ?>));

	// Set chart options
	var options = {'title':<?= json_encode($db->readProject($projectId)->name) ?> ,
				   'width':800,
				   'height':500};

	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	chart.draw(data, options);
  }
  
  function drawChart2() {

	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Costs');
	data.addColumn('number', 'Dollars');
	data.addRows([].concat(<?= json_encode($projectDepartments) ?>));

	// Set chart options
	var options = {'title':<?= json_encode($db->readProject($projectId)->name) ?>,
				   'width':800,
				   'height':500};

	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
	chart.draw(data, options);
  }
</script>

<div class="container col-md-6 col-md-offset-3">
	<legend>Employee Report</legend>
	<div id="chart_div"></div>
	<br>
	<legend>Department Report</legend>
	<div id="chart_div2"></div>
	<br>
<div>