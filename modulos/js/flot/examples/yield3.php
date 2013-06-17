<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
 <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Flot Examples</title>
    <link href="layout.css" rel="stylesheet" type="text/css"></link>
    <!--[if IE]><script language="javascript" type="text/javascript" src="../excanvas.pack.js"></script><![endif]-->
    <script language="javascript" type="text/javascript" src="../jquery.js"></script>
    <script language="javascript" type="text/javascript" src="../jquery.flot.js"></script>
 </head>
    <body>
    <h1>Flot Examples</h1>

    <div id="placeholder" style="width:600px;height:300px"></div>

    <p>Flot supports lines, points, filled areas, bars and any
    combinations of these, in the same plot and even on the same data
    series.</p>

<script id="source" language="javascript" type="text/javascript">
$(function () {
    var d2 = [[-260413200000, 315.71], [-260413200000, 315.71], [-260413200000, 315.71], [-260413200000, 13]];
    //$.plot($("#placeholder"), [
        
	/*	
	{ label: "Foo", data: [ [10, 1], [17, -14], [30, 5] ] },
    { label: "Bar", data: [ [11, 13], [19, 11], [30, -7] ] }		
	*/
	/*	
		{
			mode: "time",
			label: "Enero",
			data: d2,
            bars: { show: true }
			
        }
	*/
	
	
        $.plot($("#placeholder"),
			[d2], 
			{ 
				xaxis: {
            		bars: { show: true },
					mode: "time",
            		minTickSize: [1, "month"],
            		min: (new Date("1999/01/01")).getTime(),
            		max: (new Date("2000/01/01")).getTime(),
					
        		} 
			});	
			

    //]);
});


/*
        { label: "sin(x)",  data: d1},
        { label: "cos(x)",  data: d2},
        { label: "tan(x)",  data: d3}
    ], {
        lines: { show: true },
        points: { show: true },
        xaxis: {
            ticks: [0, [Math.PI/2, "\u03c0/2"], [Math.PI, "\u03c0"], [Math.PI * 3/2, "3\u03c0/2"], [Math.PI * 2, "2\u03c0"]]
        },
        yaxis: {
            ticks: 10,
            min: -2,
            max: 2
        },
        grid: {
            backgroundColor: "#fffaff"
        }
*/
</script>

 </body>
</html>
