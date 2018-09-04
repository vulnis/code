<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// Include required configuration files
require_once(realpath(__DIR__ . '/functions.php'));
require_once(realpath(__DIR__ . '/HighchartsPHP/Highchart.php'));


require_once(language_file());

// Include Zend Escaper for HTML Output Encoding
require_once(realpath(__DIR__ . '/Component_ZendEscaper/Escaper.php'));
$escaper = new Zend\Escaper\Escaper('utf-8');

/****************************
 * FUNCTION: GET OPEN RISKS *
 ****************************/
function get_open_risks($teams = false)
{
    if($teams !== false){
        if($teams == ""){
            $teams_query = " AND 0 ";
        }else{
            $options = explode(",", $teams);
            $teams_query = generate_or_query($options, 'team');
            $teams_query = " AND ( {$teams_query} ) ";
        }
    }else{
        $teams_query = "";
    }

    // If team separation is not enabled
    if (!team_separation_extra())
    {
        // Open the database connection
        $db = db_open();

        // Query the database
        $stmt = $db->prepare("SELECT id FROM `risks` WHERE status != \"Closed\" {$teams_query} ");
        $stmt->execute();

        // Store the list in the array
        $array = $stmt->fetchAll();

        // Close the database connection
        db_close($db);
    }
    // Otherwise team separation is enabled
    else
    {
        //Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Get the open risks stripped
        $array = strip_get_open_risks($teams);
    }

    return count($array);
}

/******************************
 * FUNCTION: GET CLOSED RISKS *
 ******************************/
function get_closed_risks($teams = false)
{
    if($teams !== false){
        if($teams == ""){
            $teams_query = " AND 0 ";
        }else{
            $options = explode(",", $teams);
            $teams_query = generate_or_query($options, 'team');
            $teams_query = " AND ( {$teams_query} ) ";
        }
    }else{
        $teams_query = "";
    }
    
    // If team separation is not enabled
    if (!team_separation_extra())
    {
        // Open the database connection
        $db = db_open();

        // Query the database
        $stmt = $db->prepare("SELECT id FROM `risks` WHERE status = \"Closed\" {$teams_query} ");
        $stmt->execute();

        // Store the list in the array
        $array = $stmt->fetchAll();

        // Close the database connection
        db_close($db);
    }
    // Otherwise team separation is enabled
    else    
    {
        //Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Get the closed risks stripped
        $array = strip_get_closed_risks($teams);
    }

    return count($array);
}

/****************************
 * FUNCTION: GET HIGH RISKS *
 ****************************/
function get_high_risks()
{
    $very_high_display_name = get_risk_level_display_name('Very High');
    $high_display_name      = get_risk_level_display_name('High');

    // Open the database connection
    $db = db_open();

    // Get the high risk level
    $stmt = $db->prepare("SELECT value FROM `risk_levels` WHERE name = :high_display_name OR name = :very_high_display_name ORDER BY value ASC");
    $stmt->bindParam(":high_display_name", $high_display_name, PDO::PARAM_STR);
    $stmt->bindParam(":very_high_display_name", $very_high_display_name, PDO::PARAM_STR);
    $stmt->execute();
    $array = $stmt->fetchAll();
    $high = $array[0]['value'];
    $veryhigh = $array[1]['value'];

    // Query the database
    $stmt = $db->prepare("SELECT a.calculated_risk, b.* FROM risk_scoring a LEFT JOIN risks b ON a.id = b.id WHERE status != \"Closed\" AND a.calculated_risk >= :high AND a.calculated_risk < :veryhigh");
    $stmt->bindParam(":high", $high, PDO::PARAM_STR, 4);
	$stmt->bindParam(":veryhigh", $veryhigh, PDO::PARAM_STR, 4);
    $stmt->execute();

    // Store the list in the array
    $array = $stmt->fetchAll();

    // Close the database connection
    db_close($db);

    // If team separation is enabled
    if (team_separation_extra())
    {
        //Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Strip out risks the user should not have access to
        $array = strip_no_access_risks($array);
    }

    return count($array);
}

/*********************************
 * FUNCTION: GET VERY HIGH RISKS *
 *********************************/
function get_veryhigh_risks()
{
    $very_high_display_name = get_risk_level_display_name('Very High');

    // Open the database connection
    $db = db_open();

    // Get the high risk level
    $stmt = $db->prepare("SELECT value FROM `risk_levels` WHERE name = :very_high_display_name");
    $stmt->bindParam(":very_high_display_name", $very_high_display_name, PDO::PARAM_STR);
    $stmt->execute();
    $array = $stmt->fetch();
    $veryhigh = $array['value'];

    // Query the database
    $stmt = $db->prepare("SELECT a.calculated_risk, b.* FROM risk_scoring a LEFT JOIN risks b ON a.id = b.id WHERE status != \"Closed\" AND a.calculated_risk >= :veryhigh");
    $stmt->bindParam(":veryhigh", $veryhigh, PDO::PARAM_STR, 4);
    $stmt->execute();

    // Store the list in the array
    $array = $stmt->fetchAll();

    // Close the database connection
    db_close($db);

    // If team separation is enabled
    if (team_separation_extra())
    {
            //Include the team separation extra
            require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

            // Strip out risks the user should not have access to
            $array = strip_no_access_risks($array);
    }

    return count($array);
}

/****************************
 * FUNCTION: GET RISK TREND *
 ****************************/
function get_risk_trend($title = null)
{
    $chart = new Highchart();
    $chart->includeExtraScripts();

    // Set the timezone to the one configured for SimpleRisk
    $chart->chart->time->useUTC = false;
    $chart->chart->time->timezone = get_setting("default_timezone");

    $chart->chart->type = "arearange";
    $chart->chart->zoomType = "x";
    $chart->title->text = $title;
    $chart->xAxis->type = "datetime";
    $chart->yAxis->title->text = null;
    $chart->yAxis->min = 0;
    $chart->tooltip = array(
        'crosshairs' => true,
        'shared' => true,
        'valueSuffix' => ' risk(s)'
    );
    $chart->legend->enabled = false;
        $chart->chart->renderTo = "risk_trend_chart";
        $chart->credits->enabled = false;
    $chart->plotOptions->series->marker->enabled = false;
    $chart->plotOptions->series->marker->lineWidth = "2";

    // These set the marker symbol when selected
    $chart->plotOptions->series->marker->symbol = "circle";
    $chart->plotOptions->series->marker->states->hover->enabled = true;
    $chart->plotOptions->series->marker->states->hover->fillColor = "white";
    $chart->plotOptions->series->marker->states->hover->lineColor = "black";
    $chart->plotOptions->series->marker->states->hover->lineWidth = "2";

    // Get the opened risks array by month
    $opened_risks = get_opened_risks_array("day");
    $open_date = empty($opened_risks[0]) ? [] : $opened_risks[0];
    $open_count = empty($opened_risks[1]) ? [] : $opened_risks[1];

    // Get the closed risks array by month
    $closed_risks = get_closed_risks_array("day");
    $close_date = empty($closed_risks[0]) ? [] : $closed_risks[0];
    $close_count = empty($closed_risks[1]) ? [] : $closed_risks[1];

    // If the opened risks array is empty
    if (empty($opened_risks[0]))
    {
        $opened_risk_data[] = array("No Data Available", 0);
    }
    // Otherwise
    else
    {
        // Set the initial values
        $date = strtotime($open_date[0]);
        $opened_sum = 0;
        $closed_sum = 0;

        // For each date from the start date until today
        while ($date <= time())
        {
            // If the PHP version is >= 5.5.0
            // array_column is new as of PHP 5.5
            if (strnatcmp(phpversion(),'5.5.0') >= 0)
            {
                // Search the open risks array
                $opened_search = array_search(date("Y-m-d", $date), $open_date);
            }
            else $opened_search = false;
            

            // If the current date is in the opened array
            if ($opened_search !== false)
            {
                $count = $open_count[$opened_search];
                $opened_sum += $count;
            }

            // If the PHP version is >= 5.5.0
            // array_column is new as of PHP 5.5
            if (strnatcmp(phpversion(),'5.5.0') >= 0)
            {
                // Search the closed array for the value
                $closed_search = array_search(date("Y-m-d", $date), $close_date);
            }
            else $closed_search = false;

            // If the current date is in the closed array
            if ($closed_search !== false)
            {
                $count = $close_count[$closed_search];
                $closed_sum += $count;
            }

            // Create the data arrays
            $opened_risk_data[] = array($date * 1000, $opened_sum);
            $closed_risk_data[] = array($date * 1000, $closed_sum);
            $trend_data[] = array($date * 1000, $opened_sum - $closed_sum);

            // Increment the date one day
            $date = strtotime("+1 day", $date);
        }

        // Draw the open risks line
        $chart->series[] = array(
            'type' => "line",
            'name' => "Opened Risks",
            'color' => "red",
            'lineWidth' => "2",
            'data' => empty($opened_risk_data) ? [] : $opened_risk_data
        );

        // Draw the closed risks line
        $chart->series[] = array(
            'type' => "line",
            'name' => "Closed Risks",
            'color' => "blue",
            'lineWidth' => "2",
            'data' => empty($closed_risk_data) ? [] : $closed_risk_data
        );

        // Draw the trend line
        $chart->series[] = array(
            'type' => "line",
            'name' => "Trend",
            'color' => "#000000",
            'lineWidth' => "2",
            'data' => empty($trend_data) ? [] : $trend_data
        );
    }

    $chart->printScripts();
    echo "<div id=\"risk_trend_chart\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("risk_trend_chart");
    echo "</script>\n";
    echo "<br /><p><font size=\"1\">* This report requires PHP >= 5.5 in order to run properly.</font></p>\n";
}

/******************************
 * FUNCTION: GET RISK PYRAMID *
 ******************************/
function get_risk_pyramid($title = null)
{
    $chart = new Highchart();

    $chart->chart->type = "pyramid";
    $chart->chart->marginRight = "100";
    $chart->title->text = $title;
    $chart->chart->renderTo = "risk_pyramid_chart";
    $chart->credits->enabled = false;
    $chart->plotOptions->series->dataLabels->enabled = true;
    $chart->plotOptions->series->dataLabels->format = "<b>{point.name}</b> ({point.y:,.0f})";
    $chart->plotOptions->series->dataLabels->color = "(Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'";
    $chart->plotOptions->series->dataLabels->softConnector = true;
    $chart->legend->enabled = false;

    // Open the database connection
    $db = db_open();

    // Get the risk levels
    $stmt = $db->prepare("SELECT * from `risk_levels` ORDER BY value DESC");
    $stmt->execute();
    $array = $stmt->fetchAll();
    $veryhigh = $array[0]['value'];
    $high = $array[1]['value'];
    $medium = $array[2]['value'];
    $low = $array[3]['value'];

    $very_high_display_name = get_risk_level_display_name('Very High');
    $high_display_name      = get_risk_level_display_name('High');
    $medium_display_name    = get_risk_level_display_name('Medium');
    $low_display_name       = get_risk_level_display_name('Low');
    $insignificant_display_name = get_risk_level_display_name('Insignificant');

    // If the team separation extra is not enabled
    if (!team_separation_extra())
    {
        // Query the database
    $stmt = $db->prepare("select a.residual_risk, COUNT(*) AS num, CASE WHEN residual_risk >= :veryhigh THEN :very_high_display_name WHEN residual_risk < :veryhigh AND residual_risk >= :high THEN :high_display_name WHEN residual_risk < :high AND residual_risk >= :medium THEN :medium_display_name WHEN residual_risk < :medium AND residual_risk >= :low THEN :low_display_name WHEN residual_risk < :low AND residual_risk >= 0 THEN :insignificant_display_name END AS level from (select ROUND(a.calculated_risk - (a.calculated_risk * GREATEST(IFNULL(c.mitigation_percent,0), IFNULL(MAX(d.mitigation_percent), 0)) / 100), 2) as residual_risk FROM `risk_scoring` a JOIN `risks` b ON a.id = b.id LEFT JOIN mitigations c ON b.id = c.risk_id LEFT JOIN framework_controls d ON FIND_IN_SET(d.id, c.mitigation_controls) WHERE b.status != \"Closed\" GROUP BY b.id) as a GROUP BY level ORDER BY a.residual_risk DESC");
        $stmt->bindParam(":veryhigh", $veryhigh, PDO::PARAM_STR, 4);
        $stmt->bindParam(":high", $high, PDO::PARAM_STR, 4);
        $stmt->bindParam(":medium", $medium, PDO::PARAM_STR, 4);
        $stmt->bindParam(":low", $low, PDO::PARAM_STR, 4);
        
        $stmt->bindParam(":very_high_display_name", $very_high_display_name, PDO::PARAM_STR);
        $stmt->bindParam(":high_display_name", $high_display_name, PDO::PARAM_STR);
        $stmt->bindParam(":medium_display_name", $medium_display_name, PDO::PARAM_STR);
        $stmt->bindParam(":low_display_name", $low_display_name, PDO::PARAM_STR);
        $stmt->bindParam(":insignificant_display_name", $insignificant_display_name, PDO::PARAM_STR);
        $stmt->execute();

        // Store the list in the array
        $array = $stmt->fetchAll();
    }
    else
    {
        //Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Query the database
        $array = strip_no_access_open_risk_summary($veryhigh, $high, $medium, $low);
    }

    // Close the database connection
    db_close($db);

    // Reverse the order of the array
    $array = array_reverse($array);

    // If the array is empty
    if (empty($array))
    {
            $data[] = array("No Data Available", 0);
    }
    // Otherwise
    else
    {
        // Initialize veryhigh, high, medium, low, and insignificant
        $veryhigh = false;
        $high = false;
        $medium = false;
        $low = false;
        $insignificant = false;
        $color_array = array();
        
        $risk_levels = get_risk_levels();
        $risk_levels_by_color = array();
        foreach($risk_levels as $risk_level){
            $risk_levels_by_color[$risk_level['name']] = $risk_level;
        }
        // Create the data array
        foreach ($array as $row)
        {
            $data[] = array($row['level'], (int)$row['num']);

            // If we have at least one very high risk
            if ($row['level'] == $very_high_display_name && $veryhigh != true)
            {
                $veryhigh = true;

                // Add red to the color array
                $color_array[] = $risk_levels_by_color["Very High"]["color"];
            }
            // If we have at least one high risk
            else if ($row['level'] == $high_display_name && $high != true)
            {
                $high = true;

                // Add red to the color array
                $color_array[] = $risk_levels_by_color["High"]["color"];
            }
            // If we have at least one medium risk
            else if ($row['level'] == $medium_display_name && $medium != true)
            {
                $medium = true;

                // Add orange to the color array
                $color_array[] = $risk_levels_by_color["Medium"]["color"];
            }
            // If we have at least one low risk
            else if ($row['level'] == $low_display_name && $low != true)
            {
                $low = true;

                // Add yellow to the color array
                $color_array[] = $risk_levels_by_color["Low"]["color"];
            }
            else if ($row['level'] == $insignificant_display_name && $insignificant != true)
            {
                $insignificant = true;

                // Add lightgrey to the color array
                $color_array[] = "lightgrey";
            }
        }

        $chart->plotOptions->pyramid->colors = $color_array;

        $chart->series[] = array(
                'name' => "Risk Pyramid",
                'data' => $data);
    }

    $chart->printScripts();
    echo "<div id=\"risk_pyramid_chart\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("risk_pyramid_chart");
    echo "</script>\n";
}

/**********************************
 * FUNCTION: OPEN RISK LEVEL PIE *
 * $teams: ex: 1:2:3:4
 **********************************/
function open_risk_level_pie($title = null, $teams = false)
{
    $chart = new Highchart();
    
    if($teams !== false){
        if($teams == ""){
            $teams_query = " AND 0 ";
        }else{
            $options = explode(",", $teams);
            $teams_query = generate_or_query($options, 'team', 'b');
            $teams_query = " AND ( {$teams_query} ) ";
        }
    }else{
        $teams_query = "";
    }
    
    $chart->chart->renderTo = "open_risk_level_pie";
    $chart->chart->plotBackgroundColor = null;
    $chart->chart->plotBorderWidth = null;
    $chart->chart->plotShadow = false;
    $chart->title->text = $title;

    $chart->tooltip->formatter = new HighchartJsExpr("function() {
    return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

    $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
    location.href = 'dynamic_risk_report.php?status=0&group=1&sort=0'; }");

    $chart->plotOptions->pie->allowPointSelect = 1;
    $chart->plotOptions->pie->cursor = "pointer";
    $chart->plotOptions->pie->dataLabels->enabled = false;
    $chart->plotOptions->pie->showInLegend = 1;
    $chart->credits->enabled = false;

    // Open the database connection
    $db = db_open();

    // Get the risk levels
    $stmt = $db->prepare("SELECT * from `risk_levels` ORDER BY value DESC");
    $stmt->execute();
    $array = $stmt->fetchAll();
    $veryhigh = $array[0]['value'];
    $high = $array[1]['value'];
    $medium = $array[2]['value'];
    $low = $array[3]['value'];

    $very_high_display_name = get_risk_level_display_name('Very High');
    $high_display_name      = get_risk_level_display_name('High');
    $medium_display_name    = get_risk_level_display_name('Medium');
    $low_display_name       = get_risk_level_display_name('Low');
    $insignificant_display_name = get_risk_level_display_name('Insignificant');

    // If the team separation extra is not enabled
    if (!team_separation_extra())
    {
        // Query the database
        $stmt = $db->prepare("select a.residual_risk, COUNT(*) AS num, CASE WHEN residual_risk >= :veryhigh THEN :very_high_display_name WHEN residual_risk < :veryhigh AND residual_risk >= :high THEN :high_display_name WHEN residual_risk < :high AND residual_risk >= :medium THEN :medium_display_name WHEN residual_risk < :medium AND residual_risk >= :low THEN :low_display_name WHEN residual_risk < :low AND residual_risk >= 0 THEN :insignificant_display_name END AS level from (select ROUND(a.calculated_risk - (a.calculated_risk * GREATEST(IFNULL(c.mitigation_percent,0), IFNULL(MAX(d.mitigation_percent), 0)) / 100), 2) as residual_risk FROM `risk_scoring` a JOIN `risks` b ON a.id = b.id LEFT JOIN mitigations c ON b.id = c.risk_id LEFT JOIN framework_controls d ON FIND_IN_SET(d.id, c.mitigation_controls) WHERE b.status != \"Closed\" ". $teams_query ." GROUP BY b.id) as a GROUP BY level ORDER BY a.residual_risk DESC");
        $stmt->bindParam(":veryhigh", $veryhigh, PDO::PARAM_STR, 4);
        $stmt->bindParam(":high", $high, PDO::PARAM_STR, 4);
        $stmt->bindParam(":medium", $medium, PDO::PARAM_STR, 4);
        $stmt->bindParam(":low", $low, PDO::PARAM_STR, 4);
        
        $stmt->bindParam(":very_high_display_name", $very_high_display_name, PDO::PARAM_STR);
        $stmt->bindParam(":high_display_name", $high_display_name, PDO::PARAM_STR);
        $stmt->bindParam(":medium_display_name", $medium_display_name, PDO::PARAM_STR);
        $stmt->bindParam(":low_display_name", $low_display_name, PDO::PARAM_STR);
        $stmt->bindParam(":insignificant_display_name", $insignificant_display_name, PDO::PARAM_STR);

        $stmt->execute();

        // Store the list in the array
        $array = $stmt->fetchAll();
    }
    else
    {
        //Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Query the database
        $array = strip_no_access_open_risk_summary($veryhigh, $high, $medium, $low, $teams);
    }

    // Close the database connection
    db_close($db);

    
    // If the array is empty
    if (empty($array))
    {
        $data[] = array("No Data Available", 0);
    }
    // Otherwise
    else
    {
        // Initialize veryhigh, high, medium, low, and insignificant
        $veryhigh = false;
        $high = false;
        $medium = false;
        $low = false;
        $insignificant = false;
        $color_array = array();

        $risk_levels = get_risk_levels();
        $risk_levels_by_color = array();
        foreach($risk_levels as $risk_level){
            $risk_levels_by_color[$risk_level['name']] = $risk_level;
        }

        // Create the data array
        foreach ($array as $row)
        {
            $data[] = array($row['level'], (int)$row['num']);

            // If we have at least one very high risk
            if ($row['level'] == $very_high_display_name && $veryhigh != true)
            {
                $veryhigh = true;

                // Add red to the color array
                $color_array[] = $risk_levels_by_color["Very High"]["color"];
            }
            // If we have at least one high risk
            else if ($row['level'] == $high_display_name && $high != true)
            {
                $high = true;

                // Add red to the color array
                $color_array[] = $risk_levels_by_color["High"]["color"];
            }
            // If we have at least one medium risk
            else if ($row['level'] == $medium_display_name && $medium != true)
            {
                $medium = true;

                // Add orange to the color array
                $color_array[] = $risk_levels_by_color["Medium"]["color"];
            }
            // If we have at least one low risk
            else if ($row['level'] == $low_display_name && $low != true)
            {
                $low = true;

                // Add yellow to the color array
                $color_array[] = $risk_levels_by_color["Low"]["color"];
            }
            else if ($row['level'] == $insignificant_display_name && $insignificant != true)
            {
                $insignificant = true;

                // Add lightgrey to the color array
                $color_array[] = "lightgrey";
            }
        }

        // Add black to color array for insignificant
        $color_array[] = "lightgrey";

        $chart->plotOptions->pie->colors = $color_array;

        $data = encode_data_before_display($data);

        $chart->series[] = array('type' => "pie",
                'name' => "Level",
                'data' => $data);
    }

    echo "<div id=\"open_risk_level_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_risk_level_pie");
    echo "</script>\n";
}

/**********************************
 * FUNCTION: OPEN RISK STATUS PIE *
 **********************************/
function open_risk_status_pie($array, $title = null, $teams = false)
{
    $chart = new Highchart();

    $chart->chart->renderTo = "open_risk_status_pie";
    $chart->chart->plotBackgroundColor = null;
    $chart->chart->plotBorderWidth = null;
    $chart->chart->plotShadow = false;
    $chart->title->text = $title;

    $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=0&group=2&sort=0'; }");

    $chart->plotOptions->pie->allowPointSelect = 1;
    $chart->plotOptions->pie->cursor = "pointer";
    $chart->plotOptions->pie->dataLabels->enabled = false;
    $chart->plotOptions->pie->showInLegend = 1;
    $chart->credits->enabled = false;

    // If the array is empty
    if (empty($array))
    {
        $data[] = array("No Data Available", 0);
    }
    // Otherwise
    else
    {
        // Set the sort value
        $sort = "status";

        // Sort the array
        $array = sort_array($array, $sort);

        // Count the array by status
        $data = count_array_values($array, $sort);

        $data = encode_data_before_display($data);

        // Create the pie chart
        $chart->series[] = array('type' => "pie",
            'name' => $sort,
            'data' => $data);
    }

    echo "<div id=\"open_risk_status_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_risk_status_pie");
    echo "</script>\n";
}

/************************************
 * FUNCTION: CLOSED RISK REASON PIE *
 ************************************/
function closed_risk_reason_pie($title = null, $teams = false)
{
    if($teams !== false){
        if($teams == ""){
            $teams_query = " AND 0 ";
        }else{
            $options = explode(",", $teams);
            $teams_query = generate_or_query($options, 'team', 'c');
            $teams_query = " AND ( {$teams_query} ) ";
        }
    }else{
        $teams_query = "";
    }

    $chart = new Highchart();

    $chart->chart->renderTo = "closed_risk_reason_pie";
    $chart->chart->plotBackgroundColor = null;
    $chart->chart->plotBorderWidth = null;
    $chart->chart->plotShadow = false;
    $chart->title->text = $title;

    $chart->tooltip->formatter = new HighchartJsExpr("function() {
    return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

    $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
    location.href = 'dynamic_risk_report.php?status=1&group=0&sort=0'; }");

    $chart->plotOptions->pie->allowPointSelect = 1;
    $chart->plotOptions->pie->cursor = "pointer";
    $chart->plotOptions->pie->dataLabels->enabled = false;
    $chart->plotOptions->pie->showInLegend = 1;
    $chart->credits->enabled = false;

    // Open the database connection
    $db = db_open();

    // If the team separation extra is not enabled
    if (!team_separation_extra())
    {
            // Query the database
    $stmt = $db->prepare("SELECT name, COUNT(*) as num FROM (SELECT a.close_reason, b.name, MAX(closure_date) FROM `closures` a JOIN `close_reason` b ON a.close_reason = b.value JOIN `risks` c ON a.risk_id = c.id WHERE c.status = \"Closed\" {$teams_query} GROUP BY risk_id ORDER BY name DESC) AS close GROUP BY name ORDER BY COUNT(*) DESC;");
            $stmt->execute();

            // Store the list in the array
            $array = $stmt->fetchAll();
    }
    else
    {
        //Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Query the database
        $array = strip_no_access_risk_pie('close_reason', $teams);
    }

    // Close the database connection
    db_close($db);

    // If the array is empty
    if (empty($array))
    {
            $data[] = array("No Data Available", 0);
    }
    // Otherwise
    else
    {
        // Create the data array
        foreach ($array as $row)
        {
            $data[] = array($row['name'], (int)$row['num']);
        }

        $chart->series[] = array('type' => "pie",
                    'name' => "Status",
                    'data' => $data);
    }

    echo "<div id=\"closed_risk_reason_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("closed_risk_reason_pie");
    echo "</script>\n";
}

/************************************
 * FUNCTION: OPEN RISK LOCATION PIE *
 ************************************/
function open_risk_location_pie($array, $title = null)
{
        $chart = new Highchart();

        $chart->chart->renderTo = "open_risk_location_pie";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = $title;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=0&group=3&sort=0'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = false;
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->credits->enabled = false;

        // If the array is empty
        if (empty($array))
        {
                $data[] = array("No Data Available", 0);
        }
        // Otherwise
        else
        {
                // Set the sort value
                $sort = "location";

                // Sort the array
                $array = sort_array($array, $sort);

                // Count the array by status
                $data = count_array_values($array, $sort);

        $data = encode_data_before_display($data);

                // Create the pie chart
                $chart->series[] = array('type' => "pie",
                        'name' => $sort,
                        'data' => $data);
        }

    echo "<div id=\"open_risk_location_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_risk_location_pie");
    echo "</script>\n";
}

/**********************************
 * FUNCTION: OPEN RISK SOURCE PIE *
 **********************************/
function open_risk_source_pie($array, $title = null)
{
        $chart = new Highchart();

        $chart->chart->renderTo = "open_risk_source_pie";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = $title;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=0&group=4&sort=0'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = false;
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->credits->enabled = false;

        // If the array is empty
        if (empty($array))
        {
                $data[] = array("No Data Available", 0);
        }
        // Otherwise
        else
        {
                // Set the sort value
                $sort = "source";

                // Sort the array
                $array = sort_array($array, $sort);

                // Count the array by status
                $data = count_array_values($array, $sort);

        $data = encode_data_before_display($data);

                // Create the pie chart
                $chart->series[] = array('type' => "pie",
                        'name' => $sort,
                        'data' => $data);
        }

    echo "<div id=\"open_risk_source_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_risk_source_pie");
    echo "</script>\n";
}

/************************************
 * FUNCTION: OPEN RISK CATEGORY PIE *
 ************************************/
function open_risk_category_pie($array, $title = null)
{
        $chart = new Highchart();

        $chart->chart->renderTo = "open_risk_category_pie";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = $title;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=0&group=5&sort=0'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = false;
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->credits->enabled = false;

        // If the array is empty
        if (empty($array))
        {
                $data[] = array("No Data Available", 0);
        }
        // Otherwise
        else
        {
                // Set the sort value
                $sort = "category";

                // Sort the array
                $array = sort_array($array, $sort);

                // Count the array by status
                $data = count_array_values($array, $sort);

        $data = encode_data_before_display($data);

                // Create the pie chart
                $chart->series[] = array('type' => "pie",
                        'name' => $sort,
                        'data' => $data);
        }

    echo "<div id=\"open_risk_category_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_risk_category_pie");
    echo "</script>\n";
}

/********************************
 * FUNCTION: OPEN RISK TEAM PIE *
 ********************************/
function open_risk_team_pie($array, $title = null)
{
        $chart = new Highchart();

        $chart->chart->renderTo = "open_risk_team_pie";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = $title;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=0&group=6&sort=0'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = false;
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->credits->enabled = false;

        // If the array is empty
        if (empty($array))
        {
                $data[] = array("No Data Available", 0);
        }
        // Otherwise
        else
        {
                // Set the sort value
                $sort = "team";

                // Sort the array
                $array = sort_array($array, $sort);

                // Count the array by status
                $data = count_array_values($array, $sort);

        $data = encode_data_before_display($data);

                // Create the pie chart
                $chart->series[] = array('type' => "pie",
                        'name' => $sort,
                        'data' => $data);
        }

    echo "<div id=\"open_risk_team_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_risk_team_pie");
    echo "</script>\n";
}

/**************************************
 * FUNCTION: OPEN RISK TECHNOLOGY PIE *
 **************************************/
function open_risk_technology_pie($array, $title = null)
{
        $chart = new Highchart();

        $chart->chart->renderTo = "open_risk_technology_pie";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = $title;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=0&group=7&sort=0'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = false;
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->credits->enabled = false;

        // If the array is empty
        if (empty($array))
        {
                $data[] = array("No Data Available", 0);
        }
        // Otherwise
        else
        {
                // Set the sort value
                $sort = "technology";

                // Sort the array
                $array = sort_array($array, $sort);

                // Count the array by status
                $data = count_array_values($array, $sort);

        $data = encode_data_before_display($data);

                // Create the pie chart
                $chart->series[] = array('type' => "pie",
                        'name' => $sort,
                        'data' => $data);
        }

    echo "<div id=\"open_risk_technology_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_risk_technology_pie");
    echo "</script>\n";
}

/**************************************
 * FUNCTION: OPEN RISK OWNER PIE *
 **************************************/
function open_risk_owner_pie($array, $title = null)
{
        $chart = new Highchart();

        $chart->chart->renderTo = "open_risk_owner_pie";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = $title;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=0&group=8&sort=0'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = false;
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->credits->enabled = false;

        // If the array is empty
        if (empty($array))
        {
                $data[] = array("No Data Available", 0);
        }
        // Otherwise
        else
        {
                // Set the sort value
                $sort = "owner";

                // Sort the array
                $array = sort_array($array, $sort);

                // Count the array by status
                $data = count_array_values($array, $sort);

        $data = encode_data_before_display($data);

                // Create the pie chart
                $chart->series[] = array('type' => "pie",
                        'name' => $sort,
                        'data' => $data);
        }

    echo "<div id=\"open_risk_owner_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_risk_owner_pie");
    echo "</script>\n";
}

/******************************************
 * FUNCTION: OPEN RISK OWNERS MANAGER PIE *
 ******************************************/
function open_risk_owners_manager_pie($array, $title = null)
{
        $chart = new Highchart();

        $chart->chart->renderTo = "open_risk_owners_manager_pie";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = $title;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=0&group=9&sort=0'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = false;
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->credits->enabled = false;

        // If the array is empty
        if (empty($array))
        {
            $data[] = array("No Data Available", 0);
        }
        // Otherwise
        else
        {
            // Set the sort value
            $sort = "manager";

            // Sort the array
            $array = sort_array($array, $sort);

            // Count the array by status
            $data = count_array_values($array, $sort);

            $data = encode_data_before_display($data);

            // Create the pie chart
            $chart->series[] = array('type' => "pie",
                    'name' => $sort,
                    'data' => $data);
        }

    echo "<div id=\"open_risk_owners_manager_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_risk_owners_manager_pie");
    echo "</script>\n";
}

/******************************************
 * FUNCTION: OPEN RISK SCORING METHOD PIE *
 ******************************************/
function open_risk_scoring_method_pie($array, $title = null)
{
        $chart = new Highchart();

        $chart->chart->renderTo = "open_risk_scoring_method_pie";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = $title;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=0&group=10&sort=0'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = false;
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->credits->enabled = false;

        // If the array is empty
        if (empty($array))
        {
                $data[] = array("No Data Available", 0);
        }
        // Otherwise
        else
        {
                // Set the sort value
                $sort = "scoring_method";

                // Sort the array
                $array = sort_array($array, $sort);

                // Count the array by status
                $data = count_array_values($array, $sort);

        $data = encode_data_before_display($data);

                // Create the pie chart
                $chart->series[] = array('type' => "pie",
                        'name' => $sort,
                        'data' => $data);
        }

    echo "<div id=\"open_risk_scoring_method_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_risk_scoring_method_pie");
    echo "</script>\n";
}

/*********************************
 * FUNCTION: OPEN MITIGATION PIE *
 *********************************/
function open_mitigation_pie($title = null)
{
        $chart = new Highchart();

        $chart->chart->renderTo = "open_mitigation_pie";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = $title;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=2&group=2&sort=0'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = false;
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->credits->enabled = false;

        // If team separation is not enabled
        if (!team_separation_extra())
        {
                // Open the database connection
                $db = db_open();

                // Query the database
        $stmt = $db->prepare("SELECT id, CASE WHEN mitigation_id = 0 THEN 'Unmitigated' WHEN mitigation_id != 0 THEN 'Mitigated' END AS name FROM `risks` WHERE status != \"Closed\" ORDER BY name");
                $stmt->execute();

                // Store the list in the array
                $array = $stmt->fetchAll();

                // Close the database connection
                db_close($db);
        }
        // Otherwise team separation is enabled
        else
        {
                //Include the team separation extra
                require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

                // Get the open mitigation pie with risks stripped
                $array = strip_open_mitigation_pie();
        }

        // Set the defaults
        $current_type = "";
        $grouped_array = array();
        $counter = -1;

        foreach ($array as $row)
        {
                // If the row name is not the current row
                if ($row['name'] != $current_type)
                {
                        // Increment the counter
                        $counter = $counter + 1;

                        // Add the value to the grouped array
                        $grouped_array[$counter]['name'] = $row['name'];
                        $grouped_array[$counter]['num'] = 1;

                        // Set the current type
                        $current_type = $row['name'];
                }
                else
                {
                        // Add the value to the grouped array
                        $grouped_array[$counter]['num'] = $grouped_array[$counter]['num'] + 1;
                }
        }

        $array = $grouped_array;

        // If the array is empty
        if (empty($array))
        {
                $data[] = array("No Data Available", 0);
        }
        // Otherwise
        else
        {
                // Create the data array
                foreach ($array as $row)
                {
                        $data[] = array($row['name'], (int)$row['num']);

            if ($row['name'] == "Mitigated")
            {
                $color_array[] = "green";
            }
            else if ($row['name'] == "Unmitigated")
            {
                $color_array[] = "red";
            }
                }

                $chart->plotOptions->pie->colors = $color_array;

        $data = encode_data_before_display($data);

                $chart->series[] = array('type' => "pie",
                        'name' => "Status",
                        'data' => $data);
        }

    echo "<div id=\"open_mitigation_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_mitigation_pie");
    echo "</script>\n";
}

/*****************************
 * FUNCTION: OPEN REVIEW PIE *
 *****************************/
function open_review_pie($title = null)
{
        $chart = new Highchart();

        $chart->chart->renderTo = "open_review_pie";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = $title;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=2&group=2&sort=0'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = false;
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->credits->enabled = false;

        // If team separation is not enabled
        if (!team_separation_extra())
        {
                // Open the database connection
                $db = db_open();

                // Query the database
        $stmt = $db->prepare("SELECT id, CASE WHEN mgmt_review = 0 THEN 'Unreviewed' WHEN mgmt_review != 0 THEN 'Reviewed' END AS name FROM `risks` WHERE status != \"Closed\" ORDER BY name");
                $stmt->execute();

                // Store the list in the array
                $array = $stmt->fetchAll();

                // Close the database connection
                db_close($db);
        }
        // Otherwise team separation is enabled
        else
        {
                //Include the team separation extra
                require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

                // Get the open review pie with risks stripped
                $array = strip_open_review_pie();
        }

        // Set the defaults
        $current_type = "";
        $grouped_array = array();
        $counter = -1;

        foreach ($array as $row)
        {
                // If the row name is not the current row
                if ($row['name'] != $current_type)
                {
                        // Increment the counter
                        $counter = $counter + 1;

                        // Add the value to the grouped array
                        $grouped_array[$counter]['name'] = $row['name'];
                        $grouped_array[$counter]['num'] = 1;

                        // Set the current type
                        $current_type = $row['name'];
                }
                else
                {
                        // Add the value to the grouped array
                        $grouped_array[$counter]['num'] = $grouped_array[$counter]['num'] + 1;
                }
        }

        $array = $grouped_array;

        // If the array is empty
        if (empty($array))
        {
                $data[] = array("No Data Available", 0);
        }
        // Otherwise
        else
        {
                // Create the data array
                foreach ($array as $row)
                {
                        $data[] = array($row['name'], (int)$row['num']);

            if ($row['name'] == "Reviewed")
            {
                $color_array[] = "green";
            }
            else if ($row['name'] == "Unreviewed")
            {
                $color_array[] = "red";
            }
                }

        $chart->plotOptions->pie->colors = $color_array;

        $data = encode_data_before_display($data);

                $chart->series[] = array('type' => "pie",
                        'name' => "Status",
                        'data' => $data);
        }

    echo "<div id=\"open_review_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_review_pie");
    echo "</script>\n";
}

/*****************************
 * FUNCTION: OPEN CLOSED PIE *
 *****************************/
function open_closed_pie($title = null)
{
        $chart = new Highchart();

        $chart->chart->renderTo = "open_closed_pie";
        $chart->chart->plotBackgroundColor = null;
        $chart->chart->plotBorderWidth = null;
        $chart->chart->plotShadow = false;
        $chart->title->text = $title;

        $chart->tooltip->formatter = new HighchartJsExpr("function() {
        return '<b>'+ this.point.name +'</b>: '+ this.point.y; }");

        $chart->plotOptions->pie->point->events->click = new HighchartJsExpr("function() {
        location.href = 'dynamic_risk_report.php?status=2&group=2&sort=0'; }");

        $chart->plotOptions->pie->allowPointSelect = 1;
        $chart->plotOptions->pie->cursor = "pointer";
        $chart->plotOptions->pie->dataLabels->enabled = false;
        $chart->plotOptions->pie->showInLegend = 1;
        $chart->credits->enabled = false;

    // If team separation is not enabled
    if (!team_separation_extra())
    {
        // Open the database connection
        $db = db_open();

            // Query the database
        $stmt = $db->prepare("SELECT id, CASE WHEN status = \"Closed\" THEN 'Closed' WHEN status != \"Closed\" THEN 'Open' END AS name FROM `risks` ORDER BY name");
        $stmt->execute();

        // Store the list in the array
        $array = $stmt->fetchAll();

        // Close the database connection
        db_close($db);
    }
    // Otherwise team separation is enabled
    else
    {
        //Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Get the open pie with risks stripped
        $array = strip_open_closed_pie();
    }

    // Set the defaults
    $current_type = "";
    $grouped_array = array();
    $counter = -1;

    foreach ($array as $row)
    {
        // If the row name is not the current row
        if ($row['name'] != $current_type)
        {
            // Increment the counter
            $counter = $counter + 1;

            // Add the value to the grouped array
            $grouped_array[$counter]['name'] = $row['name'];
            $grouped_array[$counter]['num'] = 1;

            // Set the current type
            $current_type = $row['name'];
        }
        else
        {
            // Add the value to the grouped array
            $grouped_array[$counter]['num'] = $grouped_array[$counter]['num'] + 1;
        }
    }

    $array = $grouped_array;

        // If the array is empty
        if (empty($array))
        {
                $data[] = array("No Data Available", 0);
        }
        // Otherwise
        else
        {
                // Create the data array
                foreach ($array as $row)
                {
                        $data[] = array($row['name'], (int)$row['num']);

                        if ($row['name'] == "Closed")
                        {
                                $color_array[] = "green";
                        }
                        else if ($row['name'] == "Open")
                        {
                                $color_array[] = "red";
                        }
                }

                $chart->plotOptions->pie->colors = $color_array;

        $data = encode_data_before_display($data);

                $chart->series[] = array('type' => "pie",
                        'name' => "Status",
                        'data' => $data);
        }

    echo "<div id=\"open_closed_pie\"></div>\n";
    echo "<script type=\"text/javascript\">";
    echo $chart->render("open_closed_pie");
    echo "</script>\n";
}

/*************************************
 * FUNCTION: GET REVIEW NEEDED TABLE *
 *************************************/
function get_review_needed_table()
{
    global $lang;
    global $escaper;

    // Get risks marked as consider for projects
    $risks = get_risks(3);

    // Initialize the reviews array
    $reviews = array();

    // Parse through each row in the array
    foreach ($risks as $key => $row)
    {
        // Create arrays for each value
        $risk_id[$key] = (int)$row['id'];
        $subject[$key] = $row['subject'];
        $status[$key] = $row['status'];
        $calculated_risk[$key] = $row['calculated_risk'];
        $color[$key] = get_risk_color($row['calculated_risk']);
        $risk_level = get_risk_level_name($row['calculated_risk']);
        $residual_risk_level = get_risk_level_name($row['residual_risk']);
        $dayssince[$key] = dayssince($row['submission_date']);

        // If next_review_date_uses setting is Residual Risk.
        if(get_setting('next_review_date_uses') == "ResidualRisk")
        {
            $next_review[$key] = next_review($residual_risk_level, $risk_id[$key], $row['next_review'], false);
            $next_review_html[$key] = next_review($residual_risk_level, $row['id'], $row['next_review']);
        }
        // If next_review_date_uses setting is Inherent Risk.
        else
        {
            $next_review[$key] = next_review($risk_level, $risk_id[$key], $row['next_review'], false);
            $next_review_html[$key] = next_review($risk_level, $row['id'], $row['next_review']);
        }
        
        // Create a new array of reviews
        $reviews[] = array('risk_id' => $risk_id[$key], 'subject' => $subject[$key], 'status' => $status[$key], 'calculated_risk' => $calculated_risk[$key], 'color' => $color[$key], 'dayssince' => $dayssince[$key], 'next_review' => $next_review[$key], 'next_review_html' => $next_review_html[$key]);

        // Sort the reviews array by next_review
        array_multisort($next_review, SORT_DESC, SORT_STRING, $calculated_risk, SORT_DESC, SORT_NUMERIC, $reviews);
    }

    // Start with an empty review status;
    $review_status = "";

    // For each risk
    foreach ($reviews as $review)
    {
        $risk_id = $review['risk_id'];
        $subject = $review['subject'];
        $status = $review['status'];
        $calculated_risk = $review['calculated_risk'];
        $color = $review['color'];
        $dayssince = $review['dayssince'];
        $next_review = $review['next_review'];
        $next_review_html = $review['next_review_html'];

        // If we have a new review status and its not a date
        if (($review_status != $next_review) && (!preg_match('/\d{4}/', $review_status)))
        {
            // If its not the first risk
            if ($review_status != "")
            {
                // End the previous table
                echo "</tbody>\n";
                echo "</table>\n";
                echo "<br />\n";

            }

            // Set the new review status
            $review_status = $next_review;

            // If the review status is not a date
            if (!preg_match('/\d{4}/', $review_status))
            {
                // Start the new table
                echo "<table class=\"table table-bordered table-condensed sortable\">\n";
                echo "<thead>\n";
                echo "<tr>\n";
                echo "<th bgcolor=\"#0088CC\" colspan=\"6\"><center>". $escaper->escapeHtml($review_status) ."</center></th>\n";
                echo "</tr>\n";
                echo "<tr>\n";
                echo "<th align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['ID']) ."</th>\n";
                echo "<th align=\"left\" width=\"150px\">". $escaper->escapeHtml($lang['Status']) ."</th>\n";
                echo "<th align=\"left\" width=\"300px\">". $escaper->escapeHtml($lang['Subject']) ."</th>\n";
                echo "<th align=\"center\" width=\"100px\">". $escaper->escapeHtml($lang['Risk']) ."</th>\n";
                echo "<th align=\"center\" width=\"100px\">". $escaper->escapeHtml($lang['DaysOpen']) ."</th>\n";
                echo "<th align=\"center\" width=\"150px\">". $escaper->escapeHtml($lang['NextReviewDate']) ."</th>\n";
                echo "</tr>\n";
                echo "</thead>\n";
                echo "<tbody>\n";
            }
        }

        // If the review status is not a date
        if (!preg_match('/\d{4}/', $review_status)){
            echo "<tr>\n";
            echo "<td align=\"left\" width=\"50px\"><a href=\"../management/view.php?id=" . $escaper->escapeHtml(convert_id($risk_id)) . "\">" . $escaper->escapeHtml(convert_id($risk_id)) . "</a></td>\n";
            echo "<td align=\"left\" width=\"150px\">" . $escaper->escapeHtml($status) . "</td>\n";
            echo "<td align=\"left\" width=\"300px\">" . $escaper->escapeHtml($subject) . "</td>\n";
            echo "<td align=\"center\" class=\"risk-cell\" bgcolor=\"" . $escaper->escapeHtml($color) . "\" width=\"100px\">" . $escaper->escapeHtml($calculated_risk) . "<span class=\"risk-color\" style=\"background-color:{$color}\"></span></td>\n";
            echo "<td align=\"center\" width=\"100px\">" . $escaper->escapeHtml($dayssince) . "</td>\n";
            echo "<td align=\"center\" width=\"150px\">" . $next_review_html . "</td>\n";
            echo "</tr>\n";
        }
    }
}

/************************************
 * FUNCTION: RISKS AND ASSETS TABLE *
 ************************************/
function risks_and_assets_table($report)
{
    global $lang;
    global $escaper;

    // Open the database
    $db = db_open();

    // Check the report
    switch ($report)
    {
        // Risks by Asset
        case 0:
            $query = "SELECT a.risk_id AS id, a.asset, b.id AS asset_id, b.ip AS asset_ip, b.name AS asset_name, b.value AS asset_value, b.location AS asset_location, b.team AS asset_team, c.status, c.subject, c.submission_date, d.calculated_risk, e.next_review FROM risks_to_assets a LEFT JOIN assets b ON a.asset = b.name LEFT JOIN risks c ON a.risk_id = c.id LEFT JOIN risk_scoring d ON a.risk_id = d.id LEFT JOIN mgmt_reviews e ON c.mgmt_review = e.id WHERE status != \"Closed\" ORDER BY asset_value DESC, asset_name, calculated_risk DESC, id";
            break;
        // Assets by Risk
        case 1:
            $query = "SELECT a.risk_id AS id, a.asset, b.id AS asset_id, b.ip AS asset_ip, b.name AS asset_name, b.value AS asset_value, b.location AS asset_location, b.team AS asset_team, c.status, c.subject, d.calculated_risk FROM risks_to_assets a LEFT JOIN assets b ON a.asset = b.name LEFT JOIN risks c ON a.risk_id = c.id LEFT JOIN risk_scoring d ON a.risk_id = d.id WHERE status != \"Closed\" ORDER BY calculated_risk DESC, id, asset_value DESC, asset_name";
            break;
    }

    $stmt = $db->prepare($query);
    $stmt->execute();

    // Store the results in the rows array
    $rows = $stmt->fetchAll();

    // If team separation is enabled
    if (team_separation_extra())
    {
        // Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Strip out risks the user should not have access to
        $rows = strip_no_access_risks($rows);
    }

    // Set the current group to empty
    $current_group = "";

    // If risks by asset
    if ($report == 0)
    {
        // For each row
        foreach ($rows as $row)
        {
            // Get the variables for the row
            $risk_id = (int)$row['id'];
            $asset = $row['asset'];
            $asset_id = (isset($row['asset_id']) ? (int)$row['asset_id'] : "N/A");
            $asset_ip = (isset($row['asset_ip']) ? $row['asset_ip'] : "N/A");
            $asset_name = (isset($row['asset_name']) ? $row['asset_name'] : $asset);
            $asset_value = $row['asset_value'];
            $asset_location = (isset($row['asset_location']) ? get_name_by_value("location",$row['asset_location']) : "N/A");
            $asset_location = ($asset_location != "" ? $asset_location : "N/A");
            $asset_team = (isset($row['asset_team']) ? get_name_by_value("team",$row['asset_team']) : "N/A");
            $asset_team = ($asset_team != "" ? $asset_team : "N/A");
            $status = $row['status'];
            $subject = try_decrypt($row['subject']);
            $calculated_risk = $row['calculated_risk'];
            $color = get_risk_color($calculated_risk);
            $dayssince = dayssince($row['submission_date']);

            // If the current group is not the asset id
            if ($current_group != $asset_id)
            {
                // If this is not the first group
                if ($current_group != "")
                {
                        // End the table
                        echo "</tbody>\n";
                        echo "</table>\n";
                }

                // Set the current group to the asset id
                $current_group = $asset_id;

                // Display the table header
                echo "<table class=\"table table-bordered table-condensed sortable\">\n";
                echo "<thead>\n";
                echo "<tr>\n";
                            echo "<th style=\"background-color: " .$escaper->escapeHtml($color). "\" colspan=\"7\"><center>" . $escaper->escapeHtml($lang['AssetName']) . ":&nbsp;&nbsp;" . $escaper->escapeHtml($asset_name) . "<br />" . $escaper->escapeHtml($lang['AssetValue']) . ":&nbsp;&nbsp;" . $escaper->escapeHtml(get_asset_value_by_id($asset_value)) . " <br> ". $escaper->escapeHtml($lang['AssetRisk']) . ":&nbsp;&nbsp;" . $escaper->escapeHtml($calculated_risk) ."</center></th>\n";
                        echo "</tr>\n";
                echo "<tr>\n";
                            echo "<th align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['ID']) ."</th>\n";
                echo "<th align=\"left\" width=\"150px\">". $escaper->escapeHtml($lang['Status']) ."</th>\n";
                echo "<th align=\"left\" width=\"300px\">". $escaper->escapeHtml($lang['Subject']) ."</th>\n";
                            echo "<th align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['SiteLocation']) ."</th>\n";
                            echo "<th align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['Team']) ."</th>\n";
                            echo "<th align=\"left\" width=\"100px\">". $escaper->escapeHtml($lang['InherentRisk']) ."</th>\n";
                            echo "<th align=\"left\" width=\"100px\">". $escaper->escapeHtml($lang['DaysOpen']) ."</th>\n";
                echo "</tr>\n";
                echo "</thead>\n";
                echo "<tbody>\n";
            }

            // Display the individual asset information
            echo "<tr>\n";
            echo "<td align=\"left\" width=\"50px\"><a href=\"../management/view.php?id=" . $escaper->escapeHtml(convert_id($risk_id)) . "\">" . $escaper->escapeHtml(convert_id($risk_id)) . "</a></td>\n";
            echo "<td align=\"left\" width=\"150px\">" . $escaper->escapeHtml($status) . "</td>\n";
            echo "<td align=\"left\" width=\"300px\">" . $escaper->escapeHtml($subject) . "</td>\n";
            echo "<td align=\"left\" width=\"200px\">" . $escaper->escapeHtml($asset_location) . "</td>\n";
            echo "<td align=\"left\" width=\"200px\">" . $escaper->escapeHtml($asset_team) . "</td>\n";
            echo "<td align=\"center\" class=\"risk-cell\" bgcolor=\"" . $escaper->escapeHtml($color) . "\" width=\"100px\">" . $escaper->escapeHtml($calculated_risk) . "<span class=\"risk-color\" style=\"background-color:" . $escaper->escapeHtml($color) . "\"></span></td>\n";
            echo "<td align=\"center\" width=\"100px\">" . $escaper->escapeHtml($dayssince) . "</td>\n";
                        echo "</tr>\n";
        }

        // End the last table
        echo "</tbody>\n";
        echo "</table>\n";
    }

    // If assets by risk
    if ($report == 1)
    {
        // For each row
        foreach ($rows as $row)
        {
            // Get the variables for the row
            $risk_id = (int)$row['id'];
            $asset = $row['asset'];
            $asset_id = (int)$row['asset_id'];
            $asset_ip = (isset($row['asset_ip']) ? $row['asset_ip'] : "N/A");
            $asset_ip = ($asset_ip != "" ? $asset_ip : "N/A");
            $asset_name = (isset($row['asset_name']) ? $row['asset_name'] : $asset);
            $asset_value = $row['asset_value'];
            $asset_location = (isset($row['asset_location']) ? get_name_by_value("location",$row['asset_location']) : "N/A");
            $asset_location = ($asset_location != "" ? $asset_location : "N/A");
            $asset_team = (isset($row['asset_team']) ? get_name_by_value("team",$row['asset_team']) : "N/A");
            $asset_team = ($asset_team != "" ? $asset_team : "N/A");
            $status = $row['status'];
            $subject = try_decrypt($row['subject']);
            $calculated_risk = $row['calculated_risk'];

            // If the current group is not the risk_id
            if ($current_group != $risk_id)
            {
                // If this is not the first group
                if ($current_group != "")
                {
                    // End the table
                    echo "<tr><td style=\"background-color:" . $escaper->escapeHtml($color) . "\" bgcolor=\"" . $escaper->escapeHtml($color) . "\" colspan=\"5\"></td></tr>\n";
                    echo "<tr>\n";
                    echo "<td style=\"background-color: lightgrey\" align=\"left\" width=\"50px\" colspan=\"4\"><b>" . $escaper->escapeHtml($lang['MaximumQuantitativeLoss']) . "</b></td>\n";
                    echo "<td style=\"background-color: lightgrey\" align=\"left\" width=\"50px\"><b>$" . $escaper->escapeHtml(number_format($asset_valuation)) . "</b></td>\n";
                    echo "</tr>\n";
                    echo "</tbody>\n";
                    echo "</table>\n";
                }

                // Set the current group to the risk id
                $current_group = $risk_id;

                // Get the risk id's asset valuation
                $asset_valuation = asset_valuation_for_risk_id($risk_id);

                // Get the risk color
                $color = get_risk_color($calculated_risk);

                // Display the table header
                echo "<table class=\"table table-bordered table-condensed sortable\">\n";
                echo "<thead>\n";
                echo "<tr>\n";
                echo "<th style=\"background-color:" . $escaper->escapeHtml($color) . "\" bgcolor=\"" . $escaper->escapeHtml($color) . "\" colspan=\"5\"><center><font color=\"#000000\">" . $escaper->escapeHtml($lang['RiskId']) . ":&nbsp;&nbsp;<a href=\"../management/view.php?id=" . $escaper->escapeHtml(convert_id($risk_id)) . "\" style=\"color:#000000\">" . $escaper->escapeHtml(convert_id($risk_id)) . "</a><br />" . $escaper->escapeHtml($lang['Subject']) . ":&nbsp;&nbsp;" . $escaper->escapeHtml($subject) . "<br />" . $escaper->escapeHtml($lang['InherentRisk']) . ":&nbsp;&nbsp;" . $escaper->escapeHtml($calculated_risk) . "&nbsp;&nbsp;(" . $escaper->escapeHtml(get_risk_level_name($calculated_risk)) . ")</font></center></th>\n";
                echo "</tr>\n";
                echo "<tr>\n";
                echo "<th align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['AssetName']) ."</th>\n";
                echo "<th align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['IPAddress']) ."</th>\n";
                echo "<th align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['SiteLocation']) ."</th>\n";
                echo "<th align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['Team']) ."</th>\n";
                echo "<th align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['AssetValuation']) ."</th>\n";
                echo "</tr>\n";
                echo "</thead>\n";
                echo "<tbody>\n";
            }

            // Display the individual asset information
            echo "<tr>\n";
            echo "<td align=\"left\" width=\"50px\">" . $escaper->escapeHtml($asset_name) . "</td>\n";
            echo "<td align=\"left\" width=\"50px\">" . $escaper->escapeHtml($asset_ip) . "</td>\n";
            echo "<td align=\"left\" width=\"50px\">" . $escaper->escapeHtml($asset_location) . "</td>\n";
            echo "<td align=\"left\" width=\"50px\">" . $escaper->escapeHtml($asset_team) . "</td>\n";
            echo "<td align=\"left\" width=\"50px\">" . $escaper->escapeHtml(get_asset_value_by_id($asset_value)) . "</td>\n";
            echo "</tr>\n";
        }

        // If this is not the first group
        if ($current_group != "")
        {
            // End the last table
            echo "<tr><td style=\"background-color:" . $escaper->escapeHtml($color) . "\" bgcolor=\"" . $escaper->escapeHtml($color) . "\" colspan=\"5\"></td></tr>\n";
                    echo "<tr>\n";
            echo "<td style=\"background-color: lightgrey\" align=\"left\" width=\"50px\" colspan=\"4\"><b>" . $escaper->escapeHtml($lang['MaximumQuantitativeLoss']) . "</b></td>\n";
            echo "<td style=\"background-color: lightgrey\" align=\"left\" width=\"50px\"><b>$" . $escaper->escapeHtml(number_format($asset_valuation)) . "</b></td>\n";
            echo "</tr>\n";
            echo "</tbody>\n";
            echo "</table>\n";
        }
    }

    // Close the database
        db_close($db);
}


/********************************
 * FUNCTION: GET RISKS BY TABLE *
 ********************************/
function get_risks_by_table($status, $group, $sort, $affected_asset, $column_id=true, $column_status=false, $column_subject=true, $column_reference_id=false, $column_regulation=false, $column_control_number=false, $column_location=false, $column_source=false, $column_category=false, $column_team=false, $column_technology=false, $column_owner=false, $column_manager=false, $column_submitted_by=false, $column_scoring_method=false, $column_calculated_risk=true, $column_residual_risk=true, $column_submission_date=true, $column_review_date=false, $column_project=false, $column_mitigation_planned=true, $column_management_review=true, $column_days_open=false, $column_next_review_date=false, $column_next_step=false, $column_affected_assets=false, $column_planning_strategy=false, $column_planning_date=false, $column_mitigation_effort=false, $column_mitigation_cost=false, $column_mitigation_owner=false, $column_mitigation_team=false, $column_mitigation_date=false, $column_risk_assessment=false, $column_additional_notes=false, $column_current_solution=false, $column_security_recommendations=false, $column_security_requirements=false)
{
    global $lang;
    global $escaper;

    // Check the status
    switch ($status)
    {
        // Open risks
        case 0:
            $status_query = " WHERE a.status != \"Closed\" ";
            break;
        // Closed risks
        case 1:
            $status_query = " WHERE a.status = \"Closed\" ";
            break;
        case 2:
        // All risks
            $status_query = " ";
            break;
        // Default to open risks
        default:
            $status_query = " WHERE a.status != \"Closed\" ";
            break;
    }


    // Check the sort
    switch ($sort)
    {
            // Calculated Risk
            case 0:
        $sort_name = " calculated_risk DESC ";
                    break;
    // ID
    case 1:
        $sort_name = " a.id ASC ";
        break;
    // Subject
    case 2:
        $sort_name = " a.subject ASC ";
        break;
            // Default to calculated risk
            default:
        $sort_name = " calculated_risk DESC ";
                    break;
    }

    // Check the group
    switch ($group)
    {
        // None
        case 0:
            $order_query = "GROUP BY id ORDER BY" . $sort_name;
            $group_name = "none";
            break;
        // Risk Level
        case 1:
            $order_query = "GROUP BY id ORDER BY calculated_risk DESC, " . $sort_name;
            $group_name = "risk_level";
            break;
        // Status
        case 2:
            $order_query = "GROUP BY id ORDER BY a.status," . $sort_name;
            $group_name = "status";
            break;
        // Site/Location
        case 3:
            $order_query = "GROUP BY id ORDER BY location," . $sort_name;
            $group_name = "location";
            break;
        // Source
        case 4:
            $order_query = "GROUP BY id ORDER BY source," . $sort_name;
            $group_name = "source";
            break;
        // Category
        case 5:
            $order_query = "GROUP BY id ORDER BY category," . $sort_name;
            $group_name = "category";
            break;
        // Team
        case 6:
            $order_query = "GROUP BY id ORDER BY team," . $sort_name;
            $group_name = "team";
            break;
        // Technology
        case 7:
            $order_query = "GROUP BY id ORDER BY technology," . $sort_name;
            $group_name = "technology";
            break;
        // Owner
        case 8:
            $order_query = "GROUP BY id ORDER BY owner," . $sort_name;
            $group_name = "owner";
            break;
        // Owners Manager
        case 9:
            $order_query = "GROUP BY id ORDER BY manager," . $sort_name;
            $group_name = "manager";
            break;
        // Risk Scoring Method
        case 10:
            $order_query = "GROUP BY id ORDER BY scoring_method," . $sort_name;
            $group_name = "scoring_method";
            break;
        // Regulation
        case 11:
            $order_query = "GROUP BY id ORDER BY regulation," . $sort_name;
            $group_name = "regulation";
            break;
        // Project
        case 12:
            $order_query = "GROUP BY id ORDER BY project," . $sort_name;
            $group_name = "project";
            break;
        // Next Step
        case 13:
            $order_query = "GROUP BY id ORDER BY next_step," . $sort_name;
            $group_name = "next_step";
            break;
        // Month Submitted
        case 14:
            $order_query = "GROUP BY id ORDER BY submission_date DESC," . $sort_name;
            $group_name = "month_submitted";
            break;
        // Default to calculated risk
        default:
            $order_query = "GROUP BY id ORDER BY" . $sort_name;
            $group_name = "none";
            break;
    }

    // If the team separation extra is not enabled
    if (!team_separation_extra())
    {
        // Make the big query
        $query = "SELECT a.id, a.status, a.subject, a.reference_id, a.control_number, a.submission_date, a.last_update, a.review_date, a.mitigation_id, a.mgmt_review, a.assessment as risk_assessment, a.notes as additional_notes, b.scoring_method, b.calculated_risk, ROUND(b.calculated_risk - (b.calculated_risk * GREATEST(IFNULL(p.mitigation_percent,0), IFNULL(MAX(fc.mitigation_percent), 0)) / 100), 2) as residual_risk, c.name AS location, d.name AS category, GROUP_CONCAT(DISTINCT e.name SEPARATOR ', ') AS team, GROUP_CONCAT(DISTINCT f.name SEPARATOR ', ') AS technology, g.name AS owner, h.name AS manager, i.name AS submitted_by, j.name AS regulation, k.name AS project, l.next_review, m.name AS next_step, GROUP_CONCAT(DISTINCT n.asset SEPARATOR ', ') AS affected_assets, o.closure_date, q.name AS planning_strategy, p.planning_date AS planning_date, r.name AS mitigation_effort, s.min_value AS mitigation_min_cost, s.max_value AS mitigation_max_cost, t.name AS mitigation_owner, u.name AS mitigation_team, p.submission_date AS mitigation_date, v.name AS source, p.current_solution, p.security_recommendations, p.security_requirements
            FROM risks a LEFT JOIN risk_scoring b ON a.id = b.id LEFT JOIN location c ON a.location = c.value LEFT JOIN category d ON a.category = d.value LEFT JOIN team e ON FIND_IN_SET(e.value, a.team) LEFT JOIN technology f ON FIND_IN_SET(f.value, a.technology) LEFT JOIN user g ON a.owner = g.value LEFT JOIN user h ON a.manager = h.value LEFT JOIN user i ON a.submitted_by = i.value LEFT JOIN regulation j ON a.regulation = j.value LEFT JOIN projects k ON a.project_id = k.value LEFT JOIN mgmt_reviews l ON a.mgmt_review = l.id LEFT JOIN next_step m ON l.next_step = m.value LEFT JOIN risks_to_assets n ON a.id = n.risk_id LEFT JOIN closures o ON a.close_id = o.id LEFT JOIN mitigations p ON a.id = p.risk_id LEFT JOIN framework_controls fc ON FIND_IN_SET(fc.id, p.mitigation_controls) LEFT JOIN planning_strategy q ON p.planning_strategy = q.value LEFT JOIN mitigation_effort r ON p.mitigation_effort = r.value LEFT JOIN asset_values s ON p.mitigation_cost = s.id LEFT JOIN user t ON p.mitigation_owner = t.value LEFT JOIN team u ON p.mitigation_team = u.value LEFT JOIN source v ON a.source = v.value " . $status_query . $order_query;
    }
    // Otherwise
    else
    {
        // Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Get the separation query string
        $separation_query = get_user_teams_query("a", false, true);

        // Make the big query
        $query = "SELECT a.id, a.status, a.subject, a.reference_id, a.control_number, a.submission_date, a.last_update, a.review_date, a.mitigation_id, a.mgmt_review, a.assessment as risk_assessment, a.notes as additional_notes, b.scoring_method, b.calculated_risk, ROUND(b.calculated_risk - (b.calculated_risk * GREATEST(IFNULL(p.mitigation_percent,0), IFNULL(MAX(fc.mitigation_percent), 0)) / 100), 2) as residual_risk, c.name AS location, d.name AS category, GROUP_CONCAT(DISTINCT e.name SEPARATOR ', ') AS team, GROUP_CONCAT(DISTINCT f.name SEPARATOR ', ') AS technology, g.name AS owner, h.name AS manager, i.name AS submitted_by, j.name AS regulation, k.name AS project, l.next_review, m.name AS next_step, GROUP_CONCAT(DISTINCT n.asset SEPARATOR ', ') AS affected_assets, o.closure_date, q.name AS planning_strategy, p.planning_date AS planning_date, r.name AS mitigation_effort, s.min_value AS mitigation_min_cost, s.max_value AS mitigation_max_cost, t.name AS mitigation_owner, u.name AS mitigation_team, p.submission_date AS mitigation_date, v.name AS source, p.current_solution, p.security_recommendations, p.security_requirements
        FROM risks a LEFT JOIN risk_scoring b ON a.id = b.id LEFT JOIN location c ON a.location = c.value LEFT JOIN category d ON a.category = d.value LEFT JOIN team e ON FIND_IN_SET(e.value, a.team) LEFT JOIN technology f ON FIND_IN_SET(f.value, a.technology) LEFT JOIN user g ON a.owner = g.value LEFT JOIN user h ON a.manager = h.value LEFT JOIN user i ON a.submitted_by = i.value LEFT JOIN regulation j ON a.regulation = j.value LEFT JOIN projects k ON a.project_id = k.value LEFT JOIN mgmt_reviews l ON a.mgmt_review = l.id LEFT JOIN next_step m ON l.next_step = m.value LEFT JOIN risks_to_assets n ON a.id = n.risk_id LEFT JOIN closures o ON a.close_id = o.id LEFT JOIN mitigations p ON a.id = p.risk_id LEFT JOIN framework_controls fc ON FIND_IN_SET(fc.id, p.mitigation_controls) LEFT JOIN planning_strategy q ON p.planning_strategy = q.value LEFT JOIN mitigation_effort r ON p.mitigation_effort = r.value LEFT JOIN asset_values s ON p.mitigation_cost = s.id LEFT JOIN user t ON p.mitigation_owner = t.value LEFT JOIN team u ON p.mitigation_team = u.value LEFT JOIN source v ON a.source = v.value " . $status_query . $separation_query . $order_query;
    }

    // Query the database
    $db = db_open();
    $stmt = $db->prepare($query);
    $stmt->execute();
    db_close($db);

    // Store the results in the risks array
    $risks = $stmt->fetchAll();

    // Set the current group to empty
    $current_group = "";

    // If the group name is none
    if ($group_name == "none")
    {
        // Display the table header
        echo "<table data-group='' class=\"table risk-datatable table-bordered table-striped table-condensed  table-margin-top\" style='width: 100%'>\n";
        echo "<thead>\n";
        echo "<tr class='main'>\n";

        // Header columns go here
        get_header_columns(false, $column_id, $column_status, $column_subject, $column_reference_id, $column_regulation, $column_control_number, $column_location, $column_source, $column_category, $column_team, $column_technology, $column_owner, $column_manager, $column_submitted_by, $column_scoring_method, $column_calculated_risk, $column_residual_risk, $column_submission_date, $column_review_date, $column_project, $column_mitigation_planned, $column_management_review, $column_days_open, $column_next_review_date, $column_next_step, $column_affected_assets, $column_planning_strategy, $column_planning_date, $column_mitigation_effort, $column_mitigation_cost, $column_mitigation_owner, $column_mitigation_team, $column_mitigation_date, $column_risk_assessment, $column_additional_notes, $column_current_solution, $column_security_recommendations, $column_security_requirements);

        echo "</tr>\n";
        echo "</thead>\n";
        echo "<tbody>\n";
    }
    
    // For each risk in the risks array
    foreach ($risks as $risk)
    {
        $risk_id = (int)$risk['id'];
        $status = $risk['status'];
        $subject = $risk['subject'];
        $reference_id = $risk['reference_id'];
        $control_number = $risk['control_number'];
        $submission_date = $risk['submission_date'];
        $last_update = $risk['last_update'];
        $review_date = $risk['review_date'];
        $scoring_method = get_scoring_method_name($risk['scoring_method']);
        $calculated_risk = (float)$risk['calculated_risk'];
        $residual_risk = (float)$risk['residual_risk'];
        $color = get_risk_color($risk['calculated_risk']);
        $residual_risk_color = get_risk_color($risk['residual_risk']);
        $risk_level = get_risk_level_name($risk['calculated_risk']);
        $residual_risk_level = get_risk_level_name($risk['residual_risk']);
        $location = $risk['location'];
        $source = $risk['source'];
        $category = $risk['category'];
        $team = $risk['team'];
        $technology = $risk['technology'];
        $owner = $risk['owner'];
        $manager = $risk['manager'];
        $submitted_by = $risk['submitted_by'];
        $regulation = $risk['regulation'];
        $project = $risk['project'];
        $mitigation_id = $risk['mitigation_id'];
        $mgmt_review = $risk['mgmt_review'];
        $days_open = dayssince($risk['submission_date']);

        // If next_review_date_uses setting is Residual Risk.
        if(get_setting('next_review_date_uses') == "ResidualRisk")
        {
            $next_review_date = next_review($residual_risk_level, $risk_id, $risk['next_review'], false);
            $next_review_date_html = next_review($residual_risk_level, $risk_id, $risk['next_review']);
        }
        // If next_review_date_uses setting is Inherent Risk.
        else
        {
            $next_review_date = next_review($risk_level, $risk_id, $risk['next_review'], false);
            $next_review_date_html = next_review($risk_level, $risk_id, $risk['next_review']);
        }

        $next_step = $risk['next_step'];
        $affected_assets = $risk['affected_assets'];
        $risk_assessment = $risk['risk_assessment'];
        $additional_notes = $risk['additional_notes'];
        $current_solution = $risk['current_solution'];
        $security_recommendations = $risk['security_recommendations'];
        $security_requirements = $risk['security_requirements'];
        $month_submitted = date('Y F', strtotime($risk['submission_date']));
        $planning_strategy = $risk['planning_strategy'];
        $planning_date = ($risk['planning_date'] && $risk['planning_date'] != "0000-00-00") ? date(get_default_date_format(), strtotime($risk['planning_date'])) : "";
        $mitigation_effort = $risk['mitigation_effort'];
        $mitigation_min_cost = $risk['mitigation_min_cost'];
        $mitigation_max_cost = $risk['mitigation_max_cost'];
        //$mitigation_cost = "$" . $mitigation_min_cost . " to $" . $mitigation_max_cost;
        $mitigation_cost = $risk['mitigation_min_cost'];
        $mitigation_owner = $risk['mitigation_owner'];
        $mitigation_team = $risk['mitigation_team'];
        $mitigation_date = date(get_default_date_format(), strtotime($risk['mitigation_date']));

        // If the group name is not none
        if ($group_name != "none")
        {
            $group_value = ${$group_name};
            
            switch($group_name){
                case "risk_level":
                    $group_value_from_db = $risk['calculated_risk'];
                break;
                case "month_submitted":
                    $group_value_from_db = $risk['submission_date'];
                break;
                default:
                    $group_value_from_db = $risk[$group_name];
                break;
            }
            
            // If the selected group value is empty
            if ($group_value == "")
            {
                // Current group is Unassigned
                $group_value = $lang['Unassigned'];
            }

            // If the group is not the current group
            if ($group_value != $current_group)
            {
                // If this is not the first group
                if ($current_group != "")
                {
                    echo "</tbody>\n";
                    echo "</table>\n";
                    echo "<br />\n";
                }

                // If the group is not empty
                if ($group_value != "")
                {
                    // Set the group to the current group
                    $current_group = $group_value;
                }
                else $current_group = $lang['Unassigned'];

                // Display the table header
                echo "<table data-group='".$escaper->escapeHtml($group_value_from_db)."' class=\"table risk-datatable table-bordered table-striped table-condensed  table-margin-top\" style='width: 100%'>\n";
                echo "<thead>\n";
                echo "<tr>\n";
                echo "<th bgcolor=\"#0088CC\" colspan=\"37\"><center>". $escaper->escapeHtml($current_group) ."</center></th>\n";
                echo "</tr>\n";
                echo "<tr class='main'>\n";

                // Header columns go here
                get_header_columns(false, $column_id, $column_status, $column_subject, $column_reference_id, $column_regulation, $column_control_number, $column_location, $column_source, $column_category, $column_team, $column_technology, $column_owner, $column_manager, $column_submitted_by, $column_scoring_method, $column_calculated_risk, $column_residual_risk, $column_submission_date, $column_review_date, $column_project, $column_mitigation_planned, $column_management_review, $column_days_open, $column_next_review_date, $column_next_step, $column_affected_assets, $column_planning_strategy, $column_planning_date, $column_mitigation_effort, $column_mitigation_cost, $column_mitigation_owner, $column_mitigation_team, $column_mitigation_date, $column_risk_assessment, $column_additional_notes, $column_current_solution, $column_security_recommendations, $column_security_requirements);

                echo "</tr>\n";
                echo "</thead>\n";
                echo "<tbody>\n";
            }
        }

        // Display the risk information
//        echo "<tr>\n";

        // Risk information goes here
//        get_risk_columns($risk, $column_id, $column_status, $column_subject, $column_reference_id, $column_regulation, $column_control_number, $column_location, $column_source, $column_category, $column_team, $column_technology, $column_owner, $column_manager, $column_submitted_by, $column_scoring_method, $column_calculated_risk, $column_submission_date, $column_review_date, $column_project, $column_mitigation_planned, $column_management_review, $column_days_open, $column_next_review_date, $column_next_step, $column_affected_assets, $column_planning_strategy, $column_mitigation_effort, $column_mitigation_cost, $column_mitigation_owner, $column_mitigation_team, $column_risk_assessment, $column_additional_notes, $column_current_solution, $column_security_recommendations, $column_security_requirements);

//        echo "</tr>\n";
    
    }

    // If the group name is none
    if ($group_name == "none")
    {
        // End the table
        echo "</tbody>\n";
        echo "</table>\n";
        echo "<br />\n";
    }
}

/********************************
 * FUNCTION: GET HEADER COLUMNS *
 ********************************/
function get_header_columns($hide, $id, $risk_status, $subject, $reference_id, $regulation, $control_number, $location, $source, $category, $team, $technology, $owner, $manager, $submitted_by, $scoring_method, $calculated_risk, $residual_risk, $submission_date, $review_date, $project, $mitigation_planned, $management_review, $days_open, $next_review_date, $next_step, $affected_assets, $planning_strategy, $planning_date, $mitigation_effort, $mitigation_cost, $mitigation_owner, $mitigation_team, $mitigation_date, $risk_assessment, $additional_notes, $current_solution, $security_recommendations, $security_requirements)
{
    global $lang;
    global $escaper;
    
    if($hide){
        $display = "display: none;";
    }else{
        $display = "display: table-cell;";
    }
    
    echo "<th class=\"id\" data-name='id' " . ($id == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"25px\">". $escaper->escapeHtml($lang['ID']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"status\" data-name='risk_status' " . ($risk_status == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['Status']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"subject\" data-name='subject' " . ($subject == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['Subject']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"reference_id\" data-name='reference_id' " . ($reference_id == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['ExternalReferenceId']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"regulation\" data-name='regulation' " . ($regulation == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['ControlRegulation']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"control_number\" data-name='control_number' " . ($control_number == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['ControlNumber']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"location\" data-name='location' " . ($location == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['SiteLocation']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"source\" data-name='source' " . ($source == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['RiskSource']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"category\" data-name='category' " . ($category == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['Category']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"team\" data-name='team' " . ($team == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['Team']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"technology\" data-name='technology' " . ($technology == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['Technology']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"owner\" data-name='owner' " . ($owner == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['Owner']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"manager\" data-name='manager' " . ($manager == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['OwnersManager']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"submitted_by\" data-name='submitted_by' " . ($submitted_by == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['SubmittedBy']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"scoring_method\" data-name='scoring_method' " . ($scoring_method == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['RiskScoringMethod']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"calculated_risk\" data-name='calculated_risk' " . ($calculated_risk == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"25px\">". $escaper->escapeHtml($lang['InherentRisk']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"residual_risk\" data-name='residual_risk' " . ($residual_risk == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"25px\">". $escaper->escapeHtml($lang['ResidualRisk']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"submission_date\" data-name='submission_date' " . ($submission_date == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['DateSubmitted']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"review_date\" data-name='review_date' " . ($review_date == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['ReviewDate']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"project\" data-name='project' " . ($project == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['Project']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"mitigation_planned\" data-name='mitigation_planned' " . ($mitigation_planned == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['MitigationPlanned']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"management_review\" data-name='management_review' " . ($management_review == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['ManagementReview']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"days_open\" data-name='days_open' " . ($days_open == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['DaysOpen']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"next_review_date\" data-name='next_review_date' " . ($next_review_date == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['NextReviewDate']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"next_step\" data-name='next_step' " . ($next_step == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['NextStep']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"affected_assets\" data-name='affected_assets' " . ($affected_assets == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['AffectedAssets']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"risk_assessment\" data-name='risk_assessment' " . ($risk_assessment == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['RiskAssessment']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"additional_notes\" data-name='additional_notes' " . ($additional_notes == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['AdditionalNotes']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"current_solution\" data-name='current_solution' " . ($current_solution == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['CurrentSolution']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"security_recommendations\" data-name='security_recommendations' " . ($security_recommendations == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['SecurityRecommendations']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"security_requirements\" data-name='security_requirements' " . ($security_requirements == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['SecurityRequirements']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"planning_strategy\" data-name='planning_strategy' " . ($planning_strategy == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['PlanningStrategy']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"planning_date\" data-name='planning_date' " . ($planning_date == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['MitigationPlanning']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"mitigation_effort\" data-name='mitigation_effort' " . ($mitigation_effort == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['MitigationEffort']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"mitigation_cost\" data-name='mitigation_cost' " . ($mitigation_cost== true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['MitigationCost']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"mitigation_owner\" data-name='mitigation_owner' " . ($mitigation_owner== true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['MitigationOwner']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"mitigation_team\" data-name='mitigation_team' " . ($mitigation_team == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['MitigationTeam']) ."<!-- span class='fa'></span --></th>\n";
    echo "<th class=\"mitigation_date\" data-name='mitigation_date' " . ($mitigation_date == true ? "" : "style=\"{$display}\" ") . "align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['MitigationDate']) ."<!-- span class='fa'></span --></th>\n";
}

/******************************
 * FUNCTION: GET RISK COLUMNS *
 ******************************/
function get_risk_columns($risk, $column_id, $column_status, $column_subject, $column_reference_id, $column_regulation, $column_control_number, $column_location, $column_source, $column_category, $column_team, $column_technology, $column_owner, $column_manager, $column_submitted_by, $column_scoring_method, $column_calculated_risk, $column_residual_risk, $column_submission_date, $column_review_date, $column_project, $column_mitigation_planned, $column_management_review, $column_days_open, $column_next_review_date, $column_next_step, $column_affected_assets, $column_planning_strategy, $column_mitigation_effort, $column_mitigation_cost, $column_mitigation_owner, $column_mitigation_team, $column_risk_assessment, $column_additional_notes, $column_current_solution, $column_security_recommendations, $column_security_requirements)
{
    global $lang;
    global $escaper;

    $risk_id = (int)$risk['id'];
    $status = $risk['status'];
    $subject = try_decrypt($risk['subject']);
    $reference_id = $risk['reference_id'];
    $control_number = $risk['control_number'];
    $submission_date = $risk['submission_date'];
    $last_update = $risk['last_update'];
    $review_date = $risk['review_date'];
    $scoring_method = get_scoring_method_name($risk['scoring_method']);
    $calculated_risk = $risk['calculated_risk'];
    $residual_risk = $risk['residual_risk'];
    $color = get_risk_color($risk['calculated_risk']);
    $residual_color = get_risk_color($risk['residual_risk']);
    $risk_level = get_risk_level_name($risk['calculated_risk']);
    $residual_risk_level = get_risk_level_name($risk['residual_risk']);
    $location = $risk['location'];
    $source = $risk['source'];
    $category = $risk['category'];
    $team = $risk['team'];
    $technology = $risk['technology'];
    $owner = $risk['owner'];
    $manager = $risk['manager'];
    $submitted_by = $risk['submitted_by'];
    $regulation = $risk['regulation'];
    $project = try_decrypt($risk['project']);
    $mitigation_id = $risk['mitigation_id'];
    $mgmt_review = $risk['mgmt_review'];

    // If the status is not closed
    if ($status != "Closed")
    {
        // Compare submission date to now
        $days_open = dayssince($risk['submission_date']);
    }
    // Otherwise the status is closed
    else
    {
        // Compare the submission date to the closure date
        $days_open = dayssince($risk['submission_date'], $risk['closure_date']);
    }

    // If next_review_date_uses setting is Residual Risk.
    if(get_setting('next_review_date_uses') == "ResidualRisk")
    {
        $next_review_date = next_review($residual_risk_level, $risk_id, $risk['next_review'], false);
        $next_review_date_html = next_review($residual_risk_level, $risk_id, $risk['next_review']);
    }
    // If next_review_date_uses setting is Inherent Risk.
    else
    {
        $next_review_date = next_review($risk_level, $risk_id, $risk['next_review'], false);
        $next_review_date_html = next_review($risk_level, $risk_id, $risk['next_review']);
    }

    $next_step = $risk['next_step'];
    $affected_assets = $risk['affected_assets'];
    $risk_assessment = try_decrypt($risk['risk_assessment']);
    $additional_notes = try_decrypt($risk['additional_notes']);
    $current_solution = try_decrypt($risk['current_solution']);
    $security_recommendations = try_decrypt($risk['security_recommendations']);
    $security_requirements = try_decrypt($risk['security_requirements']);
    $planning_strategy = $risk['planning_strategy'];
    $mitigation_effort = $risk['mitigation_effort'];
    $mitigation_min_cost = $risk['mitigation_min_cost'];
    $mitigation_max_cost = $risk['mitigation_max_cost'];

    // If the mitigation costs are empty
    if (empty($mitigation_min_cost) && empty($mitigation_max_cost))
    {
        // Return no value
        $mitigation_cost = "";
    }
    else $mitigation_cost = "$" . $mitigation_min_cost . " to $" . $mitigation_max_cost;

    $mitigation_owner = $risk['mitigation_owner'];
    $mitigation_team = $risk['mitigation_team'];

    // If the risk hasn't been reviewed yet
    if ($review_date == "0000-00-00 00:00:00")
    {
        // Set the review date to empty
        $review_date = "";
    }
    // Otherwise set the review date to the proper format
    else $review_date = date(get_default_datetime_format("H:i"), strtotime($review_date));

    echo "<td class=\"id\" " . ($column_id == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"25px\"><a href=\"../management/view.php?id=" . $escaper->escapeHtml(convert_id($risk_id)) . "\" target=\"_blank\">" . $escaper->escapeHtml(convert_id($risk_id)) . "</a></td>\n";
    echo "<td class=\"status\" " . ($column_status == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($status) . "</td>\n";
    echo "<td class=\"subject\" " . ($column_subject == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"300px\">" . $escaper->escapeHtml($subject) . "</td>\n";
    echo "<td class=\"reference_id\" " . ($column_reference_id == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($reference_id) . "</td>\n";
    echo "<td class=\"regulation\" " . ($column_regulation == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($regulation) . "</td>\n";
    echo "<td class=\"control_number\" " . ($column_control_number == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($control_number) . "</td>\n";
    echo "<td class=\"location\" " . ($column_location == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($location) . "</td>\n";
    echo "<td class=\"source\" " . ($column_source == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($source) . "</td>\n";
    echo "<td class=\"category\" " . ($column_category == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($category) . "</td>\n";
    echo "<td class=\"team\" " . ($column_team == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($team) . "</td>\n";
    echo "<td class=\"technology\" " . ($column_technology == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($technology) . "</td>\n";
    echo "<td class=\"owner\" " . ($column_owner == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($owner) . "</td>\n";
    echo "<td class=\"manager\" " . ($column_manager == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($manager) . "</td>\n";
    echo "<td class=\"submitted_by\" " . ($column_submitted_by == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($submitted_by) . "</td>\n";
    echo "<td class=\"scoring_method\" " . ($column_scoring_method == true ? "" : "style=\"display:tnone;\" ") . "align=\"left\" width=\"50px\">" . $escaper->escapeHtml($scoring_method) . "</td>\n";
    echo "<td class=\"calculated_risk risk-cell ".$escaper->escapeHtml($color)." \" " . ($column_calculated_risk == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" bgcolor=\"" . $escaper->escapeHtml($color) . "\" width=\"25px\"><div class='risk-cell-holder'>" . $escaper->escapeHtml($risk['calculated_risk']) . "<span class=\"risk-color\" style=\"background-color:" . $escaper->escapeHtml($color) . "\"></span></div>"."</td>\n";
    echo "<td class=\"residual_risk risk-cell ".$escaper->escapeHtml($residual_color)." \" " . ($column_residual_risk == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" bgcolor=\"" . $escaper->escapeHtml($residual_color) . "\" width=\"25px\"><div class='risk-cell-holder'>" . $escaper->escapeHtml($risk['residual_risk']) . "<span class=\"risk-color\" style=\"background-color:" . $escaper->escapeHtml($residual_color) . "\"></span></div>"."</td>\n";
    echo "<td class=\"submission_date\" " . ($column_submission_date == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml(date(get_default_datetime_format("H:i"), strtotime($submission_date))) . "</td>\n";
    echo "<td class=\"review_date\" " . ($column_review_date == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($review_date) . "</td>\n";
    echo "<td class=\"project\" " . ($column_project == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($project) . "</td>\n";
    echo "<td class=\"mitigation_planned\" " . ($column_mitigation_planned == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . planned_mitigation(convert_id($risk_id), $mitigation_id) . "</td>\n";
    echo "<td class=\"management_review\" " . ($column_management_review == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . management_review(convert_id($risk_id), $mgmt_review, $next_review_date) . "</td>\n";
    echo "<td class=\"days_open\" " . ($column_days_open == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($days_open) . "</td>\n";
    echo "<td class=\"next_review_date\" " . ($column_next_review_date == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $next_review_date_html . "</td>\n";
    echo "<td class=\"next_step\" " . ($column_next_step == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($next_step) . "</td>\n";
    echo "<td class=\"affected_assets\" " . ($column_affected_assets == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($affected_assets) . "</td>\n";
        echo "<td class=\"risk_assessment\" " . ($column_risk_assessment == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($risk_assessment) . "</td>\n";
    echo "<td class=\"additional_notes\" " . ($column_additional_notes == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($additional_notes) . "</td>\n";
    echo "<td class=\"current_solution\" " . ($column_current_solution == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($current_solution) . "</td>\n";
    echo "<td class=\"security_recommendations\" " . ($column_security_recommendations == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($security_recommendations) . "</td>\n";
    echo "<td class=\"security_requirements\" " . ($column_security_requirements == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($security_requirements) . "</td>\n";
    echo "<td class=\"planning_strategy\" " . ($column_planning_strategy == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($planning_strategy) . "</td>\n";
    echo "<td class=\"mitigation_effort\" " . ($column_mitigation_effort == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($mitigation_effort) . "</td>\n";
    echo "<td class=\"mitigation_cost\" " . ($column_mitigation_cost == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($mitigation_cost) . "</td>\n";
    echo "<td class=\"mitigation_owner\" " . ($column_mitigation_owner == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($mitigation_owner) . "</td>\n";
    echo "<td class=\"mitigation_team\" " . ($column_mitigation_team == true ? "" : "style=\"display:tnone;\" ") . "align=\"center\" width=\"150px\">" . $escaper->escapeHtml($mitigation_team) . "</td>\n";
}

/******************************
 * FUNCTION: GET RISK COLUMNS *
 ******************************/
function get_risk_columns_new($hide, $risk, $column_id, $column_status, $column_subject, $column_reference_id, $column_regulation, $column_control_number, $column_location, $column_source, $column_category, $column_team, $column_technology, $column_owner, $column_manager, $column_submitted_by, $column_scoring_method, $column_calculated_risk, $column_residual_risk, $column_submission_date, $column_review_date, $column_project, $column_mitigation_planned, $column_management_review, $column_days_open, $column_next_review_date, $column_next_step, $column_affected_assets, $column_planning_strategy, $column_planning_date, $column_mitigation_effort, $column_mitigation_cost, $column_mitigation_owner, $column_mitigation_team, $column_mitigation_date, $column_risk_assessment, $column_additional_notes, $column_current_solution, $column_security_recommendations, $column_security_requirements)
{
    global $lang;
    global $escaper;
    
    if($hide){
        $display = "display: none;";
    }else{
        $display = "display: table-cell;";
    }
    
    $risk['id'] = convert_id($risk['id']);
    $color = get_risk_color($risk['calculated_risk']);
    $residual_color = get_risk_color($risk['residual_risk']);
    $planning_date = ($risk['planning_date'] && $risk['planning_date'] != "0000-00-00") ? date(get_default_date_format(), strtotime($risk['planning_date'])) : "";
        
    echo "
        <td class=\"id\" " . ($column_id == true ? "" : "style=\"{$display}\" ") . "align=\"left\" ><a href=\"../management/view.php?id=" . $escaper->escapeHtml($risk['id']) . "\" target=\"_blank\">".$escaper->escapeHtml($risk['id'])."</a></td>
        <td class=\"status\" " . ($column_status == true ? "" : "style=\"{$display}\" ") . "align=\"left\">".$escaper->escapeHtml($risk['status'])."</td>
        <td class=\"subject\" " . ($column_subject == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['subject'])."</td>
        <td class=\"reference_id\" " . ($column_reference_id == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['reference_id'])."</td>
        <td class=\"regulation\" " . ($column_regulation == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['regulation'])."</td>
        <td class=\"control_number\" " . ($column_control_number == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['control_number'])."</td>
        <td class=\"location\" " . ($column_location == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['location'])."</td>
        <td class=\"source\" " . ($column_source == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['source'])."</td>
        <td class=\"category\" " . ($column_category == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['category'])."</td>
        <td class=\"team\" " . ($column_team == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['team'])."</td>
        <td class=\"technology\" " . ($column_technology == true ? "" : "style=\"{$display}\" ") . "align=\"left\">".$escaper->escapeHtml($risk['technology'])."</td>
        <td class=\"owner\" " . ($column_owner == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['owner'])."</td>
        <td class=\"manager\" " . ($column_manager == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['manager'])."</td>
        <td class=\"submitted_by\" " . ($column_submitted_by == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['submitted_by'])."</td>
        <td class=\"scoring_method\" " . ($column_scoring_method == true ? "" : "style=\"{$display}\" ") . "align=\"left\" >".$escaper->escapeHtml($risk['scoring_method'])."</td>
        <td class=\"calculated_risk risk-cell \" " . ($column_calculated_risk == true ? "" : "style=\"{$display}\" ") . "align=\"center\" bgcolor=\"" . $escaper->escapeHtml($color) . "\"><div class='".$escaper->escapeHtml($risk['color'])."'><div class='risk-cell-holder'>" . $escaper->escapeHtml($risk['calculated_risk']) . "<span class=\"risk-color\" style=\"background-color:" . $escaper->escapeHtml($color) . "\"></span></div></div></td>
        <td class=\"residual_risk risk-cell \" " . ($column_residual_risk == true ? "" : "style=\"{$display}\" ") . "align=\"center\" bgcolor=\"" . $escaper->escapeHtml($residual_color) . "\"><div class='".$escaper->escapeHtml($risk['residual_color'])."'><div class='risk-cell-holder'>" . $escaper->escapeHtml($risk['residual_risk']) . "<span class=\"risk-color\" style=\"background-color:" . $escaper->escapeHtml($residual_color) . "\"></span></div></div></td>
        <td class=\"submission_date\" " . ($column_submission_date == true ? "" : "style=\"{$display}\" ") . "align=\"center\" >".$escaper->escapeHtml(date(get_default_datetime_format("H:i"), strtotime($risk['submission_date'])))."</td>
        <td class=\"review_date\" " . ($column_review_date == true ? "" : "style=\"{$display}\" ") . "align=\"center\" >".$escaper->escapeHtml($risk['review_date'])."</td>
        <td class=\"project\" " . ($column_project == true ? "" : "style=\"{$display}\" ") . "align=\"center\">".$escaper->escapeHtml($risk['project'])."</td>
        <td class=\"mitigation_planned\" " . ($column_mitigation_planned == true ? "" : "style=\"{$display}\" ") . "align=\"center\">".planned_mitigation($risk['id'], $risk['mitigation_id'])."</td>
        <td class=\"management_review\" " . ($column_management_review == true ? "" : "style=\"{$display}\" ") . "align=\"center\">".management_review($risk['id'], $risk['mgmt_review'], $risk['next_review_date'])."</td>
        <td class=\"days_open\" " . ($column_days_open == true ? "" : "style=\"{$display}\" ") . "align=\"center\">".$escaper->escapeHtml($risk['days_open'])."</td>
        <td class=\"next_review_date\" " . ($column_next_review_date == true ? "" : "style=\"{$display}\" ") . "align=\"center\">".$risk['next_review_date_html']."</td>
        <td class=\"next_step\" " . ($column_next_step == true ? "" : "style=\"{$display}\" ") . "align=\"center\" >".$escaper->escapeHtml($risk['next_step'])."</td>
        <td class=\"affected_assets\" " . ($column_affected_assets == true ? "" : "style=\"{$display}\" ") . "align=\"center\">".$escaper->escapeHtml($risk['affected_assets'])."</td>
        <td class=\"risk_assessment\" " . ($column_risk_assessment == true ? "" : "style=\"{$display}\" ") . "align=\"center\" >".$escaper->escapeHtml($risk['risk_assessment'])."</td>
        <td class=\"additional_notes\" " . ($column_additional_notes == true ? "" : "style=\"{$display}\" ") . "align=\"center\" >".$escaper->escapeHtml($risk['additional_notes'])."</td>
        <td class=\"current_solution\" " . ($column_current_solution == true ? "" : "style=\"{$display}\" ") . "align=\"center\" >".$escaper->escapeHtml($risk['current_solution'])."</td>
        <td class=\"security_recommendations\" " . ($column_security_recommendations == true ? "" : "style=\"{$display}\" ") . "align=\"center\">".$escaper->escapeHtml($risk['security_recommendations'])."</td>
        <td class=\"security_requirements\" " . ($column_security_requirements == true ? "" : "style=\"{$display}\" ") . "align=\"center\" >".$escaper->escapeHtml($risk['security_requirements'])."</td>
        <td class=\"planning_strategy\" " . ($column_planning_strategy == true ? "" : "style=\"{$display}\" ") . "align=\"center\" >".$escaper->escapeHtml($risk['planning_strategy'])."</td>
	<td class=\"planning_date\" " . ($column_planning_date == true ? "" : "style=\"{$display}\" ") . "align=\"center\" >".$planning_date."</td>
        <td class=\"mitigation_effort\" " . ($column_mitigation_effort == true ? "" : "style=\"{$display}\" ") . "align=\"center\" >".$escaper->escapeHtml($risk['mitigation_effort'])."</td>
        <td class=\"mitigation_cost\" " . ($column_mitigation_cost == true ? "" : "style=\"{$display}\" ") . "align=\"center\" >".$escaper->escapeHtml($risk['mitigation_cost'])."</td>
        <td class=\"mitigation_owner\" " . ($column_mitigation_owner == true ? "" : "style=\"{$display}\" ") . "align=\"center\">".$escaper->escapeHtml($risk['mitigation_owner'])."</td>
        <td class=\"mitigation_team\" " . ($column_mitigation_team == true ? "" : "style=\"{$display}\" ") . "align=\"center\">".$escaper->escapeHtml($risk['mitigation_team'])."</td>
        <td class=\"mitigation_date\" " . ($column_mitigation_date == true ? "" : "style=\"{$display}\" ") . "align=\"center\">".$escaper->escapeHtml($risk['mitigation_date'])."</td>
        
    ";
    
}

/**********************************
 * FUNCTION: TABLE OF RISK BY TEAM *
 *********************************/
function risk_table_open_by_team($teamOptions, $ownerOptions, $managerOptions, $currentpageParam, $column_id=true, $column_status=false, $column_subject=true, $column_reference_id=false, $column_regulation=false, $column_control_number=false, $column_location=false, $column_source=false, $column_category=false, $column_team=false, $column_technology=false, $column_owner=false, $column_manager=false, $column_submitted_by=false, $column_scoring_method=false, $column_calculated_risk=true, $column_residual_risk=false, $column_submission_date=true, $column_review_date=false, $column_project=false, $column_mitigation_planned=true, $column_management_review=true, $column_days_open=false, $column_next_review_date=false, $column_next_step=false, $column_affected_assets=false, $column_planning_strategy=false, $column_planning_date=false, $column_mitigation_effort=false, $column_mitigation_cost=false, $column_mitigation_owner=false, $column_mitigation_team=false, $column_mitigation_date=false, $column_risk_assessment=false, $column_additional_notes=false, $column_current_solution=false, $column_security_recommendations=false, $column_security_requirements=false){
    global $lang;
    global $escaper;

    $team_querys = array();
    
    $params = array();
    // If at least one team was selected
    if($teamOptions != ""){
        $teams = explode(",", $teamOptions);
        
        $teamsArray = array();
        foreach($teams as $team){
            $params[] = $team;
            $teamsArray[] = "a.team = :param". (count($params)-1);
        }
        $team_query_string = implode(" OR ", $teamsArray);
//        $team_query .= ' OR ('.$team_query_string.') ';
        array_push($team_querys, $team_query_string);
    }

    // If at least one owner was selected
    if($ownerOptions != ""){
        $owners = explode(",", $ownerOptions);

        $teamsArray = array();
        foreach($owners as $owner){
            $params[] = $owner;
            $teamsArray[] = "a.owner = :param". (count($params)-1);
        }
        $team_query_string = implode(" OR ", $teamsArray);
//        $team_query .= ' OR ('.$team_query_string.') ';
        array_push($team_querys, $team_query_string);
    }
                    
    // If at least one owner's manager was selected
    if($managerOptions != ""){
        $ownersmanagers = explode(",", $managerOptions);

        $teamsArray = array();
        foreach($ownersmanagers as $ownersmanager){
            $params[] = $ownersmanager;
            $teamsArray[] = "a.manager = :param". (count($params)-1);
        }
        $team_query_string = implode(" OR ", $teamsArray);
        array_push($team_querys, $team_query_string);
    }
    $team_query = implode(" OR ", $team_querys);
    if($team_query){
        $team_query = " AND (". $team_query . ")";
    }else{
        $team_query = " AND 0 ";
    }
    
    // number of rows to show per page
    $rowsperpage = 10;

    // get the current page or set a default
    if ($currentpageParam && is_numeric($currentpageParam)) {
       // cast var as int
       $currentpage = (int) $currentpageParam;
    } else {
       // default page num
       $currentpage = 1;
    } // end if

    // the offset of the list, based on current page
    $offset = ($currentpage - 1) * $rowsperpage;
    
    /**
    * Get Risks
    * 
    * params: status, sort, group, &totalRows, start, length, group_name_by_seperation, custom_query
    * status: non-closed
    * sort: calculated_risk desc
    * group: none
    */
    $count; // total counts

    if($currentpageParam == "all"){
        $risks = risks_query(0, 0, 0, 0, $count, 0, -1, "", $team_query, $params);
    }else{
        $risks = risks_query(0, 0, 0, 0, $count, $offset, $rowsperpage, "", $team_query, $params);
    }

    
    // find out total pages
    $totalpages = ceil($count / $rowsperpage);


    // if current page is greater than total pages...
    if ($currentpage > $totalpages) {
       // set current page to last page
       $currentpage = $totalpages;
    } // end if
    // if current page is less than first page...
    if ($currentpage < 1) {
       // set current page to first page
       $currentpage = 1;
    } // end if


    $all_style = '';
    if($currentpageParam == 'all') {
        $offset = 0;
        $rowsperpage = $count;
        $currentpage = -1;
        $all_style = 'class="active"';   
    }

    
    echo "<div class=\"table-scroll\">\n";
    echo "<table class=\"table table-bordered table-striped table-condensed sortable\">\n";
    echo "<thead>\n";
    echo "<tr>\n";
    
    echo get_header_columns(true, $column_id, $column_status, $column_subject, $column_reference_id, $column_regulation, $column_control_number, $column_location, $column_source, $column_category, $column_team, $column_technology, $column_owner, $column_manager, $column_submitted_by, $column_scoring_method, $column_calculated_risk, $column_residual_risk, $column_submission_date, $column_review_date, $column_project, $column_mitigation_planned, $column_management_review, $column_days_open, $column_next_review_date, $column_next_step, $column_affected_assets, $column_planning_strategy, $column_planning_date, $column_mitigation_effort, $column_mitigation_cost, $column_mitigation_owner, $column_mitigation_team, $column_mitigation_date, $column_risk_assessment, $column_additional_notes, $column_current_solution, $column_security_recommendations, $column_security_requirements);

    echo "</thead>\n";
    echo "<tbody>\n";

    // For each risk
    foreach($risks as $risk){
        echo "<tr>\n";
        get_risk_columns_new(true, $risk, $column_id, $column_status, $column_subject, $column_reference_id, $column_regulation, $column_control_number, $column_location, $column_source, $column_category, $column_team, $column_technology, $column_owner, $column_manager, $column_submitted_by, $column_scoring_method, $column_calculated_risk, $column_residual_risk, $column_submission_date, $column_review_date, $column_project, $column_mitigation_planned, $column_management_review, $column_days_open, $column_next_review_date, $column_next_step, $column_affected_assets, $column_planning_strategy, $column_planning_date, $column_mitigation_effort, $column_mitigation_cost, $column_mitigation_owner, $column_mitigation_team, $column_mitigation_date, $column_risk_assessment, $column_additional_notes, $column_current_solution, $column_security_recommendations, $column_security_requirements);
        echo "</tr>\n";
    }

    echo "</tbody>\n";
    echo "</table>\n";
    echo "</div>\n";

    echo "<div class=\"pagination clearfix\"><ul class=\"pull-right\">";
    // range of num links to show
    $range = 3;


    if (!empty ($risks))
    {

        // if not on page 1, don't show back links
        if ($currentpage > 1) {
           // show << link to go back to page 1
           echo "<li><a href='{$_SERVER['SCRIPT_NAME']}?currentpage=1' class=\"no-bg\"><i class=\"fa fa-chevron-left\"></i><i class=\"fa fa-chevron-left\"></i></a></li>";
           // get previous page num
           $prevpage = $currentpage - 1;
           // show < link to go back to 1 page
           echo " <li><a href='{$_SERVER['SCRIPT_NAME']}?currentpage={$prevpage}' class=\"no-bg\"><i class=\"fa fa-chevron-left\"></i></a></li> ";
        } else {// end if
           echo " <li><a href='javascript:void();' class=\"no-bg\"><i class=\"fa fa-chevron-left\"></i></a></li> ";
        }

        // loop to show links to range of pages around current page
        for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
           // if it's a valid page number...
           if (($x > 0) && ($x <= $totalpages)) {
              // if we're on current page...
              if ($x == $currentpage) {
                 // 'highlight' it but don't make a link
                 echo "<li class=\"active\"><a href=\"#\">{$x}</a></li>";
              // if not current page...
              } else {
                 // make it a link
                 echo " <li><a href='{$_SERVER['SCRIPT_NAME']}?currentpage={$x}'>{$x}</a></li> ";
              } // end else
           } // end if
        } // end for

        // if not on last page, show forward and last page links
        if ($currentpage != $totalpages) {
           // get next page
           $nextpage = $currentpage + 1;
            // echo forward link for next page
           echo " <li><a href='{$_SERVER['SCRIPT_NAME']}?currentpage={$nextpage}' class=\"no-bg\"><i class=\"fa fa-chevron-right\"></i></a></li> ";
           // echo forward link for lastpage
          echo "<li><a href='{$_SERVER['SCRIPT_NAME']}?currentpage={$totalpages}' class=\"no-bg\"><i class=\"fa fa-chevron-right\"></i><i class=\"fa fa-chevron-right\"></i></a></li>";
        } else { // end if
           echo " <li><a href='javascript:void(0);' class=\"no-bg\"><i class=\"fa fa-chevron-right\"></i></a></li> ";
        }
        /****** end build pagination links ******/
    }

    echo " <li {$all_style}><a href='{$_SERVER['SCRIPT_NAME']}?currentpage=all'>All</a></li> ";

    echo "</ul></div>";

    return true;
    
}

/**********************************
 * FUNCTION: RISKS BY MONTH TABLE *
 **********************************/
function risks_by_month_table($months)
{
    $months = $months -1;
    global $escaper;
    global $lang;

    // Get the opened risks array by month
    $opened_risks = get_opened_risks_array("month");
    $open_date = $opened_risks[0];
    $open_count = $opened_risks[1];

    // Get the closed risks array by month
    $closed_risks = get_closed_risks_array("month");
    $close_date = $closed_risks[0];
    $close_count = $closed_risks[1];

    echo "<table name=\"risks_by_month\" class=\"table table-hover table-sm table-responsive\">\n";
    echo "<thead>\n";
    echo "<tr>\n";
    echo "<th scope=\"col\"></th>\n";

    // For each of the past 12 months
    for ($i=$months; $i>=0; $i--)
    {
        // Get the month
        $month = date('Y M', strtotime("first day of -$i month"));

        echo "<th scope=\"col\">" . $escaper->escapeHtml($month) . "</th>\n";
    }

    echo "</tr>\n";
    echo "</thead>\n";
    echo "<tbody>\n";
    echo "<tr>\n";
    echo "<th scope=\"row\">" . $escaper->escapeHtml($lang['OpenedRisks']) . "</th>\n";

    // For each of the past 12 months
    for ($i=$months; $i>=0; $i--)
    {
        // Get the month
        $month = date('Y-m', strtotime("first day of -$i month"));
        
        // Search the open risks array
        $key = array_search($month, $open_date);

        // If no result was found or the key is null
        if ($key === false || is_null($key))
        {
            // Set the value to 0
            $open[$i] = 0;
        }
        // Otherwise, use the value found
        else $open[$i] = $open_count[$key];

        echo "<td>" . $escaper->escapeHtml($open[$i]) . "</td>\n";
    }

    echo "</tr>\n";
    echo "<tr>\n";
    echo "<th scope=\"row\">" . $escaper->escapeHtml($lang['ClosedRisks']) . "</th>\n";

    // For each of the past 12 months
    for ($i=$months; $i>=0; $i--)
    {
        // Get the month
        $month = date('Y-m', strtotime("first day of -$i month"));

        // Search the closed risks array
        $key = array_search($month, $close_date);

        // If no result was found or the key is null
        if ($key === false || is_null($key))
        {
            // Set the value to 0
            $close[$i] = 0;
        }
        // Otherwise, use the value found
        else $close[$i] = $close_count[$key];

        echo "<td>" . $escaper->escapeHtml($close[$i]) . "</td>\n";
    }

    echo "</tr>\n";
    echo "<tr>\n";
    echo "<th scope=\"row\">" . $escaper->escapeHtml($lang['RiskTrend']) . "</th>\n";

    // For each of the past 12 months
    for ($i=$months; $i>=0; $i--)
    {
        // Subtract the open number from the closed number
        $total[$i] = $open[$i] - $close[$i];

        // If the total is positive
        if ($total[$i] > 0)
        {
            // Display it in red
            $total_string = "<font color=\"red\">+" . $total[$i] . "</font>";
        }
        // If the total is negative
        else if ($total[$i] < 0)
        {
            // Display it in green
            $total_string = "<font color=\"green\">" . $total[$i] . "</font>";
        }
        // Otherwise the total is 0
        else $total_string = $total[$i];

        echo "<td>" . $total_string . "</td>\n";
    }

    // Reverse the total array
    $total = array_reverse($total);

    // Get the number of open risks
    $open_risks_today = get_open_risks();

    // Start the total open risks array with the open risks today
    $total_open_risks[] = $open_risks_today;

    // For each of the past 12 months
    for ($i=1; $i<=$months; $i++)
    {
        $total_open_risks[$i] = $total_open_risks[$i-1] - $total[$i-1];
    }

    // Reverse the total open risks array
    $total_open_risks = array_reverse($total_open_risks);
    
    echo "</tr>\n";
    echo "<tr class=\"text-light bg-dark\">\n";
    echo "<th scope=\"row\">" . $escaper->escapeHtml($lang['TotalOpenRisks']) . "</th>\n";

    // For each of the past 12 months
    for ($i=0; $i<=$months; $i++)
    {
        // Get the total number of risks
        $total = $total_open_risks[$i];

        echo "<td>" . $escaper->escapeHtml($total) . "</td>\n";
    }

    echo "</tr>\n";
    echo "</tbody>\n";
    echo "</table>\n";
}

/*************************
 * FUNCTION: RISKS QUERY *
 *************************/
function risks_query($status, $sort, $group, $affected_asset, &$rowCount, $start=0, $length=10, $group_value_from_db="", $custom_query="", $custom_params=array(), $orderColumnName=null, $orderDir="asc")
{
    global $lang;
    // Check the status
    switch ($status)
    {
        // Open risks
        case 0:
                $status_query = " WHERE a.status != \"Closed\" ";
                break;
        // Closed risks
        case 1:
                $status_query = " WHERE a.status = \"Closed\" ";
                break;
        case 2:
        // All risks
                $status_query = " WHERE 1 ";
                break;
        // Default to open risks
        default:
                $status_query = " WHERE a.status != \"Closed\" ";
                break;
    }
    
    if($custom_query){
        $status_query .= $custom_query;
    }

    // Check the sort
    switch ($sort)
    {
        // Calculated Risk
        case 0:
            $sort_name = " calculated_risk DESC ";
            break;
        // ID
        case 1:
            $sort_name = " a.id ASC ";
            break;
        // Subject
        case 2:
            // If the encryption extra is enabled, sort by order_by_subject field
            if (encryption_extra())
            {
                $sort_name = " a.order_by_subject ASC ";
            }else{
                $sort_name = " a.subject ASC ";
            }

            break;
        case 3:
            $sort_name = " residual_risk DESC ";
            break;
        // Default to calculated risk
        default:
            $sort_name = " calculated_risk DESC ";
            break;
    }

    if($orderColumnName){
        $orderDir = (strtolower($orderDir) == "asc") ? "ASC" : "DESC";

        switch ($orderColumnName)
        {
            case "id":
                $sort_name = " id {$orderDir} ";
                break;
            case "risk_status":
                $sort_name = " status {$orderDir} ";
                break;
            case "subject":
                // If the encryption extra is enabled, sort by order_by_subject field
                if (encryption_extra())
                {
                    $sort_name = " a.order_by_subject {$orderDir} ";
                }else{
                    $sort_name = " a.subject {$orderDir} ";
                }

                break;
            case "reference_id":
                $sort_name = " reference_id {$orderDir} ";
                break;
            case "regulation":
                $sort_name = " regulation {$orderDir} ";
                break;
            case "control_number":
                $sort_name = " control_number {$orderDir} ";
                break;
            case "location":
                $sort_name = " location {$orderDir} ";
                break;
            case "source":
                $sort_name = " source {$orderDir} ";
                break;
            case "category":
                $sort_name = " category {$orderDir} ";
                break;
            case "team":
                $sort_name = " team {$orderDir} ";
                break;
            case "technology":
                $sort_name = " technology {$orderDir} ";
                break;
            case "owner":
                $sort_name = " owner {$orderDir} ";
                break;
            case "manager":
                $sort_name = " manager {$orderDir} ";
                break;
            case "submitted_by":
                $sort_name = " submitted_by {$orderDir} ";
                break;
            case "scoring_method":
                $sort_name = " scoring_method {$orderDir} ";
                break;
            case "calculated_risk":
                $sort_name = " calculated_risk {$orderDir} ";
                break;
            case "residual_risk":
                $sort_name = " residual_risk {$orderDir} ";
                break;
            case "submission_date":
                $sort_name = " submission_date {$orderDir} ";
                break;
            case "review_date":
                $sort_name = " review_date {$orderDir} ";
                break;
            case "project":
                $sort_name = " project {$orderDir} ";
                break;
            case "mitigation_planned":
                $sort_name = " mitigation_id {$orderDir} ";
                break;
            case "next_step":
                $sort_name = " next_step {$orderDir} ";
                break;
            case "affected_assets":
                $sort_name = " affected_assets {$orderDir} ";
                break;
            case "planning_strategy":
                $sort_name = " planning_strategy {$orderDir} ";
                break;
            case "mitigation_effort":
                $sort_name = " mitigation_effort {$orderDir} ";
                break;
            case "mitigation_cost":
                $sort_name = " mitigation_min_cost {$orderDir} ";
                break;
            case "mitigation_owner":
                $sort_name = " mitigation_min_cost {$orderDir} ";
                break;
            case "mitigation_team":
                $sort_name = " mitigation_team {$orderDir} ";
                break;
            case "mitigation_date":
                $sort_name = " mitigation_date {$orderDir} ";
                break;
        }
    }

    // Check the group
    switch ($group)
    {
        // None
        case 0:
                $order_query = "GROUP BY id ORDER BY" . $sort_name;
                $group_name = "none";
                break;
        // Risk Level
        case 1:
                $order_query = "GROUP BY id ORDER BY calculated_risk DESC, " . $sort_name;
                $group_name = "risk_level";
                break;
        // Status
        case 2:
                $order_query = "GROUP BY id ORDER BY a.status," . $sort_name;
                $group_name = "status";
                break;
        // Site/Location
        case 3:
                $order_query = "GROUP BY id ORDER BY location," . $sort_name;
                $group_name = "location";
                break;
        // Source
        case 4:
                $order_query = "GROUP BY id ORDER BY source," . $sort_name;
                $group_name = "source";
                break;
        // Category
        case 5:
                $order_query = "GROUP BY id ORDER BY category," . $sort_name;
                $group_name = "category";
                break;
        // Team
        case 6:
                $order_query = "GROUP BY id ORDER BY team," . $sort_name;
                $group_name = "team";
                break;
        // Technology
        case 7:
                $order_query = "GROUP BY id ORDER BY technology," . $sort_name;
                $group_name = "technology";
                break;
        // Owner
        case 8:
                $order_query = "GROUP BY id ORDER BY owner," . $sort_name;
                $group_name = "owner";
                break;
        // Owners Manager
        case 9:
                $order_query = "GROUP BY id ORDER BY manager," . $sort_name;
                $group_name = "manager";
                break;
        // Risk Scoring Method
        case 10:
                $order_query = "GROUP BY id ORDER BY scoring_method," . $sort_name;
                $group_name = "scoring_method";
                break;
        // Regulation
        case 11:
                $order_query = "GROUP BY id ORDER BY regulation," . $sort_name;
                $group_name = "regulation";
                break;
        // Project
        case 12:
                $order_query = "GROUP BY id ORDER BY project," . $sort_name;
                $group_name = "project";
                break;
        // Next Step
        case 13:
                $order_query = "GROUP BY id ORDER BY next_step," . $sort_name;
                $group_name = "next_step";
                break;
        // Month Submitted
        case 14:
                $order_query = "GROUP BY id ORDER BY submission_date DESC," . $sort_name;
                $group_name = "month_submitted";
                break;
        // Default to calculated risk
        default:
                $order_query = "GROUP BY id ORDER BY" . $sort_name;
                $group_name = "none";
                break;
    }
    
    // Filter by affected_asset
    if($affected_asset){
        $whereQuery = " where t1.asset_id=:affected_asset ";
    }else{
        $whereQuery = " where 1 ";
    }
    
    $group_field_name = "";
    if($group_name != "none"){
        if($group_name == "month_submitted"){
            $group_value_from_db = date('Y-m', strtotime($group_value_from_db))."%"; 
            $whereQuery .= " and t1.submission_date like :group_value ";
        }else{
            switch($group_name){
                case "risk_level":
                    $group_value_from_db = get_risk_level_name($group_value_from_db);
                    $group_field_name = " t1.risk_level_name";
                break;
                default:
                    $group_field_name = " t1.{$group_name} ";
                break;
            }
            $whereQuery .= " and ({$group_field_name} = :group_value or :group_value = '' and {$group_field_name} is null) ";
        }
    }

    $query = "SELECT a.id AS id, a.status, a.subject, a.reference_id, a.control_number, a.submission_date, a.last_update, a.review_date, a.mgmt_review, a.assessment as risk_assessment, a.notes as additional_notes, b.scoring_method, b.calculated_risk, p.mitigation_percent, ROUND(b.calculated_risk - (b.calculated_risk * GREATEST(IFNULL(p.mitigation_percent,0), IFNULL(MAX(fc.mitigation_percent), 0)) / 100), 2) as residual_risk, c.name AS location, d.name AS category, GROUP_CONCAT(DISTINCT e.name SEPARATOR ', ') AS team, GROUP_CONCAT(DISTINCT f.name SEPARATOR ', ') AS technology, g.name AS owner, h.name AS manager, i.name AS submitted_by, j.name AS regulation, k.name AS project, l.next_review, m.name AS next_step, GROUP_CONCAT(DISTINCT n.asset SEPARATOR ', ') AS affected_assets, o.closure_date, q.name AS planning_strategy, p.planning_date, r.name AS mitigation_effort, s.min_value AS mitigation_min_cost, s.max_value AS mitigation_max_cost, t.name AS mitigation_owner, u.name AS mitigation_team, p.submission_date AS mitigation_date, v.name AS source, p.id mitigation_id, p.current_solution, p.security_recommendations, p.security_requirements, ifnull((SELECT name FROM `risk_levels` WHERE value<=b.calculated_risk ORDER BY value DESC LIMIT 1), '{$lang['Insignificant']}') as risk_level_name, n.asset_id
    FROM risks a LEFT JOIN risk_scoring b ON a.id = b.id LEFT JOIN location c ON a.location = c.value LEFT JOIN category d ON a.category = d.value LEFT JOIN team e ON FIND_IN_SET(e.value, a.team) LEFT JOIN technology f ON FIND_IN_SET(f.value, a.technology)  LEFT JOIN user g ON a.owner = g.value LEFT JOIN user h ON a.manager = h.value LEFT JOIN user i ON a.submitted_by = i.value LEFT JOIN regulation j ON a.regulation = j.value LEFT JOIN projects k ON a.project_id = k.value LEFT JOIN mgmt_reviews l ON a.mgmt_review = l.id LEFT JOIN next_step m ON l.next_step = m.value LEFT JOIN risks_to_assets n ON a.id = n.risk_id LEFT JOIN closures o ON a.close_id = o.id LEFT JOIN mitigations p ON a.id = p.risk_id LEFT JOIN framework_controls fc ON FIND_IN_SET(fc.id, p.mitigation_controls) LEFT JOIN planning_strategy q ON p.planning_strategy = q.value LEFT JOIN mitigation_effort r ON p.mitigation_effort = r.value LEFT JOIN asset_values s ON p.mitigation_cost = s.id LEFT JOIN user t ON p.mitigation_owner = t.value LEFT JOIN team u ON p.mitigation_team = u.value LEFT JOIN source v ON a.source = v.value " . $status_query ;
        
    // If the team separation extra is enabled
    if (team_separation_extra())
    {
        // Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Get the separation query string
        $separation_query = get_user_teams_query("a", false, true);

        $query .= $separation_query;
    }
    $query .= $order_query;
    
    $start = (int)$start;
    $length = (int)$length;
    
    if($length == -1)
    {
        $limitQuery = "";
    }
    else
    {
        $limitQuery = "Limit {$start}, {$length}";
    }
    
    $query = "
        select SQL_CALC_FOUND_ROWS t1.*
        from (
            {$query}
        ) t1
        {$whereQuery}
        {$limitQuery}
    ";
    // Query the database
    $db = db_open();
    $stmt = $db->prepare($query);
    if($group_name != "none"){
        $stmt->bindParam(":group_value", $group_value_from_db, PDO::PARAM_STR, 250);
    }
    
    if($affected_asset){
        $stmt->bindParam(":affected_asset", $affected_asset, PDO::PARAM_STR, 250);
    }
    
    if($custom_query){
        // Set params for teams, owners, owner managers
        for($i=0; $i<count($custom_params); $i++){
            $stmt->bindParam(":param".$i, $custom_params[$i]);
        }
    }
    
    $stmt->execute();
    db_close($db);

    // Store the results in the risks array
    $risks = $stmt->fetchAll();

    $stmt = $db->prepare("SELECT FOUND_ROWS();");
    $stmt->execute();
    $rowCount = $stmt->fetchColumn();
    
    // Initialize the data array
    $data = array();
//    $rowCount = count($risks);
//    if($length == -1){
//        $length = $rowCount;
//        $start = 0;
//    }
    
    $risk_levels = get_risk_levels();
    $review_levels = get_review_levels();


    // For each risk in the risks array
//    for( $i = $start; $i < $start + $length && $i<$rowCount && $risks[$i]; $i++ ){
    foreach($risks as $risk){
//            $risk = $risks[$i];
            $risk_id = (int)$risk['id'];
            $status = $risk['status'];
            $subject = try_decrypt($risk['subject']);
            $reference_id = $risk['reference_id'];
            $control_number = $risk['control_number'];
            $submission_date = $risk['submission_date'];
            $last_update = $risk['last_update'];
            $review_date = $risk['review_date'];
            // If the risk hasn't been reviewed yet
            if ($review_date == "0000-00-00 00:00:00")
            {
                // Set the review date to empty
                $review_date = "";
            }
            // Otherwise set the review date to the proper format
            else $review_date = date(get_default_datetime_format("H:i"), strtotime($review_date));
            
            $scoring_method = get_scoring_method_name($risk['scoring_method']);
            $calculated_risk = (float)$risk['calculated_risk'];
            
            $residual_risk = (float)$risk['residual_risk'];
            $color = get_risk_color_from_levels($risk['calculated_risk'], $risk_levels);
            $residual_color = get_risk_color_from_levels($risk['residual_risk'], $risk_levels);
            $risk_level = get_risk_level_name_from_levels($risk['calculated_risk'], $risk_levels);
            $residual_risk_level = get_risk_level_name_from_levels($risk['residual_risk'], $risk_levels);
            $location = $risk['location'];
            $source = $risk['source'];
            $category = $risk['category'];
            $team = $risk['team'];
            $technology = $risk['technology'];
            $owner = $risk['owner'];
            $manager = $risk['manager'];
            $submitted_by = $risk['submitted_by'];
            $regulation = $risk['regulation'];
            $project = try_decrypt($risk['project']);
            $mitigation_id = $risk['mitigation_id'];
            $mgmt_review = $risk['mgmt_review'];
            $days_open = dayssince($risk['submission_date']);
            // If next_review_date_uses setting is Residual Risk.
            if(get_setting('next_review_date_uses') == "ResidualRisk")
            {
                $next_review_date = next_review($residual_risk_level, $risk_id, $risk['next_review'], false, $review_levels);
                $next_review_date_html = next_review($residual_risk_level, $risk_id, $risk['next_review'], true, $review_levels);
            }
            // If next_review_date_uses setting is Inherent Risk.
            else
            {
                $next_review_date = next_review($risk_level, $risk_id, $risk['next_review'], false, $review_levels);
                $next_review_date_html = next_review($risk_level, $risk_id, $risk['next_review'], true, $review_levels);
            }
            $next_step = $risk['next_step'];
            $affected_assets = $risk['affected_assets'];
            $risk_assessment = try_decrypt($risk['risk_assessment']);
            $additional_notes = try_decrypt($risk['additional_notes']);
            $current_solution = try_decrypt($risk['current_solution']);
            $security_recommendations = try_decrypt($risk['security_recommendations']);
            $security_requirements = try_decrypt($risk['security_requirements']);
            $month_submitted = date('Y F', strtotime($risk['submission_date']));
            $planning_strategy = $risk['planning_strategy'];
            $planning_date  =  ($risk['planning_date'] && $risk['planning_date'] != "0000-00-00") ? date(get_default_date_format(), strtotime($risk['planning_date'])) : "";
            $mitigation_effort = $risk['mitigation_effort'];
            $mitigation_min_cost = $risk['mitigation_min_cost'];
            $mitigation_max_cost = $risk['mitigation_max_cost'];
            $mitigation_owner = $risk['mitigation_owner'];
            $mitigation_team = $risk['mitigation_team'];
            $mitigation_date = date(get_default_date_format(), strtotime($risk['mitigation_date'])) ;
            $closure_date = $risk['closure_date'];


            // If the mitigation costs are empty
            if (empty($mitigation_min_cost) && empty($mitigation_max_cost))
            {
                    // Return no value
                    $mitigation_cost = "";
            }
            else $mitigation_cost = "$" . $mitigation_min_cost . " to $" . $mitigation_max_cost;

            // If the group name is not none
            if ($group_name != "none")
            {
                    $group_value = ${$group_name};

                    // If the selected group value is empty
                    if ($group_value == "")
                    {
                            // Current group is Unassigned
                            $group_vaue = $lang['Unassigned'];
                    }
            }
            else $group_value = $group_name;

            // Create the new data array
            $data[] = array("id" => $risk_id, "status" => $status, "subject" => $subject, "reference_id" => $reference_id, "control_number" => $control_number, "submission_date" => $submission_date, "last_update" => $last_update, "review_date" => $review_date, "scoring_method" => $scoring_method, "calculated_risk" => $calculated_risk, "residual_risk" => $residual_risk, "color" => $color, "residual_color" => $residual_color, "risk_level" => $risk_level, "residual_risk_level" => $residual_risk_level, "location" => $location, "source" => $source, "category" => $category, "team" => $team, "technology" => $technology, "owner" => $owner, "manager" => $manager, "submitted_by" => $submitted_by, "regulation" => $regulation, "project" => $project, "mgmt_review" => $mgmt_review, "days_open" => $days_open, "next_review_date" => $next_review_date, "next_review_date_html" => $next_review_date_html, "next_step" => $next_step, "affected_assets" => $affected_assets, "risk_assessment" => $risk_assessment, "additional_notes" => $additional_notes, "current_solution" => $current_solution, "security_recommendations" => $security_recommendations, "security_requirements" => $security_requirements, "month_submitted" => $month_submitted, "planning_strategy" => $planning_strategy, "mitigation_id" => $mitigation_id, "planning_date" => $planning_date, "mitigation_effort" => $mitigation_effort, "mitigation_min_cost" => $mitigation_min_cost, "mitigation_max_cost" => $mitigation_max_cost, "mitigation_cost" => $mitigation_cost, "mitigation_owner" => $mitigation_owner, "mitigation_team" => $mitigation_team, "mitigation_date" => $mitigation_date, "group_name" => $group_name, "group_value" => $group_value, 'closure_date' => $closure_date);
    }

    // Return the data array
    return $data;
}

/***************************
 * FUNCTION: GET PIE ARRAY *
 ***************************/
function get_pie_array($filter = null, $teams = false)
{
    if($teams !== false){
        if($teams == ""){
            $teams_query = " AND 0 ";
        }else{
            $options = explode(",", $teams);
            $teams_query = generate_or_query($options, 'team', 'a');
            $teams_query = " AND ( {$teams_query} ) ";
        }
    }else{
        $teams_query = "";
    }

    // Open the database connection
    $db = db_open();

    // Check the filter for the query to use
    switch($filter)
    {
               case 'status':
                        $field = "status";
                        $stmt = $db->prepare("SELECT a.id, a.status FROM `risks` a WHERE a.status != \"Closed\" {$teams_query} ORDER BY a.status DESC");
                        $stmt->execute();
                        break;
                case 'location':
                        $field = "name";
                        $stmt = $db->prepare("SELECT id, b.name FROM `risks` a LEFT JOIN `location` b ON a.location = b.value WHERE status != \"Closed\" {$teams_query} ORDER BY b.name DESC");
                        $stmt->execute();
                        break;
                case 'source':
                        $field = "name";
                        $stmt = $db->prepare("SELECT id, b.name FROM `risks` a LEFT JOIN `source` b ON a.source = b.value WHERE status != \"Closed\" {$teams_query} ORDER BY b.name DESC");
                        $stmt->execute();
                        break;
                case 'category':
                        $field = "name";
                        $stmt = $db->prepare("SELECT id, b.name FROM `risks` a LEFT JOIN `category` b ON a.category = b.value WHERE status != \"Closed\" {$teams_query} ORDER BY b.name DESC");
                        $stmt->execute();
                        break;
                case 'team':
                        $field = "name";
                        $stmt = $db->prepare("SELECT id, b.name FROM `risks` a LEFT JOIN `team` b ON a.team = b.value WHERE status != \"Closed\" {$teams_query} ORDER BY b.name DESC");
                        $stmt->execute();
                        break;
                case 'technology':
                        $field = "name";
                        $stmt = $db->prepare("SELECT id, b.name FROM `risks` a LEFT JOIN `technology` b ON a.technology = b.value WHERE status != \"Closed\" {$teams_query} ORDER BY b.name DESC");
                        $stmt->execute();
                        break;
                case 'owner':
                        $field = "name";
                        $stmt = $db->prepare("SELECT id, b.name FROM `risks` a LEFT JOIN `user` b ON a.owner = b.value WHERE status != \"Closed\" {$teams_query} ORDER BY b.name DESC");
                        $stmt->execute();
                        break;
                case 'manager':
                        $field = "name";
                        $stmt = $db->prepare("SELECT id, b.name FROM `risks` a LEFT JOIN `user` b ON a.manager = b.value WHERE status != \"Closed\" {$teams_query} ORDER BY b.name DESC");
                        $stmt->execute();
                        break;
                case 'scoring_method':
                        $field = "name";
                        $stmt = $db->prepare("SELECT a.id, CASE WHEN scoring_method = 5 THEN 'Custom' WHEN scoring_method = 4 THEN 'OWASP' WHEN scoring_method = 3 THEN 'DREAD' WHEN scoring_method = 2 THEN 'CVSS' WHEN scoring_method = 1 THEN 'Classic' END AS name, COUNT(*) AS num FROM `risks` a LEFT JOIN `risk_scoring` b ON a.id = b.id WHERE status != \"Closed\" ORDER BY b.scoring_method DESC");
                        $stmt->execute();
                        break;
                case 'close_reason':
                        if($teams !== false){
                            if($teams == ""){
                                $teams_query = " AND 0 ";
                            }else{
                                $options = explode(",", $teams);
                                $teams_query = generate_or_query($options, 'team', 'c');
                                $teams_query = " AND ( {$teams_query} ) ";
                            }
                        }else{
                            $teams_query = "";
                        }

                        $field = "name";
                        $stmt = $db->prepare("SELECT a.close_reason, a.risk_id as id, b.name, MAX(closure_date) FROM `closures` a JOIN `close_reason` b ON a.close_reason = b.value JOIN `risks` c ON a.risk_id = c.id WHERE c.status = \"Closed\" {$teams_query} GROUP BY risk_id ORDER BY name DESC;");
                        $stmt->execute();
                        break;
                default:
                        $stmt = $db->prepare("SELECT a.id, a.status, b.name AS location, c.name AS source, d.name AS category, GROUP_CONCAT(DISTINCT e.name SEPARATOR ', ') AS team, GROUP_CONCAT(DISTINCT f.name SEPARATOR ', ') AS technology, g.name AS owner, h.name AS manager, CASE WHEN scoring_method = 5 THEN 'Custom' WHEN scoring_method = 4 THEN 'OWASP' WHEN scoring_method = 3 THEN 'DREAD' WHEN scoring_method = 2 THEN 'CVSS' WHEN scoring_method = 1 THEN 'Classic' END AS scoring_method FROM `risks` a LEFT JOIN `location` b ON a.location = b.value LEFT JOIN `source` c ON a.source = c.value LEFT JOIN `category` d ON a.category = d.value LEFT JOIN `team` e ON FIND_IN_SET(e.value, a.team) LEFT JOIN `technology` f ON FIND_IN_SET(f.value, a.technology) LEFT JOIN `user` g ON a.owner = g.value LEFT JOIN `user` h ON a.manager = h.value LEFT JOIN `risk_scoring` i ON a.id = i.id WHERE a.status != \"Closed\" {$teams_query} GROUP BY a.id; ");
                        $stmt->execute();
                        break;
    }

        // Store the list in the array
        $array = $stmt->fetchAll();

        // Close the database connection
        db_close($db);

        // If team separation is enabled
        if (team_separation_extra())
        {
            //Include the team separation extra
            require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

            // Strip out risks the user should not have access to
            $array = strip_no_access_risks($array);
        }

    return $array;
}

/************************
 * FUNCTION: SORT ARRAY *
 ************************/
function sort_array($array, $sort)
{
    // Create the sort array
    $sortArray = array();

    // For each risk in the array
    foreach ($array as $risk)
    {
        // For each key value pair in the risk
        foreach ($risk as $key=>$value)
        {
            // If the key is not yet set in the sort array
            if (!isset($sortArray[$key]))
            {
                // Create a new array at that key
                $sortArray[$key] = array();
            }
            // Set the key to the value
            $sortArray[$key][] = $value;
        }
    }

    // Sort the array based on the sort value provided
    array_multisort($sortArray[$sort],SORT_ASC,$array);

    // Return the sorted array
    return $array;
}

/********************************
 * FUNCTION: COUNT ARRAY VALUES *
 ********************************/
function count_array_values($array, $sort)
{
    global $lang;

        // Initialize the value and count
        $value = "";
    $value_count = 1;

        // Count the number of risks for each value
        foreach ($array as $risk)
        {
                // Get the current value
                $current_value = $risk[$sort];
        if ($current_value == null) $current_value = $lang['Unassigned'];

                // If the value is not new
                if ($current_value == $value)
                {
                        $value_count++;
                }
                else
                {
                        // If the value is not empty
                        if ($value != "")
                        {
                                // Add the previous value to the array
                                $value_array[] = array($sort=>$value, 'num'=>$value_count);
                        }

                        // Set the new value and reset the count
                        $value = $current_value;
                        $value_count = 1;
                }
        }

        // Update the final value
        $value_array[] = array($sort=>$value, 'num'=>$value_count);

        // Create the data array
        foreach ($value_array as $row)
        {
            $data[] = array($row[$sort], (int)$row['num']);
        }

    // Return the data
        return $data;
}

/************************************
 * FUNCTION: GET OPENED RISKS ARRAY *
 ************************************/
function get_opened_risks_array($timeframe)
{
    // If team separation is not enabled
    if (!team_separation_extra())
    {
        // Open the database connection
        $db = db_open();

        // Query the database
        $stmt = $db->prepare("SELECT id, submission_date FROM risks ORDER BY submission_date;");
        $stmt->execute();

        // Store the list in the array
        $array = $stmt->fetchAll();

        // Close the database connection
        db_close($db);
    }
    // Otherwise team separation is enabled
    else
    {
        //Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Get the opened risks stripped
        $array = strip_get_opened_risks_array();
    }

    // Set the defaults
    $counter = -1;
    $current_date = "";
    $open_date = array();
    $open_count = array();

    // For each row
    foreach ($array as $key=>$row)
    {
        // If the timeframe is by day
        if ($timeframe === "day")
        {
            // Set the date to the day
            $date = date('Y-m-d', strtotime($row['submission_date']));
        }
        // If the timeframe is by month
        else if ($timeframe === "month")
        {
            // Set the date to the month
            $date = date('Y-m', strtotime($row['submission_date']));
        }
        // If the timeframe is by year
        else if ($timeframe === "year")
        {
            // Set the date to the year
            $date = date('Y', strtotime($row['submission_date']));
        }

        // If the date is different from the current date
        if ($current_date != $date)
        {
            // Increment the counter
            $counter = $counter + 1;

            // Set the current date
            $current_date = $date;

            // Add the date
            $open_date[$counter] = $current_date;

            // Set the open count to 1
            $open_count[$counter] = 1;

            // If this is the first entry
            if ($counter == 0)
            {
                // Set the open total to 1
                $open_total[$counter] = 1;
            }
            // Otherwise, add the value of this row to the previous value
            else $open_total[$counter] = $open_total[$counter-1] + 1;
        }
        // Otherwise, if the date is the same
        else
        {
            // Increment the open count
            $open_count[$counter] = $open_count[$counter] + 1;

            // Update the open total
            $open_total[$counter] = $open_total[$counter] + 1;
        }
    }

    // Return the open date array
    return array($open_date, $open_count);
}

/************************************
 * FUNCTION: GET CLOSED RISKS ARRAY *
 ************************************/
function get_closed_risks_array($timeframe)
{
        // If team separation is not enabled
        if (!team_separation_extra())
        {
                // Open the database connection
                $db = db_open();

                // Query the database
        $stmt = $db->prepare("SELECT a.risk_id as id, a.closure_date, c.status FROM closures a LEFT JOIN risks c ON a.risk_id=c.id WHERE a.closure_date=(SELECT max(b.closure_date) FROM closures b WHERE a.risk_id=b.risk_id) AND c.status='Closed' order by closure_date;");
                $stmt->execute();

                // Store the list in the array
                $array = $stmt->fetchAll();

                // Close the database connection
                db_close($db);
        }
        // Otherwise team separation is enabled
        else
        {
                //Include the team separation extra
                require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

                // Get the closed risks stripped
                $array = strip_get_closed_risks_array();
        }

        // Set the defaults
        $counter = -1;
        $current_date = "";
        $close_date = array();
    $close_count = array();

        // For each row
        foreach ($array as $key=>$row)
        {
                // If the timeframe is by day
                if ($timeframe === "day")
                {
                        // Set the date to the day
                        $date = date('Y-m-d', strtotime($row['closure_date']));
                }
                // If the timeframe is by month
                else if ($timeframe === "month")
                {
                        // Set the date to the month
                        $date = date('Y-m', strtotime($row['closure_date']));
                }
                // If the timeframe is by year
                else if ($timeframe === "year")
                {
                        // Set the date to the year
                        $date = date('Y', strtotime($row['closure_date']));
                }

                // If the date is different from the current date
                if ($current_date != $date)
                {
                        // Increment the counter
                        $counter = $counter + 1;

                        // Set the current date
                        $current_date = $date;

                        // Add the date
                        $close_date[$counter] = $current_date;

                        // Set the close count to 1
                        $close_count[$counter] = 1;

                        // If this is the first entry
                        if ($counter == 0)
                        {
                                // Set the close total to 1
                                $close_total[$counter] = 1;
                        }
                        // Otherwise, add the value of this row to the previous value
                        else $close_total[$counter] = $close_total[$counter-1] + 1;
                }
                // Otherwise, if the date is the same
                else
                {
                        // Increment the closed count
                        $close_count[$counter] = $close_count[$counter] + 1;

                        // Update the close total
                        $close_total[$counter] = $close_total[$counter] + 1;
                }
        }

        // Return the close date array
        return array($close_date, $close_count);
}

/****************************************
 * FUNCTION: ENCODE DATA BEFORE DISPLAY *
 ****************************************/
function encode_data_before_display($array)
{
    global $escaper;

        // Create a data array
        $data = array();

        // For each element in the array
        foreach ($array as $element)
        {
            $name = $escaper->escapeHtml($element[0]);
                $count = $element[1];
                $data[] = array($name, $count);
        }

    // Return the data array
    return $data;
}

/************************************
 * FUNCTION: RISKS AND CONTROLS TABLE *
 ************************************/
function risks_and_control_table($report)
{
    global $lang;
    global $escaper;

    $data = array();

    // Open the database
    $db = db_open();

    // Check the report
    $query = "SELECT * FROM mitigations a LEFT JOIN risks b ON b.mitigation_id = a.id LEFT JOIN risk_scoring c ON b.id = c.id WHERE b.mitigation_id > 0 AND a.mitigation_controls ";

    if ( $report == 0 ) {
        $query .= "ORDER BY a.risk_id DESC";
    } else {
        $query .= "ORDER BY a.risk_id ASC";
    }

    $stmt = $db->prepare($query);
    $stmt->execute();

    // Store the results in the rows array
    $rows = $stmt->fetchAll();

    // If team separation is enabled
    if (team_separation_extra())
    {
        // Include the team separation extra
        require_once(realpath(__DIR__ . '/../extras/separation/index.php'));

        // Strip out risks the user should not have access to
        $rows = strip_no_access_risks($rows);
    }

    // Set the current group to empty
    $current_group = "";

    $i = 0;
    if ($report == 0)
    {
        // For each row
        foreach ($rows as $row)
        {
            // Get the variables for the row
            $risk_id = (int)$row['risk_id'];
            $control_name = $row['mitigation_controls'];
            $status = $row['status'];
            $subject = try_decrypt($row['subject']);
            $calculated_risk = $row['calculated_risk'];
            $color = get_risk_color($calculated_risk);
            $dayssince = dayssince($row['submission_date']);
            $location = $row['location'];
            $mitigation_team = $row['mitigation_team'];
            
            $ddd = get_control_number($control_name);

            if ( $ddd ) {

                foreach ($ddd as $key => $value) {
                    
                // Display the table header
                    $some_control = get_framework_controls( $value );

                    $data[$i] = $some_control[0];
                    $data[$i]['risk_id'] = $risk_id;
                    $data[$i]['status'] = $status;
                    $data[$i]['subject'] = $subject;
                    $data[$i]['calculated_risk'] = $calculated_risk;
                    $data[$i]['color'] = $color;
                    $data[$i]['dayssince'] = $dayssince;
                    $data[$i]['location'] = $location;
                    $data[$i]['mitigation_team'] = $mitigation_team;
                    
                    $i++;
                }
            }
                    
        }

        for ($i=0; $i < sizeof($data) ; $i++) { 

            for ($j= $i+1; $j < sizeof($data) ; $j++) { 

                if ( $data[$i]['id'] >= $data[$j]['id'] ) {
                    $temp = $data[$i];
                    $data[$i] = $data[$j];
                    $data[$j] = $temp;
                }
            }
        }
        
        $test = 0;
        $temp_control_id = 0;

        for ($i=0; $i < sizeof($data) ; $i++) {

            if ( $i >0 && $data[$i-1]['risk_id'] == $data[$i]['risk_id'] && $data[$i-1]['id'] == $data[$i]['id']) {
                 continue;
             } 

            if ( $test == 0 && $temp_control_id == 0) {
                $temp_control_id = $data[$i]['id'];
            } else if ( $temp_control_id == $data[$i]['id']) {
                $test = 1;
            } else if ( $temp_control_id != $data[$i]['id']) {
                $test = 0;
                $temp_control_id = $data[$i]['id'];
            }

            $risk_id = $data[$i]['risk_id'];
            $status = $data[$i]['status'];
            $subject = $data[$i]['subject'];
            $calculated_risk = $data[$i]['calculated_risk'];
            $color = $data[$i]['color'];
            $dayssince = $data[$i]['dayssince'];

            $mitigation_control_location = (isset($data[$i]['location']) ? get_name_by_value("location",$data[$i]['location']) : "N/A");
            $mitigation_control_location = ($mitigation_control_location != "" ? $mitigation_control_location : "N/A");
            $mitigation_control_team = (isset($data[$i]['mitigation_team']) ? get_name_by_value("team",$data[$i]['mitigation_team']) : "N/A");
            $mitigation_control_team = ($mitigation_control_team != "" ? $mitigation_control_team : "N/A");
            
            if ( $test == 0 ) {

                echo "<table class=\"table table-bordered table-condensed sortable\">\n";
                echo "<thead>\n";
                echo "<tr>\n";
                            echo "<th colspan=\"5\"><center>" . $escaper->escapeHtml($lang['ControlLongName'])  . ":&nbsp;&nbsp;" . $escaper->escapeHtml($data[$i]['long_name']) ."</br>" . $escaper->escapeHtml($lang['ControlShortName']) . ":&nbsp;&nbsp;". $escaper->escapeHtml($data[$i]['short_name']) ."</center></th>\n";
                    echo "</tr>\n";
                echo "<tr>\n";
                    echo "<th align=\"left\" width=\"50px\">". $escaper->escapeHtml($lang['ID']) ."</th>\n";
                echo "<th align=\"left\" width=\"150px\">". $escaper->escapeHtml($lang['Status']) ."</th>\n";
                echo "<th align=\"left\" width=\"300px\">". $escaper->escapeHtml($lang['Subject']) ."</th>\n";
                            echo "<th align=\"left\" width=\"200px\">". $escaper->escapeHtml($lang['SiteLocation']) ."</th>\n";
                            echo "<th align=\"left\" width=\"200px\">". $escaper->escapeHtml($lang['Team']) ."</th>\n";
                    echo "<th align=\"left\" width=\"100px\">". $escaper->escapeHtml($lang['InherentRisk']) ."</th>\n";
                    echo "<th align=\"left\" width=\"100px\">". $escaper->escapeHtml($lang['DaysOpen']) ."</th>\n";
                echo "</tr>\n";
                echo "</thead>\n";
                    
            }
        
            // Display the individual asset information
            echo "<tbody>\n";
            echo "<tr>\n";
                    echo "<td align=\"left\" width=\"50px\"><a href=\"../management/view.php?id=".(1000 + $risk_id)."\">".(1000 + $risk_id)."</a></td>\n";
                    echo "<td align=\"left\" width=\"150px\">". $status ."</td>\n";
                    echo "<td align=\"left\" width=\"300px\">". $subject ."</td>\n";
                    echo "<td align=\"left\" width=\"200px\">". $mitigation_control_location ."</td>\n";
                    echo "<td align=\"left\" width=\"200px\">". $mitigation_control_team ."</td>\n";
            echo "<td align=\"center\" class=\"risk-cell\" bgcolor=\"" . $escaper->escapeHtml($color) . "\" width=\"100px\">" . $escaper->escapeHtml($calculated_risk) . "<span class=\"risk-color\" style=\"background-color:" . $escaper->escapeHtml($color) . "\"></span></td>\n";
                    echo "<td align=\"center\" width=\"100px\">". $dayssince ."</td>\n";
                        echo "</tr>\n";
                
        }
        
        // End the last table
        echo "</tbody>\n";
        echo "</table>\n";
        
    }

    // If assets by risk
    if ($report == 1)
    {
        $temp_risk_id = 0;

        // For each row
        foreach ($rows as $row)
        {
            // Get the variables for the row
            $risk_id = (int)$row['id'];
            $status = $row['status'];
            $subject = try_decrypt($row['subject']);
            $calculated_risk = $row['calculated_risk'];
            $control_name = $row['mitigation_controls'];
            
            // Get the risk color
            $color = get_risk_color($calculated_risk);

            $ddd = get_control_number($control_name);

            if ( $ddd ) {

                foreach ($ddd as $key => $value) {
                    // Display the table header
                    $some_control = get_framework_controls_long_name( $value );

                    $control_long_name  = $some_control['long_name'];

                    $tb1 = '<table width="100%" class="table table-bordered table-condensed" role="grid" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <th style="background-color:' . $escaper->escapeHtml($color) . '" bgcolor="' . $escaper->escapeHtml($color) . '" colspan="5">
                                        <center>
                                            <font color="#000000">'. $escaper->escapeHtml($lang['RiskId']) . ':&nbsp;&nbsp;
                                            <a href="../management/view.php?id='. $escaper->escapeHtml(convert_id($risk_id)) . '" style="color:#000000">'. $escaper->escapeHtml(convert_id($risk_id)) .'</a>
                                            <br>'. $escaper->escapeHtml($lang['Subject']) .':&nbsp;&nbsp;' . $escaper->escapeHtml($subject) . '
                                            <br>'. $escaper->escapeHtml($lang['InherentRisk']) .':&nbsp;&nbsp;'. $escaper->escapeHtml($calculated_risk) .'&nbsp;&nbsp;('. $escaper->escapeHtml(get_risk_level_name($calculated_risk)) .')
                                            </font>
                                        </center>
                                    </th>
                                </tr>
                                <tr role="row" style="height: 0px;">
                                    <th class="sorting_asc" aria-controls="mitigation-controls-table140955b56e1c6c5879" rowspan="1" colspan="1" style="width: 0px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; height: 0px;" aria-sort="ascending" aria-label="&amp;nbsp;: activate to sort column descending">
                                        <div class="dataTables_sizing" style="height:0;overflow:hidden;">&nbsp;
                                        </div>
                                    </th>
                                </tr>
                                    
                            ';
                    
                    $view_details = '<tr role="row" class="odd">
                                    <td class="sorting_1">
                                        <div class="control-block item-block clearfix">
                                            <div class="control-block--header clearfix" data-project="">
                                                <a href="#" id="show-' . $risk_id . '-' . $value . '" class="show-score" onclick="javascript: showControlDetails(' . $value .', ' . $risk_id . ');" style="color: #3f3f3f;"> 
                                                        <i class="fa fa-caret-right"></i>&nbsp; 
                                                <strong>' . $escaper->escapeHtml($lang['ControlLongName']) . '</strong>: &nbsp; &nbsp;'. $escaper->escapeHtml($control_long_name) .'
                                                </a>
                                                    <a href="#" style="color: #3f3f3f; float: left;" id="hide-' . $risk_id . '-' . $value . '" class="hide-score" style="display: none;" onclick="javascript: hideControlDetails(' . $value .', ' . $risk_id . '); " > 
                                                <i class="fa fa-caret-down"></i> &nbsp; 
                                                <strong>' . $escaper->escapeHtml($lang['ControlLongName']) . '</strong>: &nbsp; &nbsp; &nbsp;'. $escaper->escapeHtml($control_long_name) .'
                                                </a>
                                    ';

                    $details_control = '
                                                <div class="control-block--row" id="control-content-' . $risk_id . '-' . $value . '" style="display:none">
                                                </div>
                                        ';

                    $content = $view_details . $details_control;

                    $end1 = '           
                                                <input type="text" name="scroll_top" id="scroll_top" style="display:none" value="">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            ';

                    $end = '    </tbody>
                        </table>
                        ';

                    if ( $temp_risk_id != $risk_id ) {

                        echo $end;
                        echo $tb1;
                        echo $content;
                        $temp_risk_id = $risk_id;

                    } else if ( $temp_risk_id != 0 && $temp_risk_id == $risk_id ) {

                        $temp_risk_id = $risk_id;
                        echo "<hr>" . $content;
                        echo $end1;

                    } else if ( $temp_risk_id != 0 && $temp_risk_id != $risk_id ) {

                        $temp_risk_id = $risk_id;
                        echo $end1;
                        echo $end;

                    } 
                       
                        echo '<script>
                                $(document).ready( function(){
                                        $(".hide-score").css("display","none");
                                    }
                                );
                                
                                function showControlDetails( control_id , risk_id ){
                                
                                    $("#show-"+risk_id + "-" +control_id).hide();
                                    $("#hide-"+risk_id + "-" +control_id).css("display","block");
                                    $("#control-content-"+risk_id + "-" +control_id).css("display","block");
                                    var height = $(window).scrollTop();
                                    
                                    $.ajax({
                                        url: "/api/mitigation_controls/get_mitigation_control_info",
                                        data: { "control_id": control_id, "scroll_top": height },
                                        success: function(response){
                                            $("#control-content-"+risk_id + "-" +control_id).html(response.data["control_info"]);
                                            $(window).scrollTop(response.data["scroll_top"]);
                                            $("#scroll_top").val(response.data["scroll_top"]);
                                            
                                        }
                                    });
                                }
                                
                                function hideControlDetails( control_id , risk_id ){
                                    var scroll_top = $("#scroll_top").val();
                                    $("#hide-"+risk_id + "-" +control_id).css("display","none");
                                    $("#show-"+risk_id + "-" +control_id).show();
                                    $("#control-content-"+risk_id + "-" +control_id).css("display","none");
                                    $(window).scrollTop(scroll_top);
                                }
                                
                              </script>';

                }   

        }

                
        }

    }

    // Close the database
        db_close($db);
}

/*******************************
 * FUNCTION: GET CONTROLS NAME *
 *******************************/
function get_control_number( $control_numbers )
{
    if ( $control_numbers ) {

        $control_number = explode(',', $control_numbers);
        return $control_number;

    } else {
        return false;
    }
    
}

/**********************************
 * FUNCTION: GET IMPACTS COUNT *
 **********************************/
function get_impacts_count()
{
    $db = db_open();
    $stmt = $db->prepare("SELECT count(*) as count FROM impact");
    $stmt->execute();

    // Store the list in the array
    $array = $stmt->fetchAll();

    // Close the database connection
    db_close($db);

    return $array[0]['count'];
}

/**********************************
 * FUNCTION: GET LIKELIHOODS COUNT *
 **********************************/
function get_likelihoods_count()
{
    $db = db_open();
    $stmt = $db->prepare("SELECT count(*) as count FROM likelihood");
    $stmt->execute();

    // Store the list in the array
    $array = $stmt->fetchAll();

    // Close the database connection
    db_close($db);

    return $array[0]['count'];
}
?>
